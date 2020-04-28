<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    // Don't add create and update timestamps in database.
    public $timestamps  = false;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'comment';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id_post', 'content', 'id_author', 'upvotes','downvotes','time_stamp','id_parent',
    ];

    /**
     * The Post this comment belongs to
     */
     public function Post()
    {
        return $this->hasOne('App\Post', 'id_post');
    }

     /**
     * The comment this reply belongs to
     */
     public function parent()
    {
        return $this->hasOne('App\Comment', 'id_parent');
    }

     /**
     * The replies this comment has.
     */
     public function replies()
    {
        return $this->hasMany('App\Comment');
    }
}
