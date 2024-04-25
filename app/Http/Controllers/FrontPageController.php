<?php

namespace App\Http\Controllers;

use App\Country;
use App\Plan;
use App\Post;
use App\PostComment;
use App\Setting;
use App\State;
use App\Transaction;
use App\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Stripe\Checkout\Session;
use Stripe\Stripe;
use Validator;

class FrontPageController extends Controller
{
    public function index(){
        $data = [];
        $data['plans'] = Plan::where('status','ACTIVE')->where('show_homepage',1)->limit(3)->get();
        $data['active_link'] = "home";
        $data['page_title'] = "Blupikhd";
        $data['data'] = $data;
        return view('frontend.homepage', $data);
    }

    public function blog(){
        $data = [];
        $data['active_link'] = "blog";
        $data['page_title'] = "News";
        $data['popularpost'] =  Post::orderBy("total_view",'DESC')->where("status","PUBLISHED")->limit(1)->get();
        $data['posts'] = Post::orderBy("id","DESC")->where("status","PUBLISHED")->limit(12)->get();
        $data['data'] = $data;
        return view('frontend.blog', $data);
    }

    public function view_blog($slug){
        $data = [];
        $data['post'] = Post::where('slug',$slug)->first();
        if(!$data['post']){
            return redirect()->route('blog');
        }
        $data['active_link'] = "blog";
        $data['page_title'] = $data['post']->title;
        $data['data'] = $data;
        return view('frontend.view_blog', $data);
    }


    public function comment(Request $request, $post_id){
        $comment = $request->comment;
        PostComment::create(
            [
                'post_id'=>$post_id,
                'user_id'=>auth('frontenduser')->id(),
                'content_html'=>$comment,
                'status'=>'PUBLISHED',
                'post_date'=>date('Y-m-d'),
                'timestamp'=>time(),
            ]
        );
        $post = Post::find($post_id);
        return redirect()->to($post->permalink);
    }

    public function contact(){
        $data = [];
        $data['active_link'] = "contact";
        $data['page_title'] = "Our Contacts";
        $data['data'] = $data;
        return view('frontend.contact', $data);
    }

    public function faqs(){
        $data = [];
        $data['active_link'] = "faqs";
        $data['page_title'] = "Faqs";
        $data['data'] = $data;
        return view('frontend.faqs', $data);
    }

    public function chooseplan($plan_id){
        if(auth('frontenduser')->check()){
            return redirect()->route('user.wallet');
        }
        session()->put('plan_id',$plan_id);
        return redirect()->route('registration.create_account');
    }

    public function signupform(){
        $data = [];
        $data['active_link'] = "signup";
        $data['active_tabs'] = "1";
        $data['page_title'] = "Sign Up";
        $data['plans'] = Plan::where('status','ACTIVE')->where('show_homepage',1)->limit(3)->get();
        $data['data'] = $data;
        return view('frontend.registration', $data);
    }

    public function create_account(Request $request){
        if($request->method() == "POST"){
            $validated_array = [
                'firstname' => 'required|string|between:2,50',
                'lastname' => 'required|string|between:2,50',
                'username' =>'required|string|max:50|unique:users,username',
                'email' => 'required|string|email|max:100|unique:users,email',
                'password' => 'required|string|min:6',
                'phoneno' => 'required|min:11|max:15',
                'country_id'=>'required|min:1|max:300',
                'state_id'=>'required|min:1|max:300',
            ];
            $this->validate($request,$validated_array);
            $data = $request->only(array_keys($validated_array));

            if(isset($request->device_push_token)){
                $data['device_push_token'] = $request->device_push_token;
            }

            $digits = 4;
            $data['device_key'] =  str_pad(rand(0, pow(10, $digits)-1), $digits, '0', STR_PAD_LEFT);

            $data['role'] = "SUBSCRIBER";

            $data['country_id'] = Country::where('id',$request->country_id)->first()->id;
            $data['state_id'] = State::where('name',$request->state_id)->first()->id;

            $data['password'] = Hash::make($request->password);

            $user = User::create($data);

            event(new Registered($user));

            $user["email_verified_at"] = "2021-11-09 17:09:09";

            auth('frontenduser')->loginUsingId($user->id);

            return redirect()->route('registration.add_funds_to_wallet')->with('success','Registration Successful');
        }
        if(auth('frontenduser')->check()){
            return redirect()->route('registration.add_funds_to_wallet')->with('success','Subscribe Now');
        }
        $data = [];
        $data['active_link'] = "signup";
        $data['active_tabs'] = "2";
        $data['page_title'] = "Sign Up";
        $data['plans'] = Plan::where('status','ACTIVE')->get();
        $data['data'] = $data;
        return view('frontend.registration_form', $data);

    }



    public function add_funds_to_wallet(Request $request){
        if($request->method() == "POST"){
            Stripe::setApiKey(Setting::find(1)->stripe_api_key);
            $plan = Plan::find(session()->get('plan_id'));
            $session = Session::create(
                [
                    'line_items' => [[
                        'price_data' => [
                            'currency' => 'usd',
                            'product_data' => [
                                'name' => 'Blupikhd '.ucwords(strtolower($plan->type))." Subscription Plan",
                            ],
                            'unit_amount' => ($plan->amount * 100),
                        ],
                        'quantity' => 1,
                    ]],
                    'mode' => 'payment',
                    'success_url' => route('stripe.success'),
                    'cancel_url' => route('stripe.cancel'),
                ]
            );
            return redirect()->to($session->url);
        }
        $data = [];
        $data['active_link'] = "signup";
        $data['active_tabs'] = "3";
        $data['page_title'] = "Sign Up";
        $data['plans'] = Plan::where('status','ACTIVE')->get();
        $data['data'] = $data;
        return view('frontend.add_funds_to_wallet_form', $data);
    }

    public function success(){
        $data = [];
        $data['active_link'] = "signup";
        $data['active_tabs'] = "4";
        $data['page_title'] = "Sign Up";
        $data['data'] = $data;
        return view('frontend.registration_success', $data);
    }

    public function loginprocess(Request $request){
        $credentials = request(['email', 'password']);
        if (! $token = auth('frontenduser')->attempt($credentials)) {
            return response()->json(['status'=>false, 'error' => 'Invalid Email Address or Password'], 200);
        }

        $user = auth('frontenduser')->user();
        $digits = 4;
        $device_key =  str_pad(rand(0, pow(10, $digits)-1), $digits, '0', STR_PAD_LEFT);

        $up = User::find($user->id);
        $up->device_key = $device_key;
        if(!empty($request->device_push_token)){
            $up->device_push_token = $request->device_push_token;
        }

        $up->save();

        return response()->json([
            'status'=>true,
            'user'=>User::find(auth('frontenduser')->id())->toArray(),
            'link'=>route('user.dashboard')
        ]);
    }

    public function logout(){
        if(auth('frontenduser')->check()) {
            auth('frontenduser')->logout();
        }
        return redirect()->route('index');
    }


    public function topupwallet(){
        $user = auth('api')->user();
        auth('frontenduser')->login($user);
        return redirect()->route('user.wallet',['mobiletopup'=>1]);
    }


    public function terms(){
        $data = [];
        $data['active_link'] = "";
        $data['page_title'] = "Terms and Condition";
        $data['data'] = $data;
        return view('frontend.terms', $data);
    }

    public function partners(){
        $data = [];
        $data['active_link'] = "partners";
        $data['page_title'] = "Partners";
        $data['data'] = $data;
        return view('frontend.partners', $data);
    }


}


