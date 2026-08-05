<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateLocaleRequest;

class LocaleController extends Controller
{
    /**
     * Update user's locale.
     */
    public function __invoke(UpdateLocaleRequest $request)
    {
        $user = $request->user();
        $user->update($request->validated());

        app()->setLocale($user->locale);

        return back();
    }
}
