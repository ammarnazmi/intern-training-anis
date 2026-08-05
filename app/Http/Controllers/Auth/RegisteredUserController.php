<?php

namespace App\Http\Controllers\Auth;

use App\Http\Requests\AdminRequest;
use App\Http\Requests\UserRequest;
use App\Models\Admin;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Onpay\Core\Auth\Authenticatable;

class RegisteredUserController extends BaseController
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        $formRequest = match ($this->guard) {
            'user' => UserRequest::class,
            'admin' => AdminRequest::class,
        };

        return view('auth.register', compact('formRequest'));
    }

    /**
     * Handle an incoming admin registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function storeAdmin(AdminRequest $request): RedirectResponse
    {
        return $this->store(new Admin($request->validated()));
    }

    /**
     * Handle an incoming user registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function storeUser(UserRequest $request): RedirectResponse
    {
        return $this->store(new User($request->validated()));
    }

    /**
     * Handle an incoming registration request.
     */
    protected function store(Authenticatable $user): RedirectResponse
    {
        $user->save();
        $user->refresh();

        event(new Registered($user));

        Auth::guard($this->guard)->login($user);

        return redirect(route($this->guard . '.main', absolute: false));
    }
}
