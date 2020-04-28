<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use App\User;

class AccountsController extends Controller
{
    public function validatePasswordRequest(Request $request)
    {
        //Validate email
        $validator = Validator::make($request->all(), [
            'email' => 'required|exists:member_user,email'
        ]);

        //check if input is valid before moving on
        if ($validator->fails()) {
            return response()->json(array(
                'success' => false,
                'errors' => array('email' => array('Invalid e-mail address.'))
            ), 400);
        }

        $user = User::where('email', '=', $request->email)->first();

        // Check if it's a google account
        if (strlen($user->password) < 32) {
            return response()->json(array(
                'success' => false,
                'errors' => array('email' => array('This user can\'t reset their password.'))
            ), 400);
        }

        //Create Password Reset Token
        $recover_token = str_random(60);
        $user->recover_pass_token = $recover_token;
        $user->save();

        if ($this->sendResetEmail($request->email, $recover_token)) {
            return response()->json(array(
                'success' => true
            ), 200);
        } else {
            return response()->json(array(
                'success' => false,
                'errors' => array('email' => array('A Network Error occurred. Please try again.'))
            ), 400);
        }
    }

    private function sendResetEmail($email, $token)
    { //Retrieve the user from the database
        $user = User::where('email', $email)->select('first_name', 'email')->first();
        //Generate, the password reset link. The token generated is embedded in the link
        $link = config('base_url') . 'reset/' . $token . '?email=' . urlencode($user->email);

        try {
            //Here send the link with CURL with an external email API         
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    public function resetPassword(Request $request)
    {
        //Validate email
        $validator = Validator::make($request->all(), [
            'email' => 'required|exists:member_user,email'
        ]);

        //check if input is valid before moving on
        if ($validator->fails()) {
            return redirect()->back()->withErrors(['email' => 'Invalid e-mail address.']);
        }

        //Validate password
        $validator = Validator::make($request->all(), [
            'password' => 'required|confirmed'
        ]);

        //check if input is valid before moving on
        if ($validator->fails()) {
            return redirect()->back()->withErrors(['password' => 'Passwords do not match.', 'password_confirmation' => 'Passwords do not match.']);
        }

        $password = $request->password; // Validate the token
        $user = User::where('recover_pass_token', '=', $request->token)->first(); //Check if the user exists
        if ($user == null) {
            return redirect()->back()->withErrors(['email' => 'Invalid recovery link.']); // Redirect the user back to the password reset request form if the token is invalid
        }

        // Redirect the user back to the password reset request form if the email is invalid
        if ($user->email != $request->email) {
            return redirect()->back()->withErrors(['email' => 'Email not correct.']);
        }

        // Redirect the user back if the email is invalid
        $user->password = Hash::make($password);
        $user->update(); //or $user->save();

        //login the user immediately they change password successfully
        Auth::login($user);

        //Delete the token
        $user->recover_pass_token = null;
        $user->update();

        return redirect('/');
    }

    public function reset(Request $request)
    {

        if (Auth::check()) {
            return redirect('/');
        }
        $email = urldecode(Input::get('email'));

        return view('auth.passwords.reset', ['email' => $email, 'token' => $request->token]);
    }

    public function verifyEmail(Request $request)
    {
        return view('auth.verify');
    }
}
