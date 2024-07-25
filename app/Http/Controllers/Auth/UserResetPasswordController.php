<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ResetsPasswords;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Auth;


use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Str;

class UserResetPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */
    

    use ResetsPasswords;
    /**
     * Where to redirect users after resetting their password.
     *
     * @var string
     */
    protected $redirectTo = 'dashboard';

    public function __construct()
    {
        $this->middleware('guest:user');
    }
    protected function guard(){
        return Auth::guard('user');
    }

    protected function broker(){
        return Password::broker('user');
    }

    public function showResetForm(Request $request, $token = null)
    {
        return view('reset_password')->with(
            ['token' => $token, 'email' => $request->email]
        );
    }

    protected function resetPassword($user, $password)
    {
        $this->setUserPassword($user, $password);

        $user->setRememberToken(Str::random(60));

        $user->save();

        event(new PasswordReset($user));
        
        //$this->guard()->login($user);
        Auth::login($user);
    }

}
