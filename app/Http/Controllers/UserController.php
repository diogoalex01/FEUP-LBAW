<?php

namespace App\Http\Controllers;


use App\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;



class UserController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // 
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\MemberUser  $memberUser
     * @return \Illuminate\Http\Response
     */
    public function show(User $memberUser)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\MemberUser  $memberUser
     * @return \Illuminate\Http\Response
     */
    public function edit()
    {
        $user = Auth::user();
        $this->authorize('update',User::class);
        return view('pages.settings', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\MemberUser  $memberUser
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $user = Auth::user();
        error_log($request->image);
        $data = Validator::make($request->all(), [
            'first_name' => 'string|max:255',
            'last_name' => 'string|max:255',
            'username' => 'string|max:255',
            'birthday' => 'date|before_or_equal:' . now()->subYears(12),
            'email' => 'string|email|max:255',
            'gender' => 'in:male,female,other',
            'password' => 'required|string|min:6|confirmed',
            //'image' => 'nullable|mimes:jpeg,jpg,png,gif',
            'private' => 'in:true,false'
        ]);

        if ($data->fails()) {
            return response()->json(array(
                'success' => false,
                'errors' => $data->errors()
            ), 300);
        }

        if (strlen($user->password) > 32) {
            if (Hash::check($request->password_confirmation, Hash::make($request->password)) === false) {
                return response()->json(array(
                    'success' => false,
                    'errors' => array('password_confirmation' => array('Passwords do not match.'))
                ), 300);
            } else if (!Hash::check($request->password, $user->password)) {
                return response()->json(array(
                    'success' => false,
                    'errors' => array('password' => array('The password is incorrect.'))
                ), 300);
            }
        }

        if (User::where('email', $request->email)->first() !== null && $request->email != $user->email) {
            return response()->json(array(
                'success' => false,
                'errors' => array('email' => array('The email already exists.'))
            ), 300);
        }

        $user->email = $request->email;
        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->username = $request->username;
        $user->birthday = $request->birthday;
        $user->gender = $request->gender;
        $user->private = $request->private;

        if ($request->hasFile('image')) {

            $nameWithExtension = $request->image->getClientOriginalExtension();
            $path = $request->image->storeAs(
                '/user',
                $user->id . "." . $nameWithExtension,
                'public'
            );
            $user->photo = $path;
        } else {
        }
        $user->save();

        return response()->json(array(
            'success' => true
        ), 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\MemberUser  $memberUser
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $delete_content = $request['delete_content'];

        //$this->authorize('delete', [$user_id]);
        $user_id = Auth::user()->id;
        $user = User::find($user_id);

        if ($delete_content === 'true') {
            DB::transaction(function () use ($user_id) {
                DB::table('post')->where('id_author', '=', $user_id)->delete();
                DB::table('comment')->where('id_author', '=', $user_id)->delete();
            });
        }

        // Delete user profile picture
        //Storage::delete($user->image);

        Auth::logout();

        if ($user->delete()) {
            redirect('/');
        } else {
            Auth::login($user);
            redirect('/settings');
        }
    }
}
