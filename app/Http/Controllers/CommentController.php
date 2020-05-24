<?php

namespace App\Http\Controllers;

use App\Comment;
use App\User;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class CommentController extends Controller
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
        $data = $request->validate([
            'user_id' => 'required',
            'post_id' => 'required',
            'content' => 'required',
        ]);

        /* Create Comment */
        $comment = Comment::create([
            'id_author' => $data['user_id'],
            'id_post' => $data['post_id'],
            'content' => $data['content'],
        ]);

        $author = User::find($comment['id_author']);

        $comment->save();
        return response()->json(array(
            'success' => true,
            'comment' => $comment,
            'extras' => ['author_username'=> $author->username, 'author_photo' => $author->photo]
        ), 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function show(Comment $comment)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function edit(Comment $comment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Comment $comment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        //TODO: add policy
        if (Auth::check()) {
            $user = Auth::user();
        } else {
            $user = null;
        }
        error_log("2\n\n");

        if ($user == null)
            return redirect('/');

        $data = $request->validate([
            'comment_id' => 'required',
        ]);

        $comment = Comment::find($data['comment_id']);

        if ($comment->delete()) {
            return response([
            'success' => true
        ]);
        } else {
            return response([
            'success' => false
        ]);
        }
    }

    public function storeReply(Request $request)
    {

        //
        $data = $request->validate([
            'user_id' => 'required',
            'post_id' => 'required',
            'comment_id' => 'required',
            'reply' => 'required',
        ]);

        /* Create Comment */
        $comment = Comment::create([
            'id_author' => $data['user_id'],
            'id_post' => $data['post_id'],
            'content' => $data['reply'],
            'id_parent' => $data['comment_id']
        ]);

        $comment->save();
        $author = User::find($comment['id_author']);

        return response()->json(array(
            'success' => true,
            'comment' => $comment,
            'extras' => ['author_username'=> $author->username, 'author_photo' => $author->photo]
        ), 200);
    }

    public function vote(Request $request)
    {
        if (Auth::check()) {
            $user = Auth::user();
        } else {
            $user = null;
        }

        if ($user == null)
            return redirect('/');

        $data = $request->validate([
            'comment_id' => 'required',
            'vote_type' => 'required',
        ]);

        $comment = Comment::find($data['comment_id']);

        // if (strcmp("up", $data['vote_type']) == 0) {
        //     $post->upvotes = $post->upvotes + 1;
        // } else if (strcmp("down", $data['vote_type']) == 0) {
        //     $post->downvotes = $post->downvotes + 1;
        // }1, ['products_amount' => 100, 'price' => 49.99]

        $comment->votedUsers()->attach($user->id, ['vote_type' => $data['vote_type']]);

        $comment->save();

        return response([
            'success' => true,
        ]);
    }

    public function vote_edit(Request $request)
    {

        if (Auth::check()) {
            $user = Auth::user();
        } else {
            $user = null;
        }

        if ($user == null)
            return redirect('/');

        $data = $request->validate([
            'comment_id' => 'required',
            'vote_type' => 'required',
        ]);

        $comment = Comment::find($data['comment_id']);

        
        $comment->votedUsers()->updateExistingPivot($user->id, ['vote_type' => $data['vote_type']]);

        return response([
            'success' => true,
        ]);
    }

    public function vote_delete(Request $request)
    {
        if (Auth::check()) {
            $user = Auth::user();
        } else {
            $user = null;
        }

        if ($user == null)
            return redirect('/');

        $data = $request->validate([
            'comment_id' => 'required',
            'vote_type' => 'required',
        ]);

        $comment = Comment::find($data['comment_id']);

        $comment->votedUsers()->detach($user->id);

        return response([
            'success' => true,
        ]);
    }
}
