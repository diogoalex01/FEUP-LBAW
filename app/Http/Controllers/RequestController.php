<?php

namespace App\Http\Controllers;
use App\JoinCommunityRequest as JoinRequest;
use App\Request as RequestModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Notifications\Notifiable;

class RequestController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $requests = RequestModel::where('id_receiver', Auth::user()->id)->orderBy('time_stamp', 'desc')->get();
        $response = [];
        foreach ($requests as $request){
            $item['request'] = $request;
            $item['sender'] = $request->sender;
            
            if($request->requestable instanceof JoinRequest){
                $item['community'] = $request->requestable->community;
            }
            
            array_push($response, $item);
        }
        
        return response(['response' => $response]);
    }
    

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Request  $request_id
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update($request_id, Request $request)
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

        $request = RequestModel::find($request_id);
        if($request !== null){
            if($request->id_receiver == $user->id){
                if ($data['status'] ==='accept'){
                    if ($request->requestable instanceof JoinRequest){
                        $community_id = $request->requestable->community->id;
                        DB::insert('insert into community_member (id_user, id_community) values (?, ?)', [$request->id_sender, $community_id]);
                    }else{
                        DB::insert('insert into follow_user (id_follower, id_followed) values (?, ?)', [$request->id_sender, $request->id_receiver]);
                    }
                }

                $request->delete();
               
            }
        }else{
            return response([
                'success' => false,
            ]); 
        }

        return response([
        'success' => true,
        ]);          
    }
}