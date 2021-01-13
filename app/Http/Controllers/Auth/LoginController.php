<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use App\Models\Role;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    private $route;

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
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Request $request)
    {
        $this->middleware('guest')->except('logout');

        session(['last_email' => $request->get('email')]);

        $this->route = \Illuminate\Support\Facades\Route::getCurrentRoute()->getActionName();
    }

    public function username() {
        return 'email';
    }

    public function authenticated(Request $request, $user)
    {
        $this->insertLog('success', $this->route, "Login successfully.", $request->all(), $user->id);
    }

    public function sendFailedLoginResponse(Request $request)
    {
        // action log
        $this->insertLog('error', $this->route, "Login failed.", $request->all());

        throw ValidationException::withMessages([
            $this->username() => [trans('auth.failed')]
        ]);
    }


    /**
     * Validate the user login request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return void
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function validateLogin(Request $request)
    {
        $request->validate([
            // 'captcha' => 'required|captcha',
            $this->username() => 'required|email',
            'password' => 'required|string'
        ]);
    }
}