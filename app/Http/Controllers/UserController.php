<?php

namespace App\Http\Controllers;

use App\User;
use App\Post;
use App\Comment;
use App\Community;
use App\Notification;
use App\Request as RequestModel;
use App\FollowRequest;
use App\Admin;
use App\Report;
use App\UserReport;

use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
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
    public function show($user_id)
    {
        if (Auth::guard('admin')->check()) {
            $this_user = Auth::guard('admin')->user();
        } else {
            $this_user = Auth::user();
        }
        $member_users = User::all();
        $member_user = $member_users->find($user_id);

        $follows = false;
        $blocking = false;
        $blocked = false;
        $request_status = null;


        if ($member_user != NULL) {
            $postN = Post::where('id_author', '=', $user_id)->count();
            $age = \Carbon\Carbon::parse($member_user->birthday)->age;

            $posts = Post::where('id_author', '=', $user_id)->orderBy('time_stamp', 'desc')->get();
            $comments = Comment::where('id_author', '=', $user_id)->orderBy('time_stamp', 'desc')->get();

            $activities = $posts->merge($comments);
            $activities = $activities->sortByDesc('time_stamp');
            
            $communities = Community::where('id_owner', '=', $user_id)->orderBy('name', 'asc')->get();


            if ($this_user !== null) {
                $requestCondition = ['id_receiver' => $user_id, "id_sender" => $this_user->id];
                $request = RequestModel::where($requestCondition)->get();
                if (sizeof($request) > 0) {
                    //dd($request);
                    for ($i = 0; $i < sizeof($request); $i++) {
                        // dd($request);
                        $follow_request = DB::table('follow_request')->where('id', '=', $request[$i]->id)->first();
                        if ($follow_request !== null) {
                            // dd($request);
                            $request_status = $request[$i]->status;
                            break;
                        }
                    }
                }

                $followCondition = ['id_follower' => $this_user->id, 'id_followed' => $user_id];
                $follow_row = DB::table('follow_user')->where($followCondition)->get();
                if (sizeof($follow_row) > 0) {
                    $follows = true;
                } else {
                    $follows = false;
                }

                $blockingCondition = ['blocked_user' => $user_id, 'blocker_user' => $this_user->id];
                $block_row = DB::table('block_user')->where($blockingCondition)->first();
                if ($block_row !== null) {
                    $blocking = true;
                } else {
                    $blocking = false;
                }

                $blockedCondition = ['blocked_user' => $this_user->id, 'blocker_user' => $user_id];
                $block_row = DB::table('block_user')->where($blockedCondition)->first();
                if ($block_row !== null) {
                    $blocked = true;
                } else {
                    $blocked = false;
                }
            }
            //dd($blocking, $blocked);
            // $follow_request = DB::table('follow_user')->where('id_followed', '=', $user_id)->get() !== null;

            //$comments = DB::table('comment')->where('id_post', '=', $id)->orderBy('time_stamp', 'desc')->get();
            return view('pages.myProfile', ['other_user' => $member_user, 'age' => $age, 'nPosts' => $postN, 'activities' => $activities, 'communities' => $communities, 'user' => $this_user, 'follow_status' => $request_status, 'follows' => $follows, 'isBlocked' => $blocked, 'isBlocking' => $blocking]);
        }
        abort(404);
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
        $user = Auth::user();

        // Fetch content and determine boundary
        $raw_data = file_get_contents('php://input');
        $boundary = substr($raw_data, 0, strpos($raw_data, "\r\n"));

        /** Get Form Inputs (with image) for multipart/form-data PUT requests **/
        // Fetch each part
        $parts = array_slice(explode($boundary, $raw_data), 1);
        $data = array();

        foreach ($parts as $part) {
            // If this is the last part, break
            if ($part == "--\r\n") break;

            // Separate content from headers
            $part = ltrim($part, "\r\n");
            list($raw_headers, $body) = explode("\r\n\r\n", $part, 2);

            // Parse the headers list
            $raw_headers = explode("\r\n", $raw_headers);
            $headers = array();
            foreach ($raw_headers as $header) {
                list($name, $value) = explode(':', $header);
                $headers[strtolower($name)] = ltrim($value, ' ');
            }

            // Parse the Content-Disposition to get the field name, etc.
            if (isset($headers['content-disposition'])) {
                $filename = null;
                preg_match(
                    '/^(.+); *name="([^"]+)"(; *filename="([^"]+)")?/',
                    $headers['content-disposition'],
                    $matches
                );
                list(, $type, $name) = $matches;
                isset($matches[4]) and $filename = $matches[4];

                // handle your fields here
                switch ($name) {
                        // this is a file upload
                    case 'image':
                        if ($filename != null) {
                            if (!file_exists('user/')) {
                                mkdir('user/', 0777, true);
                            }
                            $fileExtension = pathinfo($filename, PATHINFO_EXTENSION);
                            $filePath = 'user/' . $user->id . "." . $fileExtension;

                            $files = glob('user/*'); // get all files

                            foreach ($files as $file) { // iterate files
                                $fileBaseName = pathinfo($file, PATHINFO_BASENAME);
                                if ($fileBaseName == $user->id)
                                    unlink($file); // delete file
                            }

                            file_put_contents($filePath, $body);
                            $user->photo = $filePath;
                        }
                        break;

                        // default for all other files is to populate $data
                    default:
                        $data[$name] = substr($body, 0, strlen($body) - 2);
                        break;
                }
            }
        }

        $dataValidated = Validator::make($data, [
            'first_name' => 'string|max:255',
            'last_name' => 'string|max:255',
            'username' => 'string|max:255',
            'birthday' => 'date|before_or_equal:' . now()->subYears(12),
            'email' => 'string|email|max:255',
            'gender' => 'in:male,female,other',
            'password' => 'required|string|min:6|confirmed',
            'image' => 'mimes:jpeg,jpg,png,gif',
            'private' => 'in:true,false'
        ]);

        if ($dataValidated->fails()) {
            return response()->json(array(
                'success' => false,
                'errors' => $dataValidated->errors()
            ), 300);
        }

        if (strlen($user->password) > 32) {
            if (Hash::check($data['password_confirmation'], Hash::make($data['password'])) === false) {
                return response()->json(array(
                    'success' => false,
                    'errors' => array('password_confirmation' => array('Passwords do not match.'))
                ), 300);
            } else if (!Hash::check($data['password'], $user->password)) {
                return response()->json(array(
                    'success' => false,
                    'errors' => array('password' => array('The password is incorrect.'))
                ), 300);
            }
        }

        if (User::where('email', $data['email'])->first() !== null && $data['email'] != $user->email) {
            return response()->json(array(
                'success' => false,
                'errors' => array('email' => array('The email already exists.'))
            ), 300);
        }

        $user->email = $data['email'];
        $user->first_name = $data['first_name'];
        $user->last_name = $data['last_name'];
        $user->username = $data['username'];
        $user->birthday = $data['birthday'];
        $user->gender = $data['gender'];
        $user->private = $data['private'];

        $user->save();

        return response()->json(array(
            'success' => true,
            'imgPath' => $user->photo
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
        $user = Auth::user();

        if ($delete_content === 'true') {
            DB::transaction(function () use ($user) {
                DB::table('post')->where('id_author', '=', $user->id)->delete();
                DB::table('comment')->where('id_author', '=', $user->id)->delete();
            });
        }

        // Delete user profile picture
        if (file_exists($user->photo)) {
            unlink($user->photo); // delete file
        }

        Auth::logout();

        if ($user->delete()) {
            redirect('/');
        } else {
            Auth::login($user);
            redirect('/settings');
        }
    }

    public function follow($user_id)
    {
        $user = Auth::user();
        // $user->following()->attach($user_id);
        // $user->save();

        DB::transaction(function () use ($user_id, $user) {
            // create a record in the follow request and request table
            $request = new RequestModel();
            $request->id_receiver = $user_id;
            $request->id_sender = $user->id;
            $request->save();

            $follow_req = new FollowRequest();
            $follow_req->id = $request->id;
            $follow_req->save();

            // link them together
            $follow_req->request()->save($request);
        });
    }


    public function unfollow($user_id)
    {
        $user = Auth::user();

        DB::transaction(function () use ($user_id, $user) {
            $condition = ['id_receiver' => $user_id, "id_sender" => $user->id];
            $request = DB::table('request')
                ->join('follow_request', 'follow_request.id', '=', 'request.id')
                ->where($condition)
                ->first();
            if ($request != null) {
                DB::delete('delete from notification where id_request = ?', [$request->id]);
                DB::delete('delete from follow_request where id = ?', [$request->id]);
                $request = Request::find($request->id);
                $request->delete();
            }
            DB::delete('delete from follow_user where id_followed = ? and id_follower = ?', [$user_id, $user->id]);
        });
    }

    public function block($user_id)
    {
        $user = Auth::user();
        DB::transaction(function () use ($user_id, $user) {
            DB::insert('insert into block_user (blocked_user, blocker_user) values (?, ?)', [$user_id, $user->id]);
            DB::delete('delete from request where id_receiver = ? and id_sender = ?', [$user->id, $user_id]);
            DB::delete('delete from request where id_receiver = ? and id_sender = ?', [$user_id, $user->id]);
        });
    }

    public function unblock($user_id)
    {
        $user = Auth::user();
        DB::delete('delete from block_user where blocked_user = ? and blocker_user = ?', [$user_id, $user->id]);
    }

    /**
     * Report user
     *
     * @param  int  $user_id
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function report ($user_id, Request $request){
        $this->authorize('report', User::class);
        $user = Auth::user();

        $data = $request->validate([
            'reason' => 'required|string'
        ]);

        $admins = Admin::all()->pluck('id')->toArray();
        $admin = $admins[array_rand($admins)];
        $admin = 4;

        DB::transaction(function ()  use ($user, $admin, $user_id, $data) {

            // Create a record in the user report and report table
            $report = new Report();
            $report->reason = $data['reason'];
            $report->id_admin = $admin;
            $report->id_user = $user->id;
            $report->save();

            $user_report = new UserReport();
            $user_report->id_report = $report->id;
            $user_report->id_user = $user_id;
            $user_report->save();

            // Link them together
            $user_report->report()->save($report);
        });
        
        //TODO: mostrar mensagem de sucesso?
    }
}
