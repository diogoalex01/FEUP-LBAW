<?php

namespace App\Http\Controllers;

use App\Post;
use App\User;
use App\Community;
use App\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
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
        //if (!Auth::check()) return redirect('/login');
        if (Auth::check()) {
            $user = Auth::user();
        } else {
            return redirect()->back()->with('showModal', "welcome");
        }

        $this->authorize('create', Post::class);
        return view('pages.newPost', ['user' => $user]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //$this->authorize('create', $post);

        $data = $request->validate([
            'community' => 'required',
            'title' => 'required',
            'private' => 'sometimes|accepted',
            'post_content' => 'required',
            'image' => 'nullable|mimes:jpeg,jpg,png,gif',
        ]);

        /* Check and create community if needed */
        $communities = DB::table('community')->pluck('name')->toArray();
        $community_name = $data['community'];

        $lowerCommunities = array_map('strtolower', $communities);
        $lowerCommunityName = strtolower($community_name);

        if (in_array($lowerCommunityName, $lowerCommunities)) {
            $community_id = Community::where('name', 'ilike', '%' . $community_name . '%')->get()->first()->id;
        } else {
            if (!in_array('private', $data)) {
                $data['private'] = 'false';
            } else {
                $data['private'] = 'true';
            }

            $community = Community::create([
                'name' => $community_name,
                'private' => $data['private'],
            ]);
            
            $community->image = "img/default_community.jpg";
            $community->id_owner = Auth::user()->id;
            $community->save();
            $community_id = $community->id;
        }

        /* Create Post */
        $post = Post::create([
            'id_community' => $community_id,
            'content' => $data['post_content'],
            'title' => $data['title'],
        ]);

        if ($request->hasFile('image')) {
            $nameWithExtension = $request->file('image')->getClientOriginalExtension();
            $path = $request->file('image')->storeAs(
                '/post',
                $post->id . "." . $nameWithExtension,
                'public'
            );
            $post->image = $path;
        }

        $post->id_author = Auth::user()->id;
        $post->save();

        return redirect('/');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $post = Post::find($id);

        if (Auth::check()) {
            $user = Auth::user();
        } else {
            $user = null;
        }

        $just_parent_comments = ['id_post' => $id, 'id_parent' => null];
        $just_replies = ['id_post' => $id, ['id_parent', '<>', null]];
        $comments = Comment::where($just_parent_comments)->orderBy('time_stamp', 'desc')->get();
        $replies = Comment::where($just_replies)->orderBy('time_stamp', 'desc')->get();

        return view('pages.post', ['post' => $post, 'user' => $user, 'comments' => $comments, 'replies' => $replies]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function list()
    {
        // if (!Auth::check()) return redirect('/login');
        //$this->authorize('list', Card::class);
        if (Auth::check()) {
            $user = Auth::user();
        } else {
            $user = null;
        }

        $posts = Post::orderBy('time_stamp', 'desc')->get()->take(20);

        return view('pages.home', ['posts' => $posts, 'user' => $user]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function refresh()
    {
        if (Auth::check()) {
            $user = Auth::user();
        } else {
            $user = null;
        }

        $posts = Post::orderBy('time_stamp', 'desc')->skip($data['num_posts'])->take(5)->get();
        return response()->json(array(
            'success' => true,
            'post' => $posts
        ), 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function edit(Post $post)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Post $post)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function destroy(Post $post)
    {
        //
    }

    public function vote(Request $request){
        // post_id: targetId, vote_type: 'up'

        if (Auth::check()) {
            $user = Auth::user();
        } else {
            $user = null;
        }

        if($user == null)
        return redirect('/');

        $data = $request->validate([
            'post_id' => 'required',
            'vote_type' => 'required',
        ]);

        $post = Post::where('id', '=', $data['post_id'])->get();
        //TODO: alterar pivot table

        if (strcmp("up", $data[vote_type]) == 0){
            $post->upvotes = $post->upvotes + 1;
        } else if (strcmp("down", $data[vote_type]) == 0){
            $post->downvotes = $post->downvotes + 1;
        }
        
    }
}