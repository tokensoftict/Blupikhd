<?php

namespace App\Http\Controllers\Api;

use App\Country;
use App\Mail\PasswordResetMail;
use App\MovieRequest;
use App\Plan;
use App\Post;
use App\PostComment;
use App\ProgrammeSchedule;
use App\PushMessage;
use App\Setting;
use App\State;
use App\TopupTransaction;
use App\Transaction;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Mail;
use Stripe\Charge;
use Stripe\PaymentIntent;
use Stripe\Stripe;
use Illuminate\Support\Facades\Hash;

class App extends Controller
{

    public function states($country ="name"){
        $country = ucwords(strtolower($country));
        if(is_numeric($country)){
            $country_id = $country;
        }else{
            $country_id = Country::where("name",$country)->get()->first()->id;
        }
        $state = State::where("country_id",$country_id)->pluck('name');

        return response()->json($state);
    }


    public function countries(){
        $countries = Country::pluck('name');
        return response()->json($countries);
    }


    public function getPopularBlogpost(){
        $posts = Post::orderBy("total_view",'DESC')->where("status","PUBLISHED")->limit(5)->get()->toArray();
        return response()->json($posts);
    }


    public function getPosts(){
        $posts = Post::orderBy("id","DESC")->where("status","PUBLISHED")->limit(10)->get()->toArray();
        return response()->json($posts);
    }

    public function viewpost($post_id =0){
        $post = Post::find($post_id);
        $view = $post->total_view;
        $view+=1;
        $post->total_view = $view;
        $post->update();
        return response()->json($post);
    }

    public function gethome(){
        $data = [
            'popular'=>Post::where("status","PUBLISHED")->orderBy("total_view",'DESC')->limit(5)->get()->toArray(),
            'recent'=>Post::where("status","PUBLISHED")->orderBy("id","DESC")->limit(10)->get()->toArray()
        ];
        return response()->json($data);
    }

    public function transfer_fund(Request $request){
        $sending_amt = $request->amount;
        if($sending_amt < 0 || $sending_amt ==0){
            return response()->json(['status'=>false,"error"=>"Please enter amount greater than Zero"]);
        }
        $recipient = User::where("email",$request->email_address)->first();
        if(!$recipient){
            return response()->json(['status'=>false,"error"=>"Invalid Recipient Email Address, Recipient account not found!"]);
        }

        $recipient = $recipient->id;

        $sender =  User::find(auth('api')->id());

        $recipient = User::find($recipient);

        if($sender->wallet < $request->amount){
            return  response()->json(['status'=>false,"error"=>"Insufficient Balance, Please top up your wallet to continue"]);
        }

        if($sender->email ==  $recipient->email){
            return  response()->json(['status'=>false,"error"=>"You can not transfer funds to yourself"]);
        }

        $transaction = new Transaction;



        $transaction->user_id = $sender->id;
        $transaction->recipient_id = $recipient->id;

        $transaction->trans_type = "FUND TRANSFER";

        $transaction->sign = "-";

        $transaction->mode = 'WALLET';

        $transaction->amount = $request->amount;

        $transaction->transaction_date = date("Y-m-d");

        $transaction->description = "FUND TRANSFER";

        $sender_wallet_amt = empty($sender->wallet) ? 0 : $sender->wallet;
        $recipient_wallet_amt = empty($recipient->wallet) ? 0 : $recipient->wallet;

        $transaction->currency = Setting::find(1)->currency;

        $transaction->wallet_amt_before =   $sender_wallet_amt;

        $transaction->wallet_amt_after =  $sender_wallet_amt - $request->amount;

        $transaction->save();

        //now lets save transaction for recipient

        $transaction = new Transaction;

        $transaction->amount = $request->amount;

        $transaction->user_id = $recipient->id;

        $transaction->recipient_id = $sender->id;

        $transaction->trans_type = "FUND TRANSFER";

        $transaction->mode = 'WALLET';

        $transaction->sign = "+";

        $transaction->transaction_date = date("Y-m-d");

        $transaction->description = "FUND TRANSFER";

        $transaction->currency = Setting::find(1)->currency;

        $transaction->wallet_amt_before =  $recipient_wallet_amt;

        $transaction->wallet_amt_after = $recipient_wallet_amt + $request->amount;

        $transaction->save();


        //lets perform the real transaction
        $sender =  User::find(auth('api')->id());

        $recipient = User::find($recipient->id);

        $s_amt = $sender->wallet;

        $s_amt = $s_amt - $request->amount;

        $sender->wallet = $s_amt;

        $sender->update();

        $r_amt = $recipient->wallet;

        $r_amt = $r_amt + $request->amount;

        $recipient->wallet = $r_amt;

        $recipient->update();

        return response()->json(['status'=>true, "message"=>"Fund Transfer was successful "]);

    }


