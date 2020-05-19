<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

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
}
