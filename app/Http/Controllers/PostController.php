<?php

namespace App\Http\Controllers;

use App\Post;
use App\Community;
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
            $user = null;
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
        //$post = new Post();
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

        if (in_array($community_name, $communities)) {
            $community_id = DB::table('community')->where('name', '=', $community_name)->get()->first()->id;
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

        // $path = $request->file('image')->store('img');
        //$path = Storage::putFile('avatars', $request->file('avatar'));
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
        // $post = Card::find($id);

        //$this->authorize('show', $post);

        //return view('pages.post', ['post' => $post]);
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

        $posts = DB::table('post')->orderBy('time_stamp', 'desc')->get()->take(30);

        return view('pages.home', ['posts' => $posts, 'user' => $user]);
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
}
