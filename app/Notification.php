<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
       // Don't add create and update timestamps in database.
       public $timestamps  = false;

/**
 * The table associated with the model.
 *
 * @var string
 */
protected $table = 'notification';

/**
 * The attributes that are mass assignable.
 *
 * @var array
 */
protected $fillable = [
    'id_request', 'is_read',
];

/**
 * The attributes that should be hidden for arrays.
 *öu
 * @var array
 */
// protected $hidden = [
// ];

}
