<?php

namespace App\Http\Controllers;

use App\Post;
use App\Comment;
use App\Community;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Auth;

class SearchController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Request
     */
    public function search_results(Request $request)
    {
        $data = $request->validate([
            'query' => 'required|string'
        ]);
        $query = $data['query'];

        if (Auth::guard('admin')->check()) {
            $user = Auth::guard('admin')->user();
        } else {
            $user = Auth::user();
        }

        $query = "'" . $query . "':*";

        $userId = User::search($query);

        $memberUser = [];
        foreach ($userId as $user) {
            array_push($memberUser, User::find($user->user_id));
        }

        //dd($memberUser);
        $postsId = Post::search($query);

        $posts = [];
        foreach ($postsId as $post) {
            array_push($posts, Post::find($post->post_id));
        }

        $communitiesId = Community::search($query);

        $communities = [];
        foreach ($communitiesId as $community) {
            array_push($communities, Community::find($community->community_id));
        }

        $commentsId = Comment::search($query);

        $comments = [];
        foreach ($commentsId as $comment) {
            array_push($comments, Comment::find($comment->comment_id));
        }

        //'comments' => $comments, 'communities'=> $communities
        //'comments' => $comments, 'communities'=> $communities
        return view('pages/search', ['user' => $user, 'memberUsers' => $memberUser, 'posts' => $posts, 'communities' => $communities, 'comments' => $comments, 'query' => $data['query']]);
    }
}
