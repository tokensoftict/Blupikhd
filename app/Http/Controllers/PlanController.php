<?php

namespace App\Http\Controllers;

use App\Category;
use App\Plan;
use App\Post;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;

class PlanController extends Controller
{

    public function lists()
    {
        $data = [
          "plans" =>    Plan::orderby('id')->get(),
          "page_title" => "List Subscription Plan"
        ];
        return view('admin.plan.lists', $data);
    }

    public function add(){
        $data = [
            "page_title" => "New Subscription Plan",
            "plan" => new Plan()
            ];
        return view('admin.plan.add', $data);
    }

    public function edit($plan_id){
        $data = [
            "page_title" => "Update Subscription Plan",
            "plan" => Plan::find($plan_id)
        ];
        return view('admin.plan.add', $data);
    }

    public function process(Request $request){
        $this->validate($request, [
            'name' => 'required',
            'type' => 'required',
            'amount' => 'required',
            'status' => 'required',
            'description' => 'required',
            'no_of_type' => 'required',
        ]);

        //storeType: set create or edit for this post.
        if($request['storeType'] === 'create'){
            $plan = new Plan;
        }else if($request['storeType'] === 'edit') {
            $plan = Plan::find($request['id']);
        }

        $plan->name = $request->name;
        $plan->type = $request->type;
        $plan->amount = $request->amount;
        $plan->status = $request->status;
        $plan->show_homepage = $request->show_homepage;
        $plan->no_of_type = $request->no_of_type;
        $plan->description = $request->description;

        //save summer note files
        $dom = new \DomDocument();
        $load = @$dom->loadHtml($request['postContent'], LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);

        if($load == false){
            if($request['storeType'] === 'create') {
                return redirect()->route('plan.new')->with('error', "Unable to create post, Invalid html content");
            }else{
                return redirect()->route('plan.edit',$request['id'])->with('error', "Unable to update post, Invalid html content");
            }
        }

        $images = $dom->getElementsByTagName('img');

        foreach ($images as $image) {
            $imageSrc = $image->getAttribute('src');
            /** if image source is 'data-url' */
            if (preg_match('/data:image/', $imageSrc)) {
                /** etch the current image mimetype and stores in $mime */
                preg_match('/data:image\/(?<mime>.*?)\;/', $imageSrc, $mime);
                $mimeType = $mime['mime'];
                /** Create new file name with random string */
                $filename = uniqid();

                /** Public path. Make sure to create the folder
                 * public/uploads
                 */
                $filePath = "/post_image/$filename.$mimeType";

                /** Using Intervention package to create Image */
                Image::make($imageSrc)
                    /** encode file to the specified mimeType */
                    ->encode($mimeType, 100)
                    /** public_path - points directly to public path */
                    ->save(public_path($filePath));

                $newImageSrc = asset($filePath);
                $image->removeAttribute('src');
                $image->setAttribute('src', $newImageSrc);
            }
        }

        $plan->web_description = $dom->saveHTML();
        $plan->save();

        if($request['storeType'] === 'create') {
            return redirect()->route('plan.new')->with('success', "Plan has been created successfully");
        }else{
            return redirect()->route('plan.edit',$request['id'])->with('success', "Plan has been updated successfully");
        }

    }

}
