<?php

namespace App\Http\Controllers;

use App\Plan;
use App\Post;
use App\TopupTransaction;
use Illuminate\Support\Facades\DB;
use App\Country;
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

class HomeController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth',  ['except' => ['posts','forgotpassword']]);
    }

    public function index()
    {
        $data = [];
        $data['dashboard'] = "dashboard";
        $data['subs_count'] = User::where("role","SUBSCRIBER")->count();
        $wallet_total = User::select(DB::raw("SUM(wallet) as wallet "))->where("role","SUBSCRIBER")->pluck("wallet");
        foreach ($wallet_total as $wallet);
        $data['wallet_total'] = number_format($wallet,2);
        $data['plans'] = Plan::where("status",'ACTIVE')->count();
        $trans = TopupTransaction::select(DB::raw("SUM(amount) as amount "))->where("status","COMPLETE")->pluck("amount");
        foreach ($trans as $tran);
        $data['transacton'] =number_format($tran,2);
        $data['topups'] = TopupTransaction::orderByDesc("id")->limit(10)->get();
        $data['subscribers'] = User::where("role","SUBSCRIBER")->orderByDesc("id")->limit(10)->get();
        return view('admin.dashboard', $data);
    }


    public function posts($slug){
        $data = [];
        $posts = Post::where("slug",$slug)->get()->first();
        if( $posts){
            $data['post'] = $posts;
            return view('blog_post', $data);
        }
        return "Invalid Slug";
    }

    public function forgotpassword($passwordresettoken, Request $request){
        
        if($request->isMethod('post')){
           $user =  User::where('remember_token',$passwordresettoken)->first();
           if($user){
               $user->password = Hash::make($request->password);
               $user->remember_token = NULL;
               $user->update();
                return redirect()->route('forgotpassword','success');
           }
            
        }
        if($passwordresettoken == "success"){
        $data = [];
        $data['plans'] = Plan::where('status','ACTIVE')->where('show_homepage',1)->limit(3)->get();
        $data['active_link'] = "home";
        $data['page_title'] = "Reset Password";
        $data['user'] = false;
        $data['success'] = $passwordresettoken;
        $data['data'] = $data;
        return view('frontend.resetpassword', $data);
        }else{
        $data = [];
        $data['plans'] = Plan::where('status','ACTIVE')->where('show_homepage',1)->limit(3)->get();
        $data['active_link'] = "home";
        $data['page_title'] = "Reset Password";
        $data['user'] = User::where('remember_token',$passwordresettoken)->first();
        $data['data'] = $data;
        return view('frontend.resetpassword', $data);
        }
    }


    public function logout(){
        if(auth()->check()){
            auth()->logout();
        }
        return redirect()->route('dashboard');
    }
}
