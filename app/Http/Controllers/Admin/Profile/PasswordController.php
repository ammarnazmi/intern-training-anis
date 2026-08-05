<?php

namespace App\Http\Controllers\Admin\Profile;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdatePasswordRequest;

class PasswordController extends Controller
{
    /**
     * Display update password form.
     */
    public function show()
    {
        return view('admin.profile.password');
    }

    /**
     * Update the password.
     */
    public function update(UpdatePasswordRequest $request)
    {
        $request->user()->update($request->validated());

        return back()->withSuccessNotification(__('The password has been updated.'));
    }
}