    public function transactions(){
        $records = Transaction::orderBy("id")->where("user_id",auth('api')->id())->limit(15)->get()->toArray();
        return response()->json($records);
    }


    public function createPaymentIntents(Request $request)
    {
        Stripe::setApiKey(Setting::find(1)->stripe_api_key);
        $charge = PaymentIntent::create(
            [
                'amount' => $request->amount * 100,
                'currency' => strtolower(Setting::find(1)->currency),
            ]
        );
        if(isset($charge->client_secret)){
            $user =  User::find(auth('api')->id());
            $data = [
                "user_id" => auth('api')->id(),
                "amount" => $request->amount,
                "status" => "PENDING",
                "payment_gateway"=>"STRIPE",
                "date"=> date("Y-m-d"),
                "transaction_token"=>$charge->client_secret,
                "transaction_date"=>date("Y-m-d"),
                "firstname"=> $user->firstname,
                "lastname" => $user->lastname,
                "country" =>Country::where("id",$user->country_id)->first()->code,
                "state"=>State::where("id",$user->state_id)->first()->name,
                "phoneno"=>$user->phoneno
            ];
            TopupTransaction::create($data);

            $data['secret'] = $data['transaction_token'];

            return response()->json($data);
        }else{
            return response()->json(['status'=>false,"error"=>"Unable to generate payment, Please try again"]);
        }
    }


    public function update_top_transaction(Request $request){

        $intent = TopupTransaction::where("transaction_token",$request->intent_secret)->first();

        if($request->status == "COMPLETE"){

            $intent->status = "COMPLETE";

            $intent->update();

            $user = User::find($intent->user_id);

            $transaction = new Transaction;

            $transaction->mode = $intent->payment_gateway;

            $transaction->amount =  $intent->amount;

            $transaction->user_id =  $intent->user_id;

            $transaction->recipient_id = $intent->user_id;

            $transaction->trans_type = "TOP UP";

            $transaction->sign = "+";

            $transaction->transaction_date = date("Y-m-d");

            $transaction->description = "TOP UP";

            $transaction->currency = Setting::find(1)->currency;

            $transaction->wallet_amt_before =  $user->wallet;

            $transaction->wallet_amt_after = $user->wallet +$intent->amount;

            $transaction->save();

            // lets update the user wallet

            $wallet = $user->wallet;

            $wallet = $wallet + $intent->amount;

            $user->wallet = $wallet;

            $user->update();

        }else{
            $intent->status = "FAILED";
            $intent->update();
        }

        return response()->json([
            "status"=>true,
            "data"=>"Payment Logs has been updated successfully"
        ]);

    }

    public function list_plan(){
        $plan = Plan::where("status","Active")->orderBy("amount")->get()->toArray();
        return response()->json($plan);
    }


