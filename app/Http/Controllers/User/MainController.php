<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;

class MainController extends Controller
{
    /**
     * Display the main dashboard page.
     */
    public function index()
    {
        return view('user.main.index');
    }
}
