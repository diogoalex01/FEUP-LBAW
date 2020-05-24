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

/*
        $auctions = DB::select(DB::raw("
                SELECT DISTINCT auctions.id as id, url, species_name, current_price, age, ending_date, id_status
                FROM (((auctions JOIN features ON auctions.id = features.id_auction) JOIN animal_photos ON auctions.id = animal_photos.id_auction) JOIN images ON animal_photos.id = images.id), 
                to_tsquery(:text) AS query, 
                to_tsvector(name || ' ' || species_name || ' ' || description ) AS textsearch
                WHERE (id_category IN (:mammals, :insects, :reptiles, :birds, :fishes, :amphibians ))  
                AND (id_main_color IN (:blue, :green, :brown, :red, :black, :white, :yellow))
                AND (id_dev_stage IN (:baby, :child, :teen, :adult, :elderly))
                AND (current_price < :max_price OR :max_price IS NULL)
                AND (current_price > :min_price OR :min_price IS NULL)
                AND (id_skill IN (:climbs, :jumps, :talks, :skates, :olfaction, :navigation, :echo, :acrobatics))
                AND query @@ textsearch
                AND id_status = 0
                ORDER BY ending_date;
                "), array(
                'text' => '%' . $request->input('search') . '%',
                'mammals' => $mammals, 'insects' => $insects, 'reptiles' => $reptiles, 'birds' => $birds, 'fishes' => $fishes, 'amphibians' => $amphibians,
                'blue' => $blue, 'green' => $green, 'brown' => $brown, 'red' => $red, 'black' => $black, 'white' => $white, 'yellow' => $yellow,
                'baby' => $baby,  'child' => $child, 'teen' => $teen, 'adult' => $adult, 'elderly' => $elderly,
                'max_price' => $request->input('max_price'), 'min_price' => $request->input('min_price'),
                'climbs' => $climbs, 'jumps' => $jumps, 'talks' => $talks, 'skates' => $skates, 'olfaction' => $olfaction, 'navigation' => $navigation, 'echo' => $echo, 'acrobatics' => $acrobatics
            ));
*/


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
                WHERE p_search.document @@ to_tsquery('portuguese', :query)

                "), array(
                'query' => $query
            ));

            $posts = [];
            foreach ($postsId as $post) {                
                array_push($posts, Post::find($post->post_id));
            }

/*
        $comments = DB::select(DB::raw("


            SELECT comment_id, document
                FROM (
                    SELECT comment.id AS comment_id,
                        (to_tsvector('portuguese', unaccent(comment.content)) ||
                        to_tsvector('portuguese', unaccent(member_user.username))) AS document
                        FROM comment
                        JOIN member_user ON member_user.id = comment.id_author
                        GROUP BY comment.id, member_user.id) c_search
                WHERE c_search.document @@ to_tsquery('portuguese', unaccent(':query'))

                "), array(
                'query' => $query,
            ));

        $communities = DB::select(DB::raw("

            SELECT community_id, document
                FROM (
                    SELECT community.id AS community_id,
                        (to_tsvector('portuguese', unaccent(community.name))) AS document
                        FROM community
                        GROUP BY community.id) c_search
                WHERE c_search.document @@ to_tsquery('portuguese', unaccent(':query'));

                "), array(
                'query' => $query
            ));
*/
//'comments' => $comments, 'communities'=> $communities
        return view('pages/search', ['user'=> $user, 'posts'=> $posts ]);
    }

}
