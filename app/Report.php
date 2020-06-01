<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    // Don't add create and update timestamps in database.
    public $timestamps  = false;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'report';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        /* 'id_community', 'content', 'title', 'id_author', 'image', 'upvotes', 'downvotes',*/];

    public function reportable()
    {
        return $this->morphTo();
    }

    /**
     * The admin this report is reponsilbe to
     */
    public function admin()
    {
        return $this->belongsTo('App\Admin', 'id_admin');
    }

    /**
     * The reporter this report belongs to
     */
    public function reporter()
    {
        return $this->belongsTo('App\User', 'id_user');
    }
}
