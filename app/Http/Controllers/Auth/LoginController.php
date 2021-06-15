<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LoginController extends Controller
{
    protected $redirectTo = "/bidding";
    public function __construct()
    {
        $this->redirectTo = url('/bidding');
        $this->middleware('guest')->except('logout');
        
    }
    use AuthenticatesUsers;

}