    public function subscribe_to_plan(Request $request){

        $user = User::find(auth('api')->id());
        $plan = Plan::find($request->plan);
        $amount = $plan->amount;
        $user_wallet = $user->wallet;
        if($amount > $user_wallet){
            return response()->json([
                'status'=>false,
                'error'=>"Insufficient Fund in Wallet, Please top up your account to continue"
            ]);
        }
        
        if($amount < 1 && $user->as_used_free == 1) {
            return response()->json([
                'status'=>false,
                'error'=>"You can only subscribe to the Free plan once, Please change your subscription plan to continue"
            ]);
        }

        $transaction = new Transaction;

        $transaction->amount =  $amount;

        $transaction->user_id =  $user->id;

        $transaction->recipient_id = $user->id;

        $transaction->trans_type = "SUBSCRIPTION";

        $transaction->mode = "WALLET";

        $transaction->sign = "-";

        $transaction->transaction_date = date("Y-m-d");

        $transaction->description = "SUBSCRIPTION";

        $transaction->currency = Setting::find(1)->currency;

        $transaction->wallet_amt_before =  $user->wallet;

        $transaction->wallet_amt_after = $user->wallet - $amount;

        $transaction->save();
        $subscription_ends  = 0;
        $subscription_start =0;
        if($user->substatus == false) {
            if ($plan->type == "WEEKLY") {
                $subscription_start = time();
                $subscription_ends = strtotime("+ ".$plan->no_of_type." week");
            } else if ($plan->type == "DAILY") {
                $subscription_start = time();
                $subscription_ends = strtotime("+ ".$plan->no_of_type." hours");
            } else if ($plan->type == "MONTHLY") {
                $subscription_start = time();
                $subscription_ends = strtotime("+ ".$plan->no_of_type." month");
            } else if ($plan->type == "YEARLY") {
                $subscription_start = time();
                $subscription_ends = strtotime("+ ".$plan->no_of_type." year");
            }else if($plan->type == "HOURLY"){
                $subscription_start = time();
                $subscription_ends = strtotime("+ ".$plan->no_of_type." hour");
            }
        }else{
            if ($plan->type == "WEEKLY") {
                $subscription_start = time();
                $subscription_ends = strtotime("+ ".$plan->no_of_type." week",$user->subscription_expired_timestamp);
            } else if ($plan->type == "DAILY") {
                $subscription_start = time();
                $subscription_ends = strtotime("+ ".$plan->no_of_type." hours",$user->subscription_expired_timestamp);
            } else if ($plan->type == "MONTHLY") {
                $subscription_start = time();
                $subscription_ends = strtotime("+ ".$plan->no_of_type." month",$user->subscription_expired_timestamp);
            } else if ($plan->type == "YEARLY") {
                $subscription_start = time();
                $subscription_ends = strtotime("+ ".$plan->no_of_type." year",$user->subscription_expired_timestamp);
            }else if($plan->type == "HOURLY"){
                $subscription_start = time();
                $subscription_ends = strtotime("+ ".$plan->no_of_type." hours",$user->subscription_expired_timestamp);
            }
        }
        $new_wallet_amt = $user_wallet - $amount;

        $user->wallet = $new_wallet_amt;
        $user->subscription_expired_timestamp = $subscription_ends;
        $user->subscription_begin_timestamp = $subscription_start;
        
        if($amount < 1){
            $user->as_used_free = 1;
        }
        
        $user->update();

        //save transaction logs
        return response()->json([
            'status'=>true,
            'message'=>$plan->name ." Subscription was successful"
        ]);
    }


    public function programs(){
        //where("day",date('l'))->
        $pro = ProgrammeSchedule::orderBy("from","ASC")->get()->toArray();
        return response()->json($pro);
    }


    public function checkUserSub(Request $request){
        $code_status = true;
        $sub_status = true;
        $settings = Setting::find(1);
        $user = User::find(auth('api')->id());

        if($request->get('code') != $user->device_key){
            $code_status = false;
        }

        if($settings->access_mode == "FREE"){
            $sub_status = true;
        }else{
            $sub_status = $user->substatus;
        }

        return response()->json(
            ['substatus'=>$sub_status,'code_status'=>$code_status,'status'=>$user->expirymessage]
        );

    }

    public function movie_request(Request $request){
        $movie_request = new MovieRequest;
        $movie_request->title = $request->title;
        $movie_request->request_date = date('Y-m-d');
        $movie_request->request_type = $request->request_type;
        $movie_request->user_id = auth('api')->id();
        $movie_request->status = "PENDING";
        $movie_request->save();
        return response()->json(['status'=>true,'message'=>'Your Request has been sent successfully']);

    }


