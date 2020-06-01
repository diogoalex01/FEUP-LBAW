<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Community extends Model
{
    // Don't add create and update timestamps in database.
    public $timestamps  = false;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'community';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'image', 'private',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    // protected $hidden = [
    // ];

    /**
     * The user this community belongs to
     */
    public function user()
    {
        return $this->belongsTo('App\User', 'id_owner');
    }

        /**
     * The post this community belongs to
     */
    public function posts()
    {
        return $this->hasMany('App\Post');
    }


    /**
     * The post this community belongs to
     */
     public function members()
    {
        return $this->belongsToMany('App\User', 'community_member', 'id_community', 'id_user')->withPivot([]);
    }

}
