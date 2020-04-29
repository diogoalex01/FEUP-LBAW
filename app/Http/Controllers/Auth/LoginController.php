<?php

namespace App\Http\Controllers\Auth;

use Lang;
use App\User;
use Socialite;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;


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
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function getUser()
    {
        return $request->user();
    }

    public function home()
    {
        return redirect('/');
    }

    /**
     * Redirect the user to the Google authentication page.
     *
     * @return \Illuminate\Http\Response
     */
    public function redirectToProvider()
    {
         return Socialite::driver('google')->scopes(['profile'])->redirect();
        //return Socialite::driver('google')->scopes(['profile', 'https://www.googleapis.com/auth/user.birthday.read'])->redirect();
    }

    /**
     * Obtain the user information from Google.
     *
     * @return \Illuminate\Http\Response
     */
    public function handleProviderCallback()
    {
        try {
            $user = Socialite::driver('google')->user();
        } catch (\Exception $e) {
            return redirect()->back()->with('showModal', "login");
        }
        // check if they're an existing user
        $existingUser = User::where('email', $user->email)->first();
        if ($existingUser) {
            // log them in
            auth()->login($existingUser, true);
        } else {
            // create a new user
            $newUser = new User;
            $name = explode(" ", $user->name);
            $newUser->first_name = $name[0];
            $newUser->last_name = $name[sizeof($name) - 1];
            $newUser->username = strtolower($name[0]) . "_" . strtolower($name[sizeof($name) - 1]) . substr($user->id, 16);
            $newUser->gender = "male";
            $newUser->email = $user->email;
            $newUser->private = false;
            $newUser->password = $user->id;
            $newUser->photo = $user->avatar;
            $newUser->birthday = "1990-10-10";
            $newUser->save();
            auth()->login($newUser, true);
        }

        return redirect()->back();
    }

    /**
     * Get the failed login response instance.
     *
     * @param \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    protected function sendFailedLoginResponse(Request $request)
    {
        return redirect()->back()->with('showModal', "login")->withErrors([
            $this->username() => Lang::get('auth.failed'),
        ]);
    }
}
