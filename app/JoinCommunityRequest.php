<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class JoinCommunityRequest extends Model
{
    // Don't add create and update timestamps in database.
    public $timestamps  = false;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'join_community_request';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    // protected $hidden = [
    // ];

    public function request(){
        return $this->morphOne('App\Report', 'requestable');
    }

    public function community(){
        return $this->belongsTo('App\Community', 'id_community');
    }
}