<?php

namespace App\Http\Controllers;

use App\MovieRequest;
use App\Plan;
use App\PushMessage;
use App\Subscriber;
use App\User;
use Illuminate\Http\Request;

class SubscriberController extends Controller
{

    public function lists(){
        $data = [
            'subscribers'=>User::where('role','SUBSCRIBER')->get(),
            'page_title'=>'Subscriber List(s)',

        ];

        return view('admin.subscriber.lists',$data);
    }


    public function active(){
        $data = [
            'subscribers'=>User::where('role','SUBSCRIBER')->where('subscription_expired_timestamp', '>', time())->get(),
            'page_title'=>'Active Subscriber List(s)',
            'type' => 'active'
        ];

        return view('admin.subscriber.lists',$data);
    }

    public function add_fund_to_wallet(Request $request){
        if($request->method() == "POST"){
            if(isset($request->subscriber_id)){
                $subs = User::find($request->subscriber_id);
                $wallet = ($subs->wallet == NULL ? 0 : $subs->wallet);
                $wallet = $wallet + $request->amount;
                $subs->wallet = $wallet;
                $subs->update();
            }
            return redirect()->route('subscribers.lists')->with('success','Fund has been added to wallet');
        }
        $data =  [
            'subscribers'=>User::where('role','SUBSCRIBER')->get(),
            'page_title'=>'Add Fund to Wallet'
        ];
        return view('admin.subscriber.add_fund_to_wallet',$data);
    }


    public function movies_request(){
        $data = [
            'movies_requests'=>MovieRequest::orderBy("id","DESC")->where("request_type","MOVIE")->get(),
            'page_title'=>'Movie Request List(s)',
        ];

        return view('admin.subscriber.movies_request',$data);
    }


    public function delete_movies_request($id){
       $movie = MovieRequest::find($id);
       if($movie){
           $movie->delete();
       }
       return redirect()->route('subscribers.movies_request')->with('success','Movie Request has been deleted successfully');
    }


    public function approve_movies_request(Request $request, $id){
        $movie = MovieRequest::find($id);
        if($request->method() == "POST"){
            $movie->status = "APPROVED";
            $movie->title = $request->title;
            $movie->movie_description = $request->comment;
            $movie->date_approved = date('Y-m-d');
            $movie->approved_by = auth()->id();
            $movie->update();
            if($request->page == "series")  return redirect()->route('subscribers.series_request')->with('success','Request has been approved successfully');
            return redirect()->route('subscribers.movies_request')->with('success','Movie Request has been approved successfully');
        }

        if(!$movie){
            if($request->page == "series")  return redirect()->route('subscribers.series_request')->with('error','Request has been not found, please check and try again');
            return redirect()->route('subscribers.movies_request')->with('error','Request has been not found, please check and try again');
        }
        $data = [
            'movies_request'=>$movie,
            'page_title'=>'Approve Movie Request',
        ];
        return view('admin.subscriber.approve_request',$data);

    }

    public function series_request(){
        $data = [
            'movies_requests'=>MovieRequest::orderBy("id","DESC")->where("request_type","SERIES")->get(),
            'page_title'=>'Series Request List(s)',
        ];

        return view('admin.subscriber.series_request',$data);
    }


    public function schedule_movies_request(Request $request, $id){
        session()->put('movie_request_id',$id);
        return  redirect()->route('setting.form',['movie_request_id'=>$id])->with('movie_request_id',$id);
    }


    public function  list_notification(){
        $data = [
            'lists'=>PushMessage::orderBy("id","DESC")->get(),
            'page_title'=>'List Notification',
        ];

        return view('admin.subscriber.list_notification',$data);
    }


    public function new_notification(Request $request){
        if($request->method() == "POST"){
            $title = $request->title;
            $body = $request->message;
            $customer_type = ($request->customer_type == "all" ? NULL : $request->customer_type);
            $customer_group = ($request->customer_group == "all" ? NULL : $request->customer_group);

            $user = User::select("device_key")->whereNotNull("device_key");
            if($customer_type != NULL){
                $user->where("type",$customer_group);
            }
            if($customer_group != NULL){
                $user->where("customer_group_id",$customer_group);
            }

            $device_tokens = $user->get();

            if($device_tokens->count() == 0){
                redirect()->back()->with("error","Total Number of targeted device is zero");
            }else{
                $device = [];
                foreach($device_tokens->toArray() as $d){
                    $device[] = $d['device_key'];
                }
                $no_of_device = $device_tokens->count();
                $device_tokens = json_encode($device);

                $create = [
                    "title"=>$title,
                    "body"=>$body,
                    "payload"=>json_encode([]),
                    "device_ids"=>$device_tokens,
                    "no_of_device"=>$no_of_device,
                    "type"=>"topic",
                    "status"=>"SENT",
                ];
                $noti = PushMessage::create($create);
                $extra_payload = $noti->toArray();
                $device_ids = json_decode($extra_payload['device_ids'],true);
                unset($extra_payload['payload']);
                unset($extra_payload['device_ids']);
                $payload = ['action'=>$noti->action,'data'=>json_decode($noti->payload), "notification"=>$extra_payload];
                if($noti->type == "topic") {
                    pushNotificationToTopic($payload, $noti->title, $noti->body);
                }else{
                    pushNotificationToDevice($payload,$noti->title, $noti->body,$device_ids);
                }
                redirect()->route("subscribers.new_notification")->with("success","Notification has been sent successfully!..");
            }
        }

        $data = [
            "page_title" => "New Notification",
        ];
        return view('admin.subscriber.new_notification', $data);
    }


    public function delete_notification($notification_id){
        $noti = PushMessage::find($notification_id);

        $noti->delete();

        return redirect()->route('subscribers.list_notification')->with('success','Notification has been deleted successfully!..');
    }

    public function update_notification($notification_id, Request $request){

        if($request->method() == "POST"){

            $notification = PushMessage::find($notification_id);
            $title = $request->title;
            $body = $request->message;

            $notification->title = $title;
            $notification->body = $body;

            $notification->update();
            return redirect()->route('subscribers.list_notification')->with('success','Notification has been updated successfully!..');
        }

        $data = [
            "page_title" => "New Notification",
            'notification' => PushMessage::find($notification_id)
        ];


        return view('admin.subscriber.edit_notification', $data);
    }
}
