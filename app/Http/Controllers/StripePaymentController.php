<?php

namespace App\Http\Controllers;

use App\Country;
use App\Plan;
use App\Setting;
use App\State;
use App\TopupTransaction;
use App\Transaction;
use App\User;
use Illuminate\Http\Request;
use Stripe\Charge;
use Stripe\Exception\ApiErrorException;
use Stripe\Stripe;

class StripePaymentController extends Controller
{
    public function __construct()
    {
        $this->middleware('frontenduser');
    }


    /**
     * success response method.
     *
     * @return \Illuminate\Http\Response
     */
    public function stripe()
    {
        return view('stripe.payment.form');
    }

    public function stripePost(Request $request)
    {
        $user =  User::find(auth('frontenduser')->id());
        $intent = TopupTransaction::where("transaction_token",$request->stripeToken)->first();
        if(!$intent) {
            $data = [
                "user_id" => auth('frontenduser')->id(),
                "amount" => session()->get('amount'),
                "status" => "PENDING",
                "date" => date("Y-m-d"),
                "payment_gateway"=>"STRIPE",
                "transaction_token" => $request->stripeToken,
                "transaction_date" => date("Y-m-d"),
                "firstname" => $user->firstname,
                "lastname" => $user->lastname,
                "country" => Country::where("id", $user->country_id)->first()->code,
                "state" => State::where("id", $user->state_id)->first()->name,
                "phoneno" => $user->phoneno
            ];
            TopupTransaction::create($data);
        }
        Stripe::setApiKey(Setting::find(1)->stripe_api_key);
        try {
            $charge = Charge::create([
                "amount" => session()->get('amount') * 100,
                "currency" => strtolower(Setting::find(1)->currency),
                "source" => $request->stripeToken,
                "description" => "Blupikhd Subscription Payment"
            ]);
        }catch(ApiErrorException $e){
            session()->flash('error', "Stripe Error : ".$e->getMessage());
            return back();
        }
       if(isset($charge->status) && $charge->status == "succeeded"){

           $intent = TopupTransaction::where("transaction_token",$request->stripeToken)->first();

           $intent->status = "COMPLETE";

           $intent->update();

           $user = User::find($intent->user_id);

           $transaction = new Transaction;

           $transaction->amount =  $intent->amount;

           $transaction->mode = 'STRIPE';

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

           session()->flash('success', 'Payment successful!');

           session()->remove('amount');

           if(session()->get('registration')) {
               session()->remove('registration');

               //subscribe user to plan right now..
               $user = User::find(auth('frontenduser')->id());
               $plan = Plan::find(session()->get('plan_id'));
               $amount = $plan->amount;
               $user_wallet = $user->wallet;

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
                       $subscription_ends = strtotime("+ ".$plan->no_of_type." hours");
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
               $user->update();


               return redirect()->route('registration.success')->with('success', 'Wallet Top up Was Successful !');
           }else {
               return redirect()->route('user.wallet')->with('success', 'Wallet Top up Was Successful !');
           }
       }else{
           session()->flash('error', 'Error charging Card, Please try again');
           return back();
       }

    }

}
