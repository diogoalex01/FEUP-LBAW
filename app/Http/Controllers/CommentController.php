<?php

namespace App\Http\Controllers;

use App\Comment;
use App\User;
use App\Admin;
use App\Report;
use App\CommentReport;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;


class CommentController extends Controller
{
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
            'extras' => ['author_username' => $author->username, 'author_photo' => $author->photo]
        ), 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function update($comment_id, Request $request)
    {
        //
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

        $comment = Comment::find($comment_id);

        if ($comment != null) {
            error_log("\n3\n");
            $comment->content = $data['new_content'];
            $comment->save();
            return response([
                'success' => true,
                "comment_id" => $comment->id,
                "new_content" => $comment->content
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
     * @param  \App\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function destroy($comment_id)
    {
        //TODO: add policy
        if (Auth::check()) {
            $user = Auth::user();
        } else {
            $user = null;
        }
        error_log("\n2\n\n");

        if ($user == null)
            return redirect('/');

        $comment = Comment::find($comment_id);

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

    public function adminDestroy($comment_id)
    {
        //$this->authorize('view', Admin::class);
        DB::transaction(function () use ($comment_id){
            $comment = Comment::find($comment_id);

            if ($comment->delete()) {
                return response([
                    'success' => true
                ]);
            } else {
                return response([
                    'success' => false
                ]);
            }
        });
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
            'extras' => ['author_username' => $author->username, 'author_photo' => $author->photo]
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

    /**
     * Report comment
     *
     * @param  int  $comment_id
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function report($comment_id, Request $request)
    {
        $this->authorize('report', Comment::class);
        $user = Auth::user();

        $data = $request->validate([
            'reason' => 'required|string'
        ]);

        $admins = Admin::all()->pluck('id')->toArray();
        $admin = $admins[array_rand($admins)];

        DB::transaction(function ()  use ($user, $admin, $comment_id, $data) {
            // Create a record in the comment report and report table
            $report = new Report();
            $report->reason = $data['reason'];
            $report->id_admin = $admin;
            $report->id_user = $user->id;
            $report->save();

            $comment_report = new CommentReport();
            $comment_report->id_report = $report->id;
            $comment_report->id_comment = $comment_id;
            $comment_report->save();

            // Link them together
            $comment_report->report()->save($report);
        });

        //TODO: mostrar mensagem de sucesso?
    }
}
