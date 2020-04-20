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
        $this->authorize('update', User::class);
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
        error_log("enteredupdate");
        $user = Auth::user();
        error_log("\n\here\n\n");
        $data = Validator::make($request->all(), [
            'first_name' => 'string|max:255',
            'last_name' => 'string|max:255',
            'username' => 'string|max:255',
            'birthday' => 'date|before_or_equal:' . now()->subYears(12),
            'email' => 'string|email|max:255',
            'gender' => 'in:male,female,other',
            'password' => 'string|min:6|confirmed',
            //'image' => ''
            //'private' => 'boolean'
        ]);

        if ($data->fails()) {
            return response()->json(array(
                'success' => false,
                'errors' => $data->errors()
            ), 300);
        }
        // $data = $request->validate([
        //     'first_name' => 'string|max:255',
        //     'last_name' => 'string|max:255',
        //     'username' => 'string|max:255',
        //     'birthday' => 'date|before_or_equal:' . now()->subYears(12),
        //     'email' => 'string|email|max:255',
        //     'gender' => 'in:male,female,other',
        //     'password' => 'string|min:6|confirmed',
        //     'image' => '',
        //     // 'private' => 'boolean'
        // ]);

        error_log("\n\Validated\n\n");
        error_log($user->password);

        // if (Hash::check(strval($data['password']), $user->password)) {
        //     if (User::where('email', strval($data['email']))->first() !== null && strval($data['email']) != $user->email) {
        //         return;
        //     }

        //     $user->email = $data['email'];
        //     $user->first_name = $data['first_name'];
        //     $user->last_name = $data['last_name'];
        //     $user->username = $data['username'];
        //     $user->birthday = $data['birthday'];
        //     $user->gender = $data['gender'];
        //     // $user->private = $data['private'];

        //     // error_log('imagem');

        //     // $user->photo = $data['image'];
        //     // $request->image->move(public_path('images'), $user->photo);

        //     error_log("user");
        //     $user->save();
        //     return;
        // }

        return response()->json(array(
            'success' => true
        ), 200);;
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
                error_log($user_id);
                DB::table('post')->where('id_author', '=', $user_id)->delete();
                DB::table('comment')->where('id_author', '=', $user_id)->delete();
                // DB::table('community_member')->where('id_user', '=', $user_id)->delete();
                //verificar follows, blocks e requests e outros assim
            });
        }

        Auth::logout();

        if ($user->delete()) {
            redirect('/');
        } else {
            Auth::login($user);
            redirect('/settings');
        }
    }
}
