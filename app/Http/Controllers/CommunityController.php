<?php

namespace App\Http\Controllers;

use App\Community;
use App\Post;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CommunityController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Community  $community
     * @return \Illuminate\Http\Response
     */
    public function show($community_id)
    {
        $communities = Community::all();
        $community = $communities->find($community_id);
        $member_users = User::all();
        $member_user = $member_users->find($user_id);
        $posts = Post::where('id_community', '=', $community_id)->orderBy('time_stamp', 'desc')->get();
        //$comments = DB::table('comment')->where('id_post', '=', $id)->orderBy('time_stamp', 'desc')->get();
        return view('pages.community', ['community' => $community, 'user' => $member_user, 'posts' => $posts]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Community  $community
     * @return \Illuminate\Http\Response
     */
    public function edit(Community $community)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Community  $community
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Community $community)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Community  $community
     * @return \Illuminate\Http\Response
     */
    public function destroy(Community $community)
    {
        //
    }

    public function get_all()
    {
        $communities = DB::table('community')->get();
        // $communities = DB::table('community')->pluck('name', 'image');
        return $communities;
    }

    public function find(Request $request)
    {
        // $data = $request->validate([
        //     'community_name' => 'required'
        //     //'image' => 'sometimes|image',
        // ]);
        // $community = Community::where('name', '=', $data['community_name'])->first();
        // //if ($community !== null) {
        //     //}
        // return $community;
    }
}
