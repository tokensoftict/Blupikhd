<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    public  $redirectTo = '/admin/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */

    /**
     * @return string
     */


    protected function attemptLogin(Request $request)
    {
        $user = User::where('email',$request->email)->where('role','ADMIN')->first();

        if(!$user or !Hash::check($request->password,$user->password)) return false;

        return $this->guard()->attempt(
            $this->credentials($request), $request->filled('remember')
        );
    }

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }
}
