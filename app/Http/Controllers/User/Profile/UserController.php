<?php

namespace App\Http\Controllers\User\Profile;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display user's profile.
     */
    public function show(Request $request)
    {
        return view('user.profile.user', ['user' => $request->user()]);
    }

    /**
     * Update user's profile.
     */
    public function update(UserRequest $request)
    {
        $user = $request->user();
        $user->update($request->validated());

        app()->setLocale($user->locale);

        return back()->withSuccessNotification(__('Account profile has been updated.'));
    }
}
