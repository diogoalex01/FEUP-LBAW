<?php

namespace App\Http\Controllers;

use App\Notification;
use App\Request as RequestModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Notifications\Notifiable;

class NotificationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // $notifications = Notification::orderBy('time_stamp', 'desc')->take(5)->get();
        $join_notifications = DB::table('notification')
                ->join('request', 'notification.id_request', '=', 'request.id')
                ->join('join_community_request', 'join_community_request.id', '=', 'request.id')
                ->join('community', 'community.id', '=', 'join_community_request.id_community')
                ->join('member_user', 'member_user.id', '=', 'request.id_sender')
                ->where('request.id_receiver', '=', Auth::user()->id)
                ->select('notification.id', 'notification.is_read', 'notification.id_request', 'request.time_stamp', 'request.status', 'request.id_receiver', 'request.id_sender', 'member_user.username', 'member_user.photo', 'community.id as community_id', 'community.image', 'community.name')
                ->orderBy('request.time_stamp', 'desc')
                ->get();;
        $follow_notifications = DB::table('notification')
                ->join('request', 'notification.id_request', '=', 'request.id')
                ->join('follow_request', 'follow_request.id', '=', 'request.id')
                ->join('member_user', 'member_user.id', '=', 'request.id_sender')
                ->where('request.id_receiver', '=', Auth::user()->id)
                ->select('notification.id', 'notification.is_read', 'notification.id_request', 'request.time_stamp', 'request.status', 'request.id_receiver', 'request.id_sender', 'member_user.username', 'member_user.photo' )
                ->orderBy('request.time_stamp', 'desc')
                ->get();

        return response(['follow_notifications' => $follow_notifications,'join_notifications' => $join_notifications]);
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
     * @param  \App\Notification  $notification
     * @return \Illuminate\Http\Response
     */
    public function show(Notification $notification)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Notification  $notification
     * @return \Illuminate\Http\Response
     */
    public function edit(Notification $notification)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Notification  $notification
     * @return \Illuminate\Http\Response
     */
    public function update($notification_id, Request $request)
    {
        // post_id: targetId, vote_type: 'up'
        //Todo: Policy users can only change status of their notifications
        if (Auth::check()) {
            $user = Auth::user();
        } else {
            $user = null;
        }

        if ($user == null)
            return redirect('/');

        $data = $request->validate([
            'status' => 'required',
        ]);

        $notification = Notification::find($notification_id);

        // if (strcmp("up", $data['vote_type']) == 0) {
        //     $post->upvotes = $post->upvotes + 1;
        // } else if (strcmp("down", $data['vote_type']) == 0) {
        //     $post->downvotes = $post->downvotes + 1;
        // }1, ['products_amount' => 100, 'price' => 49.99]

        $request = RequestModel::find($notification->id_request);
        if($request !== null){
            if($request->id_receiver == $user->id){
                if($data['status'] ==='accept'){
                    $follow_request = DB::table('follow_request')->where('id', '=', $request->id)->get();
                    if($follow_request !== null){
                        DB::insert('insert into follow_user (id_follower, id_followed) values (?, ?)', [$request->id_sender, $request->id_receiver]);
                    } else {
                        $join_request = DB::table('join_community_request')->where('id', '=', $request->id)->get();
                        if($join_request != null){
                        DB::insert('insert into community_member (id_user, id_community) values (?, ?)', [$request->id_sender, $join_request->id_community]);
                    }
                    }
                }
            $request->delete();
            }

            return response([
            'success' => true,
            ]);
        }    
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Notification  $notification
     * @return \Illuminate\Http\Response
     */
    public function destroy(Notification $notification)
    {
        //
    }
}