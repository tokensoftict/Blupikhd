<?php

namespace App\Http\Controllers;


use App\Logic\Providers\FacebookRepository;
use App\Setting;

class SocialController extends Controller
{
    protected $facebook;

    public function __construct()
    {
        $this->facebook = new FacebookRepository();
    }

    public function redirectToProvider()
    {
        return redirect($this->facebook->redirectTo());
    }

    public function handleProviderCallback()
    {
        if (request('error') == 'access_denied'){
            $accessToken = "Not set";
            return redirect()->route('setting.access_mode')->with('success','Error : Access Denied.... Please try again') ;
        }else{
             $accessToken = $this->facebook->handleCallback();
             $set = Setting::find(1);
             $set->facebook_token = $accessToken;
             $set->update();
             return redirect()->route('setting.access_mode')->with('success','Facebook Linkage with Page was successful') ;
        }
    }
}
