<?php

namespace App\Http\Controllers;

use App\Community;
use App\Post;
use App\User;
use App\JoinCommunityRequest;
use App\Admin;
use App\Report;
use App\CommunityReport;
use App\Request as RequestModel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CommunityController extends Controller
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
     * @param  \App\Community  $community
     * @return \Illuminate\Http\Response
     */
    public function show($community_id)
    {
        $communities = Community::all();
        $community = $communities->find($community_id);
        if ($community !== null) {

            $member = false;
            $request_status = null;
            $owner = false;

            if (Auth::check()) {
                $user = Auth::user();
            } else {
                $user = null;
            }

            $posts = Post::where('id_community', '=', $community_id)->orderBy('time_stamp', 'desc')->take(20)->get();

            if ($user !== null) {

                if (sizeof($community->members()->where('id_user', $user->id)->get()) > 0) {
                    $member = true;
                }

                if (!$member) {
                    $join_request = DB::table('request')
                        ->join('join_community_request', 'join_community_request.id', '=', 'request.id')
                        ->where('join_community_request.id_community', '=', $community_id)
                        ->where('request.id_sender', '=', $user->id)->first();
                    if ($join_request !== null) {
                        $request_status = "pending";
                    }
                }

                if ($community->id_owner == $user->id) {
                    $owner = true;
                }
            }
            // $comments = DB::table('comment')->where('id_post', '=', $id)->orderBy('time_stamp', 'desc')->get();
            return view('pages.community', ['community' => $community, 'posts' => $posts, 'user' => $user, 'isMember' => $member, 'request_status' => $request_status, 'owner' => $owner]);
        }
        abort(404);
    }

    public function popularTab(Request $request)
    {
        $communities = Community::all();
        $community = $communities->find($request['community_id']);

        if (Auth::check()) {
            $user = Auth::user();
        } else {
            $user = null;
        }

        $posts = Post::where('id_community', '=', $request['community_id'])->orderBy('upvotes', 'desc')->skip($request['num_posts'])->take(5)->get();

        $htmlView = [];

        foreach ($posts as $post) {
            array_push($htmlView, view('partials.homePost',  ['post' => $post, 'user' => $user])->render());
        }

        return response([
            'success' => true,
            'html'    => $htmlView
        ]);
    }

    public function homeTab(Request $request)
    {
        $communities = Community::all();
        $community = $communities->find($request['community_id']);

        if (Auth::check()) {
            $user = Auth::user();
        } else {
            $user = null;
        }

        $posts = Post::where('id_community', '=', $request['community_id'])->orderBy('time_stamp', 'desc')->skip($request['num_posts'])->take(5)->get();

        $htmlView = [];

        foreach ($posts as $post) {
            array_push($htmlView, view('partials.homePost',  ['post' => $post, 'user' => $user])->render());
        }

        return response([
            'success' => true,
            'html'    => $htmlView
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function refresh(Request $request)
    {
        $communities = Community::all();
        $community = $communities->find($request['community_id']);

        if (Auth::check()) {
            $user = Auth::user();
        } else {
            $user = null;
        }

        if ($request['type'] == 'popular') {
            $posts = Post::where('id_community', '=', $request['community_id'])->orderBy('upvotes', 'desc')->skip($request['num_posts'])->take(5)->get();
        } else if ($request['type'] == 'home') { // recents
            $posts = Post::where('id_community', '=', $request['community_id'])->orderBy('time_stamp', 'desc')->skip($request['num_posts'])->take(5)->get();
        }

        // return response()->json(array(
        //     'success' => true,
        //     'post' => $posts
        // ), 200);

        $htmlView = [];

        foreach ($posts as $post) {
            array_push($htmlView, view('partials.homePost',  ['post' => $post, 'user' => $user])->render());
        }

        return response([
            'success' => true,
            'html'    => $htmlView
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Community  $community
     * @return \Illuminate\Http\Response
     */
    public function edit(Community $community)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Community  $community
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Community $community)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Community  $community
     * @return \Illuminate\Http\Response
     */
    public function adminDestroy($community_id)
    {
        //todo is admin
        $reports = CommunityReport::where('id_community','=', $community_id)->get();
        //$this->authorize('view', Admin::class);
        foreach($reports as $report){
            $report->report->delete();
        }

        $Community = Community::find($community_id);

        if ($Community->delete()) {
            return response([
                'success' => true
            ]);
        } else {
            return response([
                'success' => false
            ]);
        }
    }

    public function get_all()
    {
        $communities = DB::table('community')->get();
        // $communities = DB::table('community')->pluck('name', 'image');
        return $communities;
    }

    public function find(Request $request)
    {
        // $data = $request->validate([
        //     'community_name' => 'required'
        //     //'image' => 'sometimes|image',
        // ]);
        // $community = Community::where('name', '=', $data['community_name'])->first();
        // //if ($community !== null) {
        //     //}
        // return $community;
    }

    public function join($community_id)
    {
        if (Auth::check()) {
            $user = Auth::user();
        } else {
            $user = null;
        }
        $community = Community::find($community_id);
        if ($community->private && $community->id_owner != $user->id) {
            DB::transaction(function () use ($community, $user) {

                // create a record in the join community request and request table
                $request = new RequestModel();
                $request->id_receiver = $community->id_owner;
                $request->id_sender = $user->id;
                $request->save();

                $join_community_req = new JoinCommunityRequest();
                $join_community_req->id = $request->id;
                $join_community_req->id_community = $community->id;
                $join_community_req->save();

                // link them together
                $join_community_req->request()->save($request);
            });
            return response(['status' => 'pending']);
        } else {
            $community->members()->attach($user->id, []);
            return response(['status' => 'accepted']);
        }
    }

    public function leave($community_id)
    {
        if (Auth::check()) {
            $user = Auth::user();
        } else {
            $user = null;
        }
        $join_request = DB::table('join_community_request')->where('id_community', '=', $community_id)->first();
        if ($join_request !== null) {
            $request = RequestModel::find($join_request->id);
            $request->delete();
        }
        $community = Community::find($community_id);
        $community->members()->detach($user->id, []);
    }

    /**
     * Report community
     *
     * @param  int  $comunity_id
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function report($community_id, Request $request)
    {
        $this->authorize('report', Community::class);
        $user = Auth::user();

        $data = $request->validate([
            'reason' => 'required|string'
        ]);

        $admins = Admin::all()->pluck('id')->toArray();
        $admin = $admins[array_rand($admins)];

        DB::transaction(function ()  use ($user, $admin, $community_id, $data) {
            // Create a record in the community report and report table
            $report = new Report();
            $report->reason = $data['reason'];
            $report->id_admin = $admin;
            $report->id_user = $user->id;
            $report->save();

            $community_report = new CommunityReport();
            $community_report->id_report = $report->id;
            $community_report->id_community = $community_id;
            $community_report->save();

            // Link them together
            $community_report->report()->save($report);
        });


        //TODO: mostrar mensagem de sucesso?
    }
}
