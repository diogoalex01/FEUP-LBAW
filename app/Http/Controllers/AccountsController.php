<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Sendpulse\RestApi\ApiClient;
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
    {
        //Retrieve the user from the database
        $user = User::where('email', $email)->select('first_name', 'last_name', 'email')->first();
        //Generate, the password reset link. The token generated is embedded in the link
        $link = env('APP_URL') . '/reset/' . $token . '?email=' . urlencode($user->email);

        error_log("\n\n");
        error_log($user->email);
        error_log($link);
     
        try {

            define('API_USER_ID', '362ccba54c8e19e839a7a823d9fac4de');
            define('API_SECRET', 'c5e009beb89e4a70fd7c64fbe822caa6');

            $SPApiClient = new ApiClient(API_USER_ID, API_SECRET);

            // Send mail using SMTP
            $email = array(
                'html'    => "<head> <title>Recovery Email</title> <meta content=\"text/html; charset=utf-8\" http-equiv=\"Content-Type\"> <meta content=\"width=device-width\" name=\"viewport\"> <style type=\"text/css\"> @font-face { font-family: &#x27; Postmates Std&#x27; ; font-weight: 600; font-style: normal; src: local(&#x27; Postmates Std Bold&#x27; ), url(https://s3-us-west-1.amazonaws.com/buyer-static.postmates.com/assets/email/postmates-std-bold.woff) format(&#x27; woff&#x27; ); } @font-face { font-family: &#x27; Postmates Std&#x27; ; font-weight: 500; font-style: normal; src: local(&#x27; Postmates Std Medium&#x27; ), url(https://s3-us-west-1.amazonaws.com/buyer-static.postmates.com/assets/email/postmates-std-medium.woff) format(&#x27; woff&#x27; ); } @font-face { font-family: &#x27; Postmates Std&#x27; ; font-weight: 400; font-style: normal; src: local(&#x27; Postmates Std Regular&#x27; ), url(https://s3-us-west-1.amazonaws.com/buyer-static.postmates.com/assets/email/postmates-std-regular.woff) format(&#x27; woff&#x27; ); } </style> <style media=\"screen and (max-width: 680px)\"> @media screen and (max-width: 680px) { .page-center { padding-left: 0 !important; padding-right: 0 !important; } .footer-center { padding-left: 20px !important; padding-right: 20px !important; } } </style></head><body style=\"background-color: #f4f4f5;\"> <table align=\"center\" cellpadding=\"0\" cellspacing=\"0\" style=\"width: 100%; height: 100%; background-color: #f4f4f5; text-align: center;\"> <tbody> <tr> <td style=\"text-align: center;\"> <table align=\"center\" cellpadding=\"0\" cellspacing=\"0\" id=\"body\" style=\"background-color: #ffe8dd; width: 100%; max-width: 680px; height: 100%; padding-left: 60px; padding-right: 60px;\"> <tbody> <tr> <td> <table align=\"center\" cellpadding=\"0\" cellspacing=\"0\" class=\"page-center\" style=\"text-align: left; padding-bottom: 88px; width: 100%; padding-left: 100; padding-right: 100;\"> <tbody> <tr> <td style=\"padding-top: 24px;\"> <img src=\"https://i.imgur.com/Fp5ew7G.png\" style=\"width: 66px;\"> </td> </tr> <tr> <td colspan=\"2\" style=\"padding-top: 72px; -ms-text-size-adjust: 100%; -webkit-font-smoothing: antialiased; -webkit-text-size-adjust: 100%; color: #242424; font-family: 'Postmates Std', 'Helvetica', -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Roboto', 'Oxygen', 'Ubuntu', 'Cantarell', 'Fira Sans', 'Droid Sans', 'Helvetica Neue', sans-serif; font-size: 48px; font-smoothing: always; font-style: normal; font-weight: 600; letter-spacing: -2.6px; line-height: 52px; mso-line-height-rule: exactly; text-decoration: none;\"> Reset your password</td> </tr> <tr> <td style=\"padding-top: 48px; padding-bottom: 48px;\"> <table cellpadding=\"0\" cellspacing=\"0\" style=\"width: 100%\"> <tbody> <tr> <td style=\"width: 100%; height: 1px; max-height: 1px; background-color: #d9dbe0; opacity: 0.81\"> </td> </tr> </tbody> </table> </td> </tr> <tr> <td style=\"-ms-text-size-adjust: 100%; -ms-text-size-adjust: 100%; -webkit-font-smoothing: antialiased; -webkit-text-size-adjust: 100%; color: #6f7380; font-family: 'Postmates Std', 'Helvetica', -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Roboto', 'Oxygen', 'Ubuntu', 'Cantarell', 'Fira Sans', 'Droid Sans', 'Helvetica Neue', sans-serif; font-size: 16px; font-smoothing: always; font-style: normal; font-weight: 400; letter-spacing: -0.18px; line-height: 24px; mso-line-height-rule: exactly; text-decoration: none; vertical-align: top; width: 100%;\"> You're receiving this e-mail because you requested a password reset for your <b>PearToPear</b> account. </td> </tr> <tr> <td style=\"padding-top: 24px; -ms-text-size-adjust: 100%; -ms-text-size-adjust: 100%; -webkit-font-smoothing: antialiased; -webkit-text-size-adjust: 100%; color: #6f7380; font-family: 'Postmates Std', 'Helvetica', -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Roboto', 'Oxygen', 'Ubuntu', 'Cantarell', 'Fira Sans', 'Droid Sans', 'Helvetica Neue', sans-serif; font-size: 16px; font-smoothing: always; font-style: normal; font-weight: 400; letter-spacing: -0.18px; line-height: 24px; mso-line-height-rule: exactly; text-decoration: none; vertical-align: top; width: 100%;\"> Please tap the button below to choose a new password. </td> </tr> <tr> <td> <a href=\"$link\" style=\"margin-top: 36px; -ms-text-size-adjust: 100%; -ms-text-size-adjust: 100%; -webkit-font-smoothing: antialiased; -webkit-text-size-adjust: 100%; color: #ffffff; font-family: 'Postmates Std', 'Helvetica', -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Roboto', 'Oxygen', 'Ubuntu', 'Cantarell', 'Fira Sans', 'Droid Sans', 'Helvetica Neue', sans-serif; font-size: 12px; font-smoothing: always; font-style: normal; font-weight: 600; letter-spacing: 0.7px; line-height: 48px; mso-line-height-rule: exactly; text-decoration: none; vertical-align: top; width: 220px; background-color: #812b2e; border-radius: 5px; display: block; text-align: center; text-transform: uppercase\" target=\"_blank\"> Reset Password </a> </td> </tr> </tbody> </table> </td> </tr> </tbody> </table> <table align=\"center\" cellpadding=\"0\" cellspacing=\"0\" id=\"footer\" style=\"background-color: #4c191b; width: 100%; max-width: 680px; height: 100%; padding-left: 40px; padding-right: 40px;\"> <tbody> <tr> <td> <table align=\"center\" cellpadding=\"0\" cellspacing=\"0\" class=\"footer-center\" style=\"text-align: left; width: 100%; padding-left: 40px; padding-right: 40px;\"> <tbody> <tr> <td colspan=\"2\" style=\"padding-top: 32px; padding-bottom: 24px; width: 100%;\"> <a href=\"http://lbaw2076.lbaw-prod.fe.up.pt/\"> <img src=\" https://i.imgur.com/qcVZZ7G.png\" style=\"width: 66px;\"> </a> </td> </tr> <tr> <td colspan=\"2\" style=\"padding-top: 24px; padding-bottom: 48px;\"> <table cellpadding=\"0\" cellspacing=\"0\" style=\"width: 100%\"> <tbody> <tr> <td style=\"width: 100%; height: 1px; max-height: 1px; background-color: #EAECF2; opacity: 0.19\"> </td> </tr> </tbody> </table> </td> </tr> <tr> <td style=\"-ms-text-size-adjust: 100%; -ms-text-size-adjust: 100%; -webkit-font-smoothing: antialiased; -webkit-text-size-adjust: 100%; color: ffe8dd; font-family: 'Postmates Std', 'Helvetica', -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Roboto', 'Oxygen', 'Ubuntu', 'Cantarell', 'Fira Sans', 'Droid Sans', 'Helvetica Neue', sans-serif; font-size: 15px; font-smoothing: always; font-style: normal; font-weight: 400; letter-spacing: 0; line-height: 24px; mso-line-height-rule: exactly; text-decoration: none; vertical-align: top; width: 100%;\"> If you have any questions or concerns, we're here to help. For more information visit our <a href=\"http://lbaw2076.lbaw-prod.fe.up.pt/\" style=\"font-weight: 500; color: #ffffff\" target=\"_blank\">Website</a>. <p style=\"font-weight: 700; color: #ffffff\" target=\"_blank\">LBAW © 2020 Copyright</p> </td> </tr> <tr> <td style=\"height: 72px;\"></td> </tr> </tbody> </table> </td> </tr> </tbody> </table> </td> </tr> </tbody> </table></body>",
                'text'    => 'Reset link: ' . $link,
                'subject' => '[PearToPear] Please reset your password',
                'from'    => array(
                    'name'  => 'PearToPear',
                    'email' => 'up201706892@fe.up.pt'
                ),
                'to'      => array(
                    array(
                        'name'  => $user->first_name . " " . $user->last_name,
                        'email' => $user->email
                    )
                )
            );
            var_dump($SPApiClient->smtpSendMail($email));

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
