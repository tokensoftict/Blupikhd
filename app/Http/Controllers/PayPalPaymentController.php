<?php
namespace App\Http\Controllers;

use App\Country;
use App\Setting;
use App\State;
use App\TopupTransaction;
use App\User;
use Illuminate\Http\Request;
use PayPal\Rest\ApiContext;
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Api\Agreement;
use PayPal\Api\Payer;
use PayPal\Api\Plan;
use PayPal\Api\PaymentDefinition;
use PayPal\Api\PayerInfo;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Api\Amount;
use PayPal\Api\Transaction;
use PayPal\Api\RedirectUrls;
use PayPal\Api\Payment;
use PayPal\Api\PaymentExecution;
use Illuminate\Support\Facades\Input;
use Redirect;
use URL;

class PayPalPaymentController extends Controller
{
    public function __construct()
    {
        $settings = Setting::find(1);

        $paypal_conf = [
            'client_id' =>  $settings->paypal_client_id,
            'secret' => $settings->paypal_client_secret,
            'settings' => array(
                'mode' => strtolower($settings->paypal_mode), //env('PAYPAL_MODE','sandbox'),
                'http.ConnectionTimeOut' => 30,
                'log.LogEnabled' => true,
                'log.FileName' => storage_path() . '/logs/paypal.log',
                'log.LogLevel' => 'ERROR'
            ),
        ];

        $this->_api_context = new ApiContext(new OAuthTokenCredential(
                $paypal_conf['client_id'],
                $paypal_conf['secret'])
        );
        $this->_api_context->setConfig($paypal_conf['settings']);
    }
    public function payWithpaypal()
    {
        $amountToBePaid = session()->get('amount');
        $payer = new Payer();
        $payer->setPaymentMethod('paypal');

        $item_1 = new Item();
        $item_1->setName('BLUPIKHD SUBSCRIPTION') /** item name **/
        ->setCurrency('USD')
            ->setQuantity(1)
            ->setPrice($amountToBePaid); /** unit price **/

        $item_list = new ItemList();
        $item_list->setItems(array($item_1));

        $amount = new Amount();
        $amount->setCurrency('USD')
            ->setTotal($amountToBePaid);
        $redirect_urls = new RedirectUrls();
        /** Specify return URL **/
        $redirect_urls->setReturnUrl(URL::route('paypal.status'))
            ->setCancelUrl(URL::route('paypal.status'));

        $transaction = new Transaction();
        $transaction->setAmount($amount)
            ->setItemList($item_list)
            ->setDescription('BLUPIKHD STREAMING SERVICE');

        $payment = new Payment();
        $payment->setIntent('Sale')
            ->setPayer($payer)
            ->setRedirectUrls($redirect_urls)
            ->setTransactions(array($transaction));
        try {

            $payment->create($this->_api_context);

        } catch (\PayPal\Exception\PPConnectionException $ex) {

            if (\Config::get('app.debug')) {

                session()->flash('error', 'Connection timeout');

                return back();

            } else {

                session()->flash('error', 'Some error occur, sorry for inconvenient');

                return back();

            }
        }

        foreach ($payment->getLinks() as $link) {
            if ($link->getRel() == 'approval_url') {
                $redirect_url = $link->getHref();
                break;
            }
        }

        //create a session for top up transaction
        $user = User::find(auth('frontenduser')->id());

        $data = [
            "user_id" => auth('frontenduser')->id(),
            "amount" => session()->get('amount'),
            "status" => "PENDING",
            "payment_gateway"=>"PAYPAL",
            "date" => date("Y-m-d"),
            "transaction_token" => $payment->getId(),
            "transaction_date" => date("Y-m-d"),
            "firstname" => $user->firstname,
            "lastname" => $user->lastname,
            "country" => Country::where("id", $user->country_id)->first()->code,
            "state" => State::where("id", $user->state_id)->first()->name,
            "phoneno" => $user->phoneno
        ];

        TopupTransaction::create($data);

        /** add payment ID to session **/
        session()->put('paypal_payment_id', $payment->getId());

        if (isset($redirect_url)) {
            /** redirect to paypal **/
            return Redirect::away($redirect_url);
        }

        session()->flash('error', 'Error generating redirect URL, Please try again');

        return back();
    }

    public function getPaymentStatus(Request $request)
    {
        /** Get the payment ID before session clear **/
        $payment_id = session()->get('paypal_payment_id');
        /** clear the session payment ID **/
        session()->forget('paypal_payment_id');

        if (empty($request->PayerID) || empty($request->token)) {
            session()->flash('error', 'Payment failed');
             return redirect()->route('user.wallet')->with('error', 'Payment failed !');
        }

        $payment = Payment::get($payment_id, $this->_api_context);

        $execution = new PaymentExecution();

        $execution->setPayerId($request->PayerID);
        /**Execute the payment **/
        $result = $payment->execute($execution, $this->_api_context);

        if ($result->getState() == 'approved') {

            session()->flash('success', 'Payment success');

            $intent = TopupTransaction::where("transaction_token",$payment_id)->first();

            $intent->status = "COMPLETE";

            $intent->update();

            $user = User::find($intent->user_id);

            $transaction = new \App\Transaction;

            $transaction->amount =  $intent->amount;

            $transaction->mode = 'PAYPAL';

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
                $plan = \App\Plan::find(session()->get('plan_id'));
                $amount = $plan->amount;
                $user_wallet = $user->wallet;

                $transaction = new \App\Transaction;

                $transaction->amount =  $amount;

                $transaction->user_id =  $user->id;

                $transaction->recipient_id = $user->id;

                $transaction->trans_type = "SUBSCRIPTION";

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

        }

        session()->flash('error', 'Error charging Card, Please try again');

        return redirect()->route('user.wallet')->with('error', 'Wallet Top up Was Unsuccessful !');
    }
}
