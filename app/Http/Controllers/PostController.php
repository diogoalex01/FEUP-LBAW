<?php

namespace App\Http\Controllers;

use App\Post;
use App\User;
use App\Community;
use App\Comment;
use App\Admin;
use App\Report;
use App\PostReport;
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
        $this->authorize('create', Post::class);
        if (Auth::check()) {
            $user = Auth::user();
        } else {
            $user = null;
        }

        $data = $request->validate([
            'community' => 'required',
            'title' => 'required',
            'private' => 'sometimes|accepted',
            'post_content' => 'required',
            'image' => 'nullable|mimes:jpeg,jpg,png,gif',
        ]);
        
        /* Check and create community if needed */
        $communities = Community::all()->pluck('name')->toArray();
        $community_name = $data['community'];

        // Make alphanumeric (removes all other characters)
        $community_name = preg_replace("/[\W0-9_\s-]/", "", $community_name);
        // Clean up multiple dashes or whitespaces
        $community_name = preg_replace("/[\s-]+/", " ", $community_name);
        // Convert whitespaces and underscore to dash
        $community_name = preg_replace("/[\s_]/", "-", $community_name);

        $lowerCommunities = array_map('strtolower', $communities);
        $lowerCommunityName = strtolower($community_name);
        if (in_array($lowerCommunityName, $lowerCommunities)) {
            $community = Community::where('name', 'ilike', '%' . $community_name . '%')->get()->first();
            $member = $community->members()->where('id_user', '=', $user->id)->first();

            if($member === null){
                if($community->private){
                    error_log("não é member");
                    return redirect('/community/'.$community->id);
                }
                $community->members()->attach(Auth::user()->id, []);
            }

            $community_id = $community->id;
        } else {
            if (!isset($data['private'])) {
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
            $community->members()->attach(Auth::user()->id, []);
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

        if ($post !== null) {
            $just_parent_comments = ['id_post' => $id, 'id_parent' => null];
            $just_replies = ['id_post' => $id, ['id_parent', '<>', null]];
            $comments = Comment::where($just_parent_comments)->orderBy('time_stamp', 'desc')->get();
            $replies = Comment::where($just_replies)->orderBy('time_stamp', 'desc')->get();
            return view('pages.post', ['post' => $post, 'user' => $user, 'comments' => $comments, 'replies' => $replies]);
        }

        abort(404);
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

        // $posts = Post::whereExists(function ($query, $user) {
        //     $query>select(DB::raw(1))
        //              ->from('community')
        //              ->whereRaw("community_member.id_user = ". $user->id." and community_member.id_community = community.id");

        //     $query->whereExists(function ($query, $user) {
        //        $query->select(DB::raw(1))
        //              ->from('community_member')
        //              ->whereRaw("community_member.id_user = ". $user->id." and community_member.id_community = community.id");
        //    });
        // })->get()->take(20);

        // ->orderBy('time_stamp','desc')->get()->take(20);

        $allPosts = [];
        $posts = [];
        if ($user !== null) {
            $posts = Post::getUserHomePosts();
            $follow_posts = Post::getUserFollowPosts();

            foreach ($posts as $post) {
                $newPost = Post::find($post->id);
                array_push($allPosts, $newPost);
            }
            foreach ($follow_posts as $post) {
                $newPost = Post::find($post->id);
                array_push($allPosts, $newPost);
            }

            usort($allPosts, function($a, $b) {return !strcmp($a->time_stamp, $b->time_stamp);});
            // $allPosts = $allPosts->sortByDesc('time_stamp');
        } else {
            $posts = Post::getPosts('time_stamp');
            
            foreach ($posts as $post) {
            $newPost = Post::find($post->id);
            array_push($allPosts, $newPost);
            }
        }
        
        $posts = array_slice($allPosts, 0, 15);
        // dd($posts);
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

        // $posts = Post::orderBy('time_stamp', 'desc')->get()->take(20);
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

        $allPosts = [];
        // $posts = Post::orderBy('time_stamp', 'desc')->get()->take(20);
        $posts = $user !== null ? Post::getUserHomePosts() : Post::getPosts('time_stamp');

        foreach ($posts as $post) {
            $newPost = Post::find($post->id);
            array_push($allPosts, $newPost);
        }
        
        usort($allPosts, function($a, $b) {return !strcmp($a->time_stamp, $b->time_stamp);});
        $posts = array_slice($allPosts, 0, 15);

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

        // $posts = Post::orderBy('upvotes', 'desc')->get()->take(20);
        $allPosts = [];

        $posts = $user !== null ? Post::getOtherPosts('upvotes') : Post::getPosts('upvotes');

        foreach ($posts as $post) {
            $newPost = Post::find($post->id);
            array_push($allPosts, $newPost);
        }
        $posts = array_slice($allPosts, 0, 15);

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

        // $posts = Post::orderBy('time_stamp', 'desc')->get()->take(20);
        $allPosts = [];

        $posts = $user !== null ? Post::getOtherPosts('time_stamp') : Post::getPosts('time_stamp');

        foreach ($posts as $post) {
            $newPost = Post::find($post->id);
            array_push($allPosts, $newPost);
        }

        $posts = array_slice($allPosts, 0, 15);
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

        $allPosts = [];
        // $posts = Post::orderBy('time_stamp', 'desc')->get()->take(20);

        if ($request['type'] == 'home') {
            if ($user !== null) {
                $joinedPosts = [];
                $posts = Post::getUserHomePosts();
                $follow_posts = Post::getUserFollowPosts();

                foreach ($posts as $post) {
                    array_push($joinedPosts, $post);
                }
                foreach ($follow_posts as $post) {
                    array_push($joinedPosts, $post);
                }
                $posts = $joinedPosts;
            } else {
                $posts = Post::getPosts('time_stamp');
            }
            // $posts =  $user !== null ?  Post::getUserHomePosts() : Post::getPosts('time_stamp');
            // $posts = Post::orderBy('time_stamp', 'desc')->skip($request['num_posts'])->take(5)->get();
        } else if ($request['type'] == 'popular') {
            $posts = $user !== null ? Post::getOtherPosts('upvotes') : Post::getPosts('upvotes');
            // $posts = Post::orderBy('upvotes', 'desc')->skip($request['num_posts'])->take(5)->get();
        } else if ($request['type'] == 'recent') {
            $posts = $user !== null ? Post::getOtherPosts('time_stamp') : Post::getPosts('time_stamp');
            // $posts = Post::orderBy('time_stamp', 'desc')->skip($request['num_posts'])->take(5)->get();
        }

        foreach ($posts as $post) {
            $newPost = Post::find($post->id);
            array_push($allPosts, $newPost);
        }

        usort($allPosts, function($a, $b) {return !strcmp($a->time_stamp, $b->time_stamp);});

        $posts = array_slice($allPosts, $request['num_posts'], 5);

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
        //TODO: add policy
        if (Auth::check()) {
            $user = Auth::user();
        } else {
            $user = null;
        }


        if ($user == null) {
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
            $post->content = $data['new_content'];
            $post->save();
            return response([
                'success' => true,
                "post_id" => $post->id,
                "new_content" => $post->content
            ]);
        } else {
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
        $post = Post::find($post_id);
        // $this->authorize('delete', $post);
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

        if($post != null){
            if ($post->delete()) {
                return response(['success'=>true]);
            } else {
                return response(['success'=>true]);
            }
        }
        abort(404);
    }

    public function adminDestroy($post_id)
    {
        // $this->authorize('adminDel', Post::class);
        DB::transaction(function () use ($post_id) {

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
        });

        return response([
                    'success' => true
                ]);
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

    /**
     * Report post
     *
     * @param  int  $post_id
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function report($post_id, Request $request)
    {
        $this->authorize('report', Post::class);
        $user = Auth::user();

        $data = $request->validate([
            'reason' => 'required|string'
        ]);

        $admins = Admin::all()->pluck('id')->toArray();
        $admin = $admins[array_rand($admins)];

        DB::transaction(function ()  use ($user, $admin, $post_id, $data) {
            // Create a record in the post report and report table
            $report = new Report();
            $report->reason = $data['reason'];
            $report->id_admin = $admin;
            $report->id_user = $user->id;
            $report->save();

            $post_report = new PostReport();
            $post_report->id_report = $report->id;
            $post_report->id_post = $post_id;
            $post_report->save();

            // Link them together
            $post_report->report()->save($report);
        });
    }
}