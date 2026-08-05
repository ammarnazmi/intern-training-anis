<?php

namespace App\Http\Controllers\Admin\Profile;

use App\Http\Controllers\Controller;
use App\Http\Requests\AdminRequest;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display user's profile.
     */
    public function show(Request $request)
    {
        return view('admin.profile.user', ['user' => $request->user()]);
    }

    /**
     * Update user's profile.
     */
    public function update(AdminRequest $request)
    {
        $user = $request->user();
        $user->update($request->validated());

        app()->setLocale($user->locale);

        return back()->withSuccessNotification(__('Account profile has been updated.'));
    }
}
