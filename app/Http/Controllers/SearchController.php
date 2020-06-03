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

        $userId = DB::select(
            DB::raw("
            SELECT user_id, ts_rank_cd(to_tsvector('portuguese', c_search.username), query) AS weight
            FROM (
                SELECT member_user.id AS user_id, member_user.username AS username
                    FROM member_user
                    GROUP BY user_id) c_search, to_tsquery('portuguese', :query) AS query
                WHERE (to_tsvector('portuguese', c_search.username)) @@ query 
            ORDER BY weight DESC;"),

            array('query' => $query)
        );

        $memberUser = [];
        foreach ($userId as $user) {
            array_push($memberUser, User::find($user->user_id));
        }

        //dd($memberUser);
        $postsId = DB::select(
            DB::raw("
            SELECT post_id, ts_rank_cd(search_weight, query) AS weight
            FROM(
                    SELECT *, post.id AS post_id
                        FROM post JOIN member_user ON member_user.id = post.id_author
                        GROUP BY post.id, member_user.id) abc,
                        to_tsquery('portuguese', :query) AS query
                        WHERE search_weight @@ query
                    ORDER BY weight DESC;"),

            array('query' => $query)
        );

        $posts = [];
        foreach ($postsId as $post) {
            array_push($posts, Post::find($post->post_id));
        }

        $communitiesId = DB::select(
            DB::raw("
            SELECT community_id, ts_rank_cd(to_tsvector('portuguese', c_search.name), query) AS weight
            FROM (
                SELECT community.id AS community_id, name
                    FROM community
                    GROUP BY community.id) c_search, to_tsquery('portuguese', :query) AS query
            WHERE (to_tsvector('portuguese', c_search.name)) @@ query 
            ORDER BY weight DESC;"),
            array('query' => $query)
        );

        $communities = [];
        foreach ($communitiesId as $community) {
            array_push($communities, Community::find($community->community_id));
        }

        $commentsId = DB::select(
            DB::raw("

                SELECT comment_id, ts_rank_cd(to_tsvector('portuguese', c_search.content), query) AS weight
                FROM (
                    SELECT comment.id AS comment_id, comment.content AS content
                        FROM comment
                        GROUP BY comment.id) c_search, to_tsquery('portuguese', :query) AS query
                WHERE (to_tsvector('portuguese', c_search.content)) @@ query 
                ORDER BY weight DESC;"),
            array('query' => $query)
        );

        $comments = [];
        foreach ($commentsId as $comment) {
            array_push($comments, Comment::find($comment->comment_id));
        }

        //'comments' => $comments, 'communities'=> $communities
        //'comments' => $comments, 'communities'=> $communities
        return view('pages/search', ['user' => $user, 'memberUsers' => $memberUser, 'posts' => $posts, 'communities' => $communities, 'comments' => $comments, 'query' => $data['query']]);
    }
}
