<?php

namespace App\Http\Controllers;

use App\MovieRequest;
use App\ProgrammeSchedule;
use App\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function access_mode(Request $request){
        $data = Setting::find(1);
        if($request->method() == "POST"){
            $data->access_mode = $request->access_mode;
            $data->streamning_url = $request->streamning_url;
            $data->update();
            return redirect()->route('setting.access_mode')->with('success',"Access mode has been updated successfully!.");
        }else{
            return view('admin.settings.access_mode', compact('data'));
        }
    }

    public function schedule(){
        $data = ProgrammeSchedule::all();
        return view('admin.settings.programme_schedule', compact('data'));
    }


    public function form( Request $request, $id = false){
        $data = [];
        $movie_re = null;
        if($id !=false){
            $data = ProgrammeSchedule::find($id);
        }else{
            $data = new ProgrammeSchedule;

            if($request->movie_request_id){
                $request_id = $request->movie_request_id;
                $req = MovieRequest::find( $request_id);
                $data->title = $req->title;
                $data->description = $req->movie_description;
                $movie_re = $request_id;
            }



        }
        return view('admin.settings.schedule_form', compact('data','movie_re'));
    }

    public function process(Request $request){
        if(isset($request->id)){
            $prog = ProgrammeSchedule::find($request->id);
        }else{
            $prog = new ProgrammeSchedule();
        }

        if ($request->hasFile('programme_image')) {
            $image = $request->file('programme_image');
            $filename = 'image_' . time() . '_' . $image->hashName();
            $image->move(public_path('programme_image'), $filename);
            $prog->programme_image = $filename;
        }

        if(!isset($request->all_day)) {
            $prog->title = $request->title;
            $prog->day = $request->day;
            $prog->from = date("H:i", strtotime($request->from));
            $prog->to = date("H:i", strtotime($request->to));
            $prog->from_number = str_replace(":","",date("H:i", strtotime($request->from)));
            $prog->to_number = str_replace(":","",date("H:i", strtotime($request->to)));
            $prog->to_string = $request->to;
            $prog->from_string = $request->from;
            $prog->link = $request->link;
            $prog->status = $request->status;
            $prog->description = $request->description;
            $prog->save();
        }else{
            $days = ['Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday'];
            foreach($days as $day){
                $prog = new ProgrammeSchedule();
                if(isset($filename)){
                    $prog->programme_image = $filename;
                }
                $prog->title = $request->title;
                $prog->day = $day;
                $prog->status = $request->status;
                $prog->from = date("H:i", strtotime($request->from));
                $prog->to = date("H:i", strtotime($request->to));
                $prog->from_number = str_replace(":","",date("H:i", strtotime($request->from)));
                $prog->to_number = str_replace(":","",date("H:i", strtotime($request->to)));
                $prog->to_string = $request->to;
                $prog->link = $request->link;
                $prog->from_string = $request->from;
                $prog->description = $request->description;
                $prog->save();
            }
        }

        if($request->get('movie_request_id')){
            $request = MovieRequest::find($request->get('movie_request_id'));
            $request->status = "READY";
            $request->update();
        }

        if(isset($request->id)){
            return redirect()->route('setting.edit',$prog->id)->with('success', 'Programme has been updated successfully');
        }else {
            return redirect()->route('setting.form')->with('success', 'Programme has been added successfully');
        }
    }

    public function delete($id){
        $prog = ProgrammeSchedule::find($id);
        $prog->delete();
        return redirect()->route('setting.schedule')->with('success', 'Programme has been deleted successfully');
    }






    public function paypal(Request $request){
        $settings = Setting::find(1);
        if($request->method() == "POST"){
            $data = $request->only(Setting::$keys);
            foreach ($data as $key=>$d){
                $settings->$key = $d;
            }
            $settings->update();
            return redirect()->route('setting.paypal')->with('success', 'Settings has been saved successfully');
        }
        $data = [
            'settings'=> $settings
        ];
        return view('admin.settings.paypal',$data);
    }

    public function googleplay(Request $request){
        $settings = Setting::find(1);
        if($request->method() == "POST"){
            $data = $request->only(Setting::$keys);
            foreach ($data as $key=>$d){
                $settings->$key = $d;
            }
            $settings->update();
            return redirect()->route('setting.googleplay')->with('success', 'Settings has been saved successfully');
        }
        $data = [
            'settings'=> $settings
        ];
        return view('admin.settings.googleplay',$data);
    }

    public function appleplay(Request $request){
        $settings = Setting::find(1);
        if($request->method() == "POST"){
            $data = $request->only(Setting::$keys);
            foreach ($data as $key=>$d){
                $settings->$key = $d;
            }
            $settings->update();
            return redirect()->route('setting.googleplay')->with('success', 'Settings has been saved successfully');
        }
        $data = [
            'settings'=> $settings
        ];
        return view('admin.settings.appleplay',$data);
    }

    public function stripe(Request $request){
        $settings = Setting::find(1);
        if($request->method() == "POST"){
            $data = $request->all();
            unset($data['_token']);
            foreach ($data as $key=>$d){
                $settings->$key = $d;
            }
            $settings->update();
            return redirect()->route('setting.stripe')->with('success', 'Settings has been saved successfully');
        }
        $data = [
            'settings'=> $settings
        ];
        return view('admin.settings.stripe',$data);
    }

    public function ads_mananger(Request $request){
        if($request->method() == "POST"){
            $settings = Setting::find(1);
            $data = $request->all();
            unset($data['_token']);
            foreach($data as $key=>$value){
                $settings->$key = $value;
            }
            $settings->update();
            return redirect()->route('setting.ads_mananger')->with('success', 'Settings has been saved successfully');
        }
        $data = [];
        $data['settings'] = Setting::find(1);
        return view('admin.settings.ads_mananger',$data);
    }


    public function terms_condition(Request $request)
    {
        $settings = Setting::find(1);

        if($request->method() == "POST"){

            $settings->terms_condition = $request->terms_condition;

            $settings->update();
        }

        $data = [
            'settings'=> $settings
        ];

        return view('admin.settings.terms_condition',$data);
    }


    public function frontpagesettings(Request $request)
    {
        if($request->method() == "POST")
        {
           //process homeslidingbanner
            $sliders = [];
            $textentries = [];
            if($request->get('homeslidingbanner'))
            {
                $homeslidingbanner = $request->get('homeslidingbanner');

                foreach ($homeslidingbanner as $image)
                {
                    $sliders[] =  $image;
                }

            }

            if($request->file('homeslidingbanner'))
            {

                $homeslidingbanner = $request->file('homeslidingbanner');

                foreach ($homeslidingbanner as $image)
                {
                    $filename = 'image_' . time() . '_' . $image->hashName();
                    $image->move(public_path('slider_image'), $filename);
                    $sliders[] =  $filename;
                }

            }

            addFrontPageSettings('sliders',$sliders);
            //process App Icon Link

            $app_links =$request->only(['apple','google','appletv','amazontv']);

            foreach ($app_links as $key=>$links)
            {
                addFrontPageSettings($key,$links);
            }

            //process Social Media Icon Link

            $app_social_media_links = $request->only(['facebook','instagram','twitter','telegram','copyright']);

            foreach ($app_social_media_links as $key=>$app_social)
            {
                addFrontPageSettings($key,$app_social);
            }

            //process Homepage Text Content
            if($request->get('homepagetextentry'))
            {
                $homepagetextentry = $request->get('homepagetextentry');
                $homepagetextlogo = $request->file('homepagetextentry');

                $logo_index = 0;
                foreach ($homepagetextentry['text'] as $key=>$entry)
                {
                    if(!isset($homepagetextentry['logo'][$key])) {
                        $logo = $homepagetextlogo['logo'][$logo_index];
                        $filename = 'image_' . time() . '_' . $logo->hashName();
                        $logo->move(public_path('home_text_entry'), $filename);
                        $logo_index++;
                    }else{
                        $filename = $homepagetextentry['logo'][$key];
                    }
                    $textentries[] = [
                        'text' => $entry,
                        'display' => $homepagetextentry['display'][$key],
                        'logo' =>$filename,
                        'title'=> $homepagetextentry['title'][$key]
                    ];
                }

            }
            addFrontPageSettings('homepagetextentry',$textentries);
            return redirect()->route('setting.frontpagesettings')->with('success', 'Homepage Settings has been saved successfully');
        }
        $data = [];
        return view('admin.settings.frontpagesettings',$data);
    }


}