    public function settings(Request $request){
        $settings = Setting::find(1);

        if($request->email && $request->password){
            $credentials = request(['email', 'password']);
            if (! $token = auth('api')->attempt($credentials)) {
                $login_resp = ['status'=>false, 'error' => 'Invalid Email Address or Password'];
            }else{
                $user = auth('api')->user();
                $digits = 4;
                $device_key =  str_pad(rand(0, pow(10, $digits)-1), $digits, '0', STR_PAD_LEFT);
                $up = User::find($user->id);
                $up->device_key = $device_key;
                if(!empty($request->device_push_token)){
                    $up->device_push_token = $request->device_push_token;
                }
                $up->save();
                $login_resp=  [
                    'status'=>true,
                    'access_token' => $token,
                    'token' => $token,
                    'token_type' => 'bearer',
                    'expires_in' => auth('api')->factory()->getTTL() * 60,
                    'user'=>User::find(auth('api')->id())->toArray(),
                    "transactions"=>Transaction::orderBy("id","DESC")->where("user_id",auth('api')->id())->limit(15)->get()->toArray()
                ];
            }

        }else{
            $login_resp = ['status'=>false, 'error' => 'Invalid Email Address or Password'];
        }
        return response()->json(["settings"=>$settings,'login'=>$login_resp]);
    }

    public function current_program(){
        $today = date('l');
        $time = str_replace(":","",date("H:i"));
        $now = ProgrammeSchedule::whereRaw("'$time' BETWEEN from_number AND to_number")->where("day",$today)->get()->first();
        if($now) {
            $now_program_ends = $now->to_number + 1;
            $next = ProgrammeSchedule::whereRaw("'$now_program_ends' BETWEEN from_number AND to_number")->where("day", $today)->get()->first();
            if($next) {
                return response()->json(["now" => $now->toArray(), "next" => $next->toArray(), 'status'=>User::find(auth('api')->id())->expirymessage]);
            }else{
                return response()->json(["now" => $now->toArray(), "next" =>['title'=>'Not Available'], 'status'=>User::find(auth('api')->id())->expirymessage]);
            }
        }else{
            return response()->json(["now" => ['title'=>'Not Available'], "next" =>['title'=>'Not Available'],'status'=>User::find(auth('api')->id())->expirymessage]);
        }
    }

    public function load_comments($post_id){
        $comments = PostComment::where('post_id',$post_id)->where('status','PUBLISHED')->orderBy('id','DESC')->limit(20)->get();
        return response()->json($comments->toArray());
    }

    public function notifications(){
        $notis = PushMessage::orderBy("id","DESC")->get()->toArray();
        return response()->json($notis);
    }

    public function post_comments(Request $request){
        $post_id = $request->post_id;
        $comment = $request->comment;
        PostComment::create(
            [
                'post_id'=>$post_id,
                'user_id'=>auth('api')->id(),
                'content_html'=>$comment,
                'status'=>'PUBLISHED', //PENDING
                'post_date'=>date('Y-m-d'),
                'timestamp'=>time(),
            ]
        );
        return response()->json(['status'=>true,'message'=>'Comment has been posted successfully']);
    }


    public function forgot_my_password(Request $request){
        $email = $request->email;

        $user =User::where("email",$email)->first();
        if($user){
            //send email to the user
            $user->remember_token = sha1(md5(time()));
            $user->update();
            Mail::to($user->email)->send(new PasswordResetMail($user));
        }

        return response()->json([
            'status'=>true,
            'message' => 'A link to reset your password has been sent your mailbox or spam, if the email entered exist',
        ], Response::HTTP_OK);

    }
    
    
    
    public function delete_account(Request $request){
        
        $password = $request->get('password');
        
        $user_id = auth('api')->id();
        
        $user = User::find($user_id);
        
        if (!Hash::check($password, $user->password)) {
             return response()->json([
            'status'=>false,
            'message' => 'Invalid Password, Please check and try again',
        ], Response::HTTP_OK);
        }
        
    
        $role = $user->role;
        $user->transactions()->delete();
        $user->topupTransactions()->delete();
        $user->movieRequests()->delete();
        $user->postComments()->delete();
        $user->delete();
        return response()->json([
            'status'=>true,
            'message' => 'Your account has been deleted successfully!',
        ], Response::HTTP_OK);
        
        
      
        
        
    }
    

}
