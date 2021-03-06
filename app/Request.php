<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Request extends Model
{
    // Don't add create and update timestamps in database.
    public $timestamps  = false;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'request';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id_receiver', 'id_sender', 'status',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    // protected $hidden = [
    // ];

    public function sender(){
        return $this->belongsTo('App\User', 'id_sender');
    }

    public function receiver(){
        return $this->belongsTo('App\User', 'id_receiver');
    }

    public function requestable()
    {
        return $this->morphTo();
    }
}
