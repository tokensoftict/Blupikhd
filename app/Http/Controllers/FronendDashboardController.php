<?php

namespace App\Http\Controllers;

use App\MovieRequest;
use App\Plan;
use App\ProgrammeSchedule;
use App\PushMessage;
use App\Setting;
use App\Transaction;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Hash;

class FronendDashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('frontenduser');
    }

    public function dashboard(){
        $data = [];
        $data['page_title'] = "Home";
        $data['progs'] = ProgrammeSchedule::orderBy('from_number')->get();
        $data['notifications'] = PushMessage::orderBy('id','DESC')->limit(5)->get();
        $data['data'] = $data;
        return view('account.dashboard', $data);
    }

    public function profile(Request $request){
        if($request->method() == "POST"){
            $user = User::find(auth()->id());

            $user->firstname = $request->firstname;
            $user->lastname = $request->lastname;

            if(isset($request->email)){
                $user->email = $request->email;
            }

            if(!empty($request->current_password) && !empty($request->confirm_password) && !empty($request->password)){
                if(!Hash::check($request->current_password, $user->password)){
                    return redirect()->route('user.profile')->with('error','Current Password does not match, Please check and try again');
                }

                if($request->confirm_password != $request->password){
                    return redirect()->route('user.profile')->with('error','Current Password does not match confirm password');
                }

                $user->password = Hash::make( $request->password);
            }

           $user->update();
            return redirect()->route('user.profile')->with('success','Profile has been updated successfully');

        }
        $data = [];
        $data['page_title'] = "My Profile";
        $data['notifications'] = PushMessage::orderBy('id','DESC')->limit(5)->get();
        $data['profile'] = User::find(auth('frontenduser')->id());
        $data['data'] = $data;
        return view('account.profile', $data);
    }


    public function wallet(Request $request){
        $data = [];
        $data['page_title'] = "My Wallet";
        $data['notifications'] = PushMessage::orderBy('id','DESC')->limit(5)->get();
        $data['profile'] = User::find(auth('frontenduser')->id());
        $data['plans'] = Plan::where('status','ACTIVE')->get();
        $data['data'] = $data;
        $data['topup'] = $request->get('mobiletopup') ? true : false;
        return view('account.wallet', $data);
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

        $sender =  User::find(auth('frontenduser')->id());

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

        $transaction->mode = "WALLET";

        $transaction->sign = "-";

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

        $transaction->mode = "WALLET";

        $transaction->sign = "+";

        $transaction->transaction_date = date("Y-m-d");

        $transaction->description = "FUND TRANSFER";

        $transaction->currency = Setting::find(1)->currency;

        $transaction->wallet_amt_before =  $recipient_wallet_amt;

        $transaction->wallet_amt_after = $recipient_wallet_amt + $request->amount;

        $transaction->save();


        //lets perform the real transaction
        $sender =  User::find(auth('frontenduser')->id());

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


    public function subscribe_to_plan(Request $request){

        $user = User::find(auth('frontenduser')->id());
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
            } else if($plan->type == "HOURLY"){
                $subscription_start = time();
                $subscription_ends = strtotime("+ ".$plan->no_of_type." hour");
            }
        }else{
            if ($plan->type == "WEEKLY") {
                $subscription_start = time();
                $subscription_ends = strtotime("+ ".$plan->no_of_type." week",$user->subscription_expired_timestamp);
            } else if ($plan->type == "DAILY") {
                $subscription_start = time();
                $subscription_ends = strtotime("+ ".$plan->no_of_type." hour",$user->subscription_expired_timestamp);
            } else if ($plan->type == "MONTHLY") {
                $subscription_start = time();
                $subscription_ends = strtotime("+ ".$plan->no_of_type." month",$user->subscription_expired_timestamp);
            } else if ($plan->type == "YEARLY") {
                $subscription_start = time();
                $subscription_ends = strtotime("+ ".$plan->no_of_type." year",$user->subscription_expired_timestamp);
            } else if($plan->type == "HOURLY"){
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


    public function top_up_wallet(Request $request){
        if($request->amount){

            if($request->amount > 0) {
                session()->put('amount', $request->amount);
                if (isset($request->registration)) {
                    session()->put('registration', true);
                }
                if($request->payment_method == "paypal"){
                    return redirect()->route('paypal.payment');
                }else if($request->payment_method == "stripe"){
                    return redirect()->route('stripe.paymentform');
                }
                return redirect()->back()->with('error','Unable to commence payment : Invalid Payment Method');
            }else{
                //user is subscribing to free plan
                $plan = Plan::find(session()->get('plan_id'));

                $user = User::find(auth('frontenduser')->id());

                if($user->as_used_free == 1){
                    return redirect()->back()->with('error','You can only subscribe to the Free plan once, Please change your subscription plan to continue');
                }else{
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
                        } else if($plan->type == "HOURLY"){
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
                        } else if($plan->type == "HOURLY"){
                            $subscription_start = time();
                            $subscription_ends = strtotime("+ ".$plan->no_of_type." hour",$user->subscription_expired_timestamp);
                        }
                    }

                    $user->subscription_expired_timestamp = $subscription_ends;
                    $user->subscription_begin_timestamp = $subscription_start;
                    $user->as_used_free = 1;
                    $user->update();
                    if (isset($request->registration)) {
                        return redirect()->route('registration.success')->with('success', 'Registration Was Successful !');
                    }else{
                        dd('dont know what to do');
                    }
                }
            }
        }
        return redirect()->route('user.wallet');
    }


    public function movie_request(Request $request){
        if($request->method() == "POST"){

            $this->validate($request, [
                'title' => 'required',
                'request_type' => 'required',
            ]);

            $movie_request = new MovieRequest;
            $movie_request->title = $request->title;
            $movie_request->request_date = date('Y-m-d');
            $movie_request->request_type = $request->request_type;
            $movie_request->user_id = auth('frontenduser')->id();
            $movie_request->status = "PENDING";
            $movie_request->save();
            return back()->with('success','Your request has been logged successfully!..');
        }
        $data = [];
        $data['page_title'] = "Movie request";
        $data['profile'] = User::find(auth('frontenduser')->id());
        $data['notifications'] = PushMessage::orderBy('id','DESC')->limit(5)->get();
        $data['data'] = $data;
        return view('account.movie_request', $data);

    }


    public function load_programme_ajax(Request $request){
        $day = $request->day;
        $data = [];
        $data['page_title'] = "ajax";
        $progs = ProgrammeSchedule::where('day',$day)->orderBy('from_number')->get();
        $data['progs'] = $progs;
        return view('account.programmelist', $data);
    }

}
