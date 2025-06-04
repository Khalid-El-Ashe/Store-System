<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    // this is a callback function and ClaggerFunction
    public function __construct()
    // this function to use the Guard or Middaleware or Policy what you wont
    {

        // $this->middleware(['auth'])->except(); // this is with exception
        // $this->middleware(['auth'])->only('index'); // this is just to index function

    }

    public function index()
    {

        return response()->view('layouts.dashboard');
    }
}
