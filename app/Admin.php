<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;

class Admin extends Authenticatable
{
    // Don't add create and update timestamps in database.
    public $timestamps  = false;

    /* The table associated with the model.
    *
    * @var string
    */
    protected $table = 'admin_user';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'email', 'password'
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
     * The reports this comment has.
     */
    public function reports()
    {
        return $this->hasMany('App\Report', 'id_admin');
    }

}