<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Carbon;
use Onpay\Core\Support\EventStream;

class MainController extends Controller
{
    /**
     * Display the main dashboard page.
     */
    public function index()
    {
        return view('admin.main.index');
    }

    /**
     * Get counts for users.
     */
    public function counts()
    {
        return [
            'users_count' => User::count(),
        ];
    }

    /**
     * Get online users.
     */
    public function onlineUsers()
    {
        $previousLastOnlineTime = 0;

        return (new EventStream())->poll(static function () use (&$previousLastOnlineTime) {
            $lastOnlineTime = cache()->remember(
                str(User::class)->classBasename()->snake()->append('_last_online_time'),
                3600,
                static fn () => ($lastOnlineAt = User::max('last_online_at')) ? Carbon::parse($lastOnlineAt)->getTimestamp() : 0
            );

            if ($lastOnlineTime > $previousLastOnlineTime) {
                $data = User::select('id', 'name', 'last_online_at')
                    ->where('last_online_at', '>', now()->subSeconds(600))
                    ->orderBy('last_online_at', 'desc')
                    ->get();

                $previousLastOnlineTime = $lastOnlineTime;

                if (count($data) > 0) {
                    return $data;
                }
            }

            return null;
        });
    }
}
