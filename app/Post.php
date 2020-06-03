<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;


class Post extends Model
{
    // Don't add create and update timestamps in database.
    public $timestamps  = false;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'post';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id_community', 'content', 'title', 'id_author', 'image', 'upvotes', 'downvotes',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *öu
     * @var array
     */
    // protected $hidden = [
    // ];

    /**
     * The user this post belongs to
     */
    public function user()
    {
        return $this->belongsTo('App\User', 'id_author');
    }

    /**
     * This post belongs to a community 
     */
    public function community()
    {
        return $this->belongsTo('App\Community', 'id_community');
    }

    /**
     * The comments this post has.
     */
    public function comments()
    {
        return $this->hasMany('App\Comment');
    }

    //return $this->belongsToMany('App\Role', 'role_user', 'user_id', 'role_id');

    /**
     * Users who have voted on this post 
     */
    public function votedUsers()
    {
        return $this->belongsToMany('App\User', 'post_vote', 'id_post', 'id_user')->withPivot([
            'vote_type',
        ]);
    }

    public static function getUserHomePosts()
    {
        if (Auth::check()) {
            $user = Auth::user();
        } else {
            $user = null;
        }

        return DB::select(
            DB::raw(
                "select * from post where exists 
                    (select * from community_member 
                        where (community_member.id_community = post.id_community 
                        and 
                        community_member.id_user =" . $user->id . ") 
                    )
                and 
                    not exists (select * from block_user
                                 where
                                    ( 
                                        (block_user.blocked_user = post.id_author and block_user.blocker_user = " . $user->id . ")
                                    or 
                                        (block_user.blocker_user = post.id_author and block_user.blocked_user = " . $user->id . ")
                                    )
                                )
            order by (post.time_stamp) desc; ",
                ['user' => $user->id]
            )
        );

    }

    public static function getUserFollowPosts()
    {
        if (Auth::check()) {
            $user = Auth::user();
        } else {
            $user = null;
        }

        return DB::select(
            DB::raw(
                "select * from post where exists 
                    (select * from follow_user 
                        where (follow_user.id_followed = post.id_author 
                        and 
                        follow_user.id_follower =" . $user->id . ") 
                    )
                and exists (select * from community
                                 where
                                    ( 
                                        community.id = post.id_community
                                        and
                                        community.private = false
                                    )
                            )
            order by (post.time_stamp) desc; ",
                ['user' => $user->id]
            )
        );
    }
    public static function getOtherPosts($criteria)
    {
        if (Auth::check()) {
            $user = Auth::user();
        } else {
            $user = null;
        }

        return DB::select(
            DB::raw(
                "select * from post where 
                    not exists (select * from block_user
                                 where
                                    ( 
                                        (block_user.blocked_user = post.id_author and block_user.blocker_user = " . $user->id . ")
                                    or 
                                        (block_user.blocker_user = post.id_author and block_user.blocked_user = " . $user->id . ")
                                    )
                                )
                                and 
                                (
                                     ( exists 
                                        (select * from community_member 
                                            where (community_member.id_community = post.id_community 
                                            and 
                                            community_member.id_user =" . $user->id . ") 
                                        ))
                                    or
                                    (
                                        exists 
                                    (select * from community 
                                        where (community.id= post.id_community 
                                        and 
                                        community.private = false) 
                                    )
                                    )
                                )
            order by (post." . $criteria . ") desc; ",
                ['user' => $user->id]
            )
        );
    }

    public static function getPosts($criteria)
    {
        return DB::select(
            DB::raw(
                "select * from post where exists 
                    (select * from community 
                        where (community.id = post.id_community 
                        and 
                        community.private = false) 
                    )
            order by (post.".$criteria.") desc; ",
                []
            )
        );
    }

    public static function search($query){
        return DB::select(
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
    }


}
