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
}
