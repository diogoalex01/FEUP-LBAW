<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;



class User extends Authenticatable
{
    use Notifiable;

    // Don't add create and update timestamps in database.
    public $timestamps  = false;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'member_user';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'first_name', 'photo', 'last_name', 'gender', 'username', 'birthday', 'private',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The community this user owns.
     */
     public function community()
    {
        return $this->hasMany('App\Community');
    }

    /**
     * The posts this user owns.
     */
    public function posts()
    {
        return $this->hasMany('App\Posts');
    }

    /**
     * The comments this user owns.
     */
    public function comments()
    {
        return $this->hasMany('App\Comment');
    }

    /**
     * The communities this user owns.
     */
    public function communities()
    {
        return $this->belongsToMany('App\Community', 'community_member', 'id_user', 'id_community')->withPivot([]);
    }

    /**
     * The Post this user voted on.
     */
    public function votedPosts()
    {
        return $this->belongsToMany('App\Post', 'post_vote', 'id_user', 'id_post')->withPivot([
            'vote_type',
        ]);
    }

     /**
     * The Comment this user voted on.
     */
     public function votedComments()
    {
        return $this->belongsToMany('App\Comment', 'comment_vote', 'id_user', 'id_comment')->withPivot([
            'vote_type',
        ]);
    }

    public function sentRequests(){
        return $this->hasMany('App\Report');
    }

    public function receivedRequests(){
        return $this->hasMany('App\Report');
    }


    public static function search($query){
        return DB::select(
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
    }

    // public function get_post_vote(Post $post)
    // {
    //     return $this->belongsToMany('App\Post')
    //         ->using('App\PostVote')
    //         ->withPivot([
    //             'vote_type',
    //         ]);
    // }
}