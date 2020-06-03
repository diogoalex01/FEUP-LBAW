<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


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

    /**
     * The post this community belongs to
     */
     public function requests()
    {
        return $this->hasMany('App\JoinCommunityRequest');
    }

    public static function userCommunitites($user_id){
        return DB::table('community')->join('community_member','community_member.id_community','community.id')->where('community_member.id_user',$user_id)->get();
    }

    public static function search($query){
        return DB::select(
            DB::raw("
            SELECT community_id, ts_rank_cd(to_tsvector('portuguese', c_search.name), query) AS weight
            FROM (
                SELECT community.id AS community_id, name
                    FROM community
                    GROUP BY community.id) c_search, to_tsquery('portuguese', :query) AS query
            WHERE (to_tsvector('portuguese', c_search.name)) @@ query 
            ORDER BY weight DESC;"),
            array('query' => $query)
        );
    }

}
