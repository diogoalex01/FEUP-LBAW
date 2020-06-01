<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PostReport extends Model
{
    // Don't add create and update timestamps in database.
    public $timestamps  = false;

    protected $primaryKey = 'id_report'; 

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'post_report';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        // 'id_community', 'content', 'title', 'id_author', 'image', 'upvotes', 'downvotes',
    ];

    public function report(){
        return $this->morphOne('App\Report', 'reportable');
    }

    public function post(){
        return $this->belongsTo('App\Post', 'id_post');
    }
}
