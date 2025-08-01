<?php

namespace App\Http\Controllers\Front\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TwoFactorAuthenticationContrller extends Controller
{
    public function index() {
        $user = Auth::user();
        return view('front.auth.tow-factor-auth', compact('user'));
    }
}
