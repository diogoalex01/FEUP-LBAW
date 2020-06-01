<?php

namespace App\Http\Controllers;

use App\Community;
use App\Post;
use App\User;
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
        $member = false;

        if (Auth::check()) {
            $user = Auth::user();
        } else {
            $user = null;
        }

        $posts = Post::where('id_community', '=', $community_id)->orderBy('time_stamp', 'desc')->take(20)->get();

        if ($user !== null) {
            // dd($community->members());
            if ($community->members()->where('id_user', '=', $user->id)->first() != null) {
                $member = true;
            }
        }

        // $comments = DB::table('comment')->where('id_post', '=', $id)->orderBy('time_stamp', 'desc')->get();
        return view('pages.community', ['community' => $community, 'posts' => $posts, 'user' => $user, 'isMember' => $member]);
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

        $posts = Post::where('id_community', '=', $request['community_id'])->orderBy('time_stamp', 'desc')->skip($request['num_posts'])->take(5)->get();
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
    public function destroy(Community $community)
    {
        //
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
        if ($community->private) {
            DB::transaction(function () use ($community, $user) {
                DB::insert('insert into request (id_receiver, id_sender) values (?, ?)', [$community->id_owner, $user->id]);
                $request = DB::table('request')->latest('time_stamp')->first();
                DB::insert('insert into join_community_request (id, id_community) values (?,?)', [$request->id, $community->id]);
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
        $community = Community::find($community_id);
        $community->members()->detach($user->id, []);
    }
}
