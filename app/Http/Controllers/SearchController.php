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

        if (Auth::check()) {
            $user = Auth::user();
        } else {
            return redirect()->back()->with('showModal', "welcome");
        }

        $postsId = DB::select(DB::raw("

            SELECT post_id, document
                FROM (
                    SELECT post.id AS post_id,
                        post.title AS title,
                        (to_tsvector('portuguese', post.title) ||
                        to_tsvector('portuguese', post.content) ||
                        to_tsvector('portuguese', member_user.username)) AS document
                        FROM post
                        JOIN member_user ON member_user.id = post.id_author
                        GROUP BY post.id, member_user.id) p_search
                        WHERE to_tsquery(:query) @@ p_search.document

                "), array(
                'query' => $query
            ));

            $posts = [];
            foreach ($postsId as $post) {                
                array_push($posts, Post::find($post->post_id));
            }

        $communitiesId = DB::select(DB::raw("

        SELECT community_id, document
        FROM (
            SELECT community.id AS community_id,
                (to_tsvector('portuguese', community.name)) AS document
                FROM community
                GROUP BY community.id) c_search
        WHERE to_tsquery(:query) @@ c_search.document;


                "), array(
                'query' => $query
            ));

            $communities = [];
            foreach ($communitiesId as $community) {                
                array_push($communities, Community::find($community->community_id));
            }

            $commentsId = DB::select(DB::raw("

                SELECT comment_id, document
                FROM (
                    SELECT comment.id AS comment_id,
                        (to_tsvector('portuguese', comment.content) ||
                        to_tsvector('portuguese', member_user.username)) AS document
                        FROM comment
                        JOIN member_user ON member_user.id = comment.id_author
                        GROUP BY comment.id, member_user.id) c_search
                WHERE to_tsquery(:query) @@ c_search.document

                "), array(
                'query' => $query
            ));

            $postComments = [];
            foreach ($commentsId as $comment) {                
                $comment1 = Comment::find($comment->comment_id);
                $foundPost =  Post::find($comment1->id_post);
                if(!in_array($foundPost, $postComments))
                {
                    array_push($postComments, $foundPost);
                }
            }


//'comments' => $comments, 'communities'=> $communities
        return view('pages/search', ['user'=> $user, 'posts'=> $posts, 'communities'=> $communities, 'postComments'=> $postComments ]);
    }

}