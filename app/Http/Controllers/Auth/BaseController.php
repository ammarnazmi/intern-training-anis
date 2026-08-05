<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

class BaseController extends Controller
{
    protected string $guard;
    protected string $broker;

    public function __construct(Request $request)
    {
        $this->guard = $request->segment(1);
        $this->broker = str($this->guard)->plural()->value();

        View::composer('auth.*', fn ($view) => $view->with('guard', $this->guard));
    }
}
