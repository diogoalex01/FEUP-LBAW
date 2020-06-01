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
            if ($data['private'] != 'on') {
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

    public function homeTab(Request $request)
    {
        // if (!Auth::check()) return redirect('/login');
        //$this->authorize('list', Card::class);
        if (Auth::check()) {
            $user = Auth::user();
        } else {
            $user = null;
        }

        $posts = Post::orderBy('time_stamp', 'desc')->get()->take(20);
        // $posts = DB::table('post')
        //     ->join('member_user', 'member_user.id', '=', 'post.id_author')
        //     ->whereNotExists(function ($query) use ($user) {
        //         $query->select('*')
        //             ->from('block_user')
        //             ->where('blocked_user', '=', 'post.id_author')
        //             ->where('blocker_user', '!=', $query->id);
        //     })
        //     ->whereNotExists('select * from block_user where block_user.blocked_user = post.id_author')
        //     ->orderBy('time_stamp', 'desc')->get();

        $htmlView = [];

        foreach ($posts as $post) {
            array_push($htmlView, view('partials.homePost',  ['post' => $post, 'user' => $user])->render());
        }

        return response([
            'success' => true,
            'html'    => $htmlView
        ]);
    }

    public function popularTab(Request $request)
    {
        // if (!Auth::check()) return redirect('/login');
        //$this->authorize('list', Card::class);
        if (Auth::check()) {
            $user = Auth::user();
        } else {
            $user = null;
        }

        $posts = Post::orderBy('upvotes', 'desc')->get()->take(20);
        $htmlView = [];

        foreach ($posts as $post) {
            array_push($htmlView, view('partials.homePost',  ['post' => $post, 'user' => $user])->render());
        }

        return response([
            'success' => true,
            'html'    => $htmlView
        ]);
    }

    public function recentTab(Request $request)
    {
        // if (!Auth::check()) return redirect('/login');
        //$this->authorize('list', Card::class);
        if (Auth::check()) {
            $user = Auth::user();
        } else {
            $user = null;
        }

        $posts = Post::orderBy('time_stamp', 'desc')->get()->take(20);
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
        if (Auth::check()) {
            $user = Auth::user();
        } else {
            $user = null;
        }

        if ($request['type'] == 'home') {
            $posts = Post::orderBy('time_stamp', 'desc')->skip($request['num_posts'])->take(5)->get();
        } else if ($request['type'] == 'popular') {
            $posts = Post::orderBy('upvotes', 'desc')->skip($request['num_posts'])->take(5)->get();
        } else if ($request['type'] == 'recent') {
            $posts = Post::orderBy('time_stamp', 'desc')->skip($request['num_posts'])->take(5)->get();
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
    public function update($post_id, Request $request)
    {
        error_log("\n1\n");
        //TODO: add policy
        if (Auth::check()) {
            $user = Auth::user();
        } else {
            $user = null;
        }


        if ($user == null) {
            error_log("\n2\n");
            return response([
                'success' => false
            ]);
        }
        $data = $request->validate([
            'new_content' => 'required',
        ]);

        $post = Post::find($post_id);

        // $this->authorize('update', 2,$post);

        if ($post != null) {
            error_log("\n3\n");
            $post->content = $data['new_content'];
            $post->save();
            return response([
                'success' => true,
                "post_id" => $post->id,
                "new_content" => $post->content
            ]);
        } else {
            error_log("\n4\n");
            return response([
                'success' => false
            ]);
        }
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function destroy($post_id)
    {
        //TODO: add policy
        if (Auth::check()) {
            $user = Auth::user();
        } else {
            $user = null;
        }

        if ($user == null)
            return redirect('/');

        // $data = $request->validate([
        //     'post_id' => 'required',
        // ]);

        $post = Post::find($post_id);

        if ($post->delete()) {
            return response([
                'success' => true
            ]);
        } else {
            return response([
                'success' => false
            ]);
        }
    }

    public function vote($post_id, Request $request)
    {
        // post_id: targetId, vote_type: 'up'

        if (Auth::check()) {
            $user = Auth::user();
        } else {
            $user = null;
        }

        if ($user == null)
            return redirect('/');

        $data = $request->validate([
            'vote_type' => 'required',
        ]);

        $post = Post::find($post_id);

        // if (strcmp("up", $data['vote_type']) == 0) {
        //     $post->upvotes = $post->upvotes + 1;
        // } else if (strcmp("down", $data['vote_type']) == 0) {
        //     $post->downvotes = $post->downvotes + 1;
        // }1, ['products_amount' => 100, 'price' => 49.99]

        $post->votedUsers()->attach($user->id, ['vote_type' => $data['vote_type']]);

        $post->save();

        return response([
            'success' => true,
        ]);
    }

    public function vote_edit($post_id, Request $request)
    {

        if (Auth::check()) {
            $user = Auth::user();
        } else {
            $user = null;
        }

        if ($user == null)
            return redirect('/');

        $data = $request->validate([
            'vote_type' => 'required',
        ]);

        $post = Post::find($post_id);


        $post->votedUsers()->updateExistingPivot($user->id, ['vote_type' => $data['vote_type']]);

        return response([
            'success' => true,
        ]);
    }


    public function vote_delete($post_id, Request $request)
    {
        if (Auth::check()) {
            $user = Auth::user();
        } else {
            $user = null;
        }

        if ($user == null)
            return redirect('/');

        $data = $request->validate([
            'vote_type' => 'required',
        ]);

        $post = Post::find($post_id);

        $post->votedUsers()->detach($user->id);

        return response([
            'success' => true,
        ]);
    }
}
