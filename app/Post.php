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
        'id_community', 'content', 'title', 'id_author', 'image',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    // protected $hidden = [
    // ];

    /**
     * The user this post belongs to
     */
    public function user()
    {
        return $this->hasOne('App\User', 'id_author');
    }

    /**
     * The community this post belongs to
     */
    public function community()
    {
        return $this->hasOne('App\Community', 'id_community');
    }

    /**
     * The comments this post has.
     */
    public function comments()
    {
        return $this->hasMany('App\Comment');
    }
}
