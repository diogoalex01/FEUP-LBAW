<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;


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
        return $this->hasMany('App\Community');
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

    // public function get_post_vote(Post $post)
    // {
    //     return $this->belongsToMany('App\Post')
    //         ->using('App\PostVote')
    //         ->withPivot([
    //             'vote_type',
    //         ]);
    // }
}