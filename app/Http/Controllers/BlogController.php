<?php

namespace App\Http\Controllers;

use App\Category;
use App\Post;
use App\PostComment;
use App\PostTag;
use App\Tag;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Setting;
use Intervention\Image\Facades\Image;
use App\Logic\Providers\FacebookRepository;

class BlogController extends Controller
{
    protected $facebook;
    
     public function __construct()
    {
        $this->facebook = new FacebookRepository();
    }
    
    public function lists()
    {
        $posts = Post::orderby('id', 'desc')->get();
        $data = [
            'posts'=>$posts
        ];
        return view('admin.blog.post.list', $data);
    }


    public function create()
    {
        $post = new Post();
        $tags = Tag::pluck('name', 'id');
        $post_tag = [];
        $categories = Category::pluck('name', 'id');
        $token = Setting::find(1)->facebook_token;
        $pages = [];//$this->facebook->getPages($token);
        return view('admin.blog.post.form', compact('post', 'tags', 'post_tag', 'categories','pages'));
    }


    public function edit($id)
    {
        $post = Post::find($id);
        $tags = Tag::pluck('name', 'id');
        $post_tag = PostTag::where('post_id', '=', $id)->pluck('tag_id')->toArray();
        $categories = Category::pluck('name', 'id');
        $token = Setting::find(1)->facebook_token;
        $pages = [];//$this->facebook->getPages($token);
        return view('admin.blog.post.form', compact('post', 'tags', 'post_tag', 'categories','pages'));
    }


    public function store(Request $request)
    {

          set_time_limit(0);
          ini_set('memory_limit', '1024M');

        //dd($request->all());
        //unique: tableName, columnName
        //validate alert message set in /resources/lang/en/validation.php
        $this->validate($request, [
            'postTitle' => 'required|unique:posts,title,' . $request['id'],
            'postSlug' => 'unique:posts,slug,' . $request['id'],
        ]);
        //storeType: set create or edit for this post.
        if($request['storeType'] === 'create'){
            $post = new Post;
        }else if($request['storeType'] === 'edit') {
            $post = Post::find($request['id']);
        }

        //base post value (required values)
        $post->title   = $request['postTitle'];
        //$post->content_text = $request['postTextContent'];
        //$post->content_html = $request['postHtmlContent'];
        $post->excerpt = $request['postExcerpt'];

        //postStatus: if postStatus is empty, default status is 'DRAFT'
        if(empty($request['postStatus']))  {
            $post->status = 'DRAFT';
        }else {
            $post->status = $request['postStatus'];
        }

        //postSlug: if postSlug is empty, use postTitle
        if(empty($request['postSlug']))  {
            $post->slug = Str::slug($request['postTitle']);
        }else {
            $post->slug = Str::slug($request['postSlug']);
        }

        //postCategory: if postCategory is not empty, save it.
        if(!empty($request['postCategory'])) {
            $post->category = $request['postCategory'];
        }

        //postImage: if has image file, save and move to /public/img
        if ($request->hasFile('postImage')) {
            $image = $request->file('postImage');
            $filename = 'image_' . time() . '_' . $image->hashName();
            $image = $image->move(public_path('post_image'), $filename);
            $post->image = $filename;
        }

        //seoTitle: if seoTitle is empty, use postTitle
        if(empty($request['seoTitle'])) {
            $post->seo_title = $request['postTitle'];
        }else {
            $post->seo_title = $request['seoTitle'];
        }

        //seoDesc: if seoDesc is empty, use postExcerpt
        if(empty($request['seoDesc'])) {
            $post->seo_description = $request['postExcerpt'];
        }else {
            $post->seo_description = $request['seoDesc'];
        }

        //seoKeywords: if seoKeywords is empty and has postTag, use postTag
        if(empty($request['seoKeywords']) && !empty($request['postTag'])) {
            $seoKeywords = '';
            foreach ($request['postTag'] as $tag) {
                $tagName = Tag::where('id', '=', $tag)->value('name');
                $seoKeywords .= $tagName . ', ';
            }
            $post->seo_keywords = substr($seoKeywords, 0, -2);
        }else {
            $post->seo_keywords = $request['seoKeywords'];
        }


        //save summer note files
        $dom = new \DomDocument();
        $load = @$dom->loadHtml($request['postContent'], LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);

        if($load == false){
            if($request['storeType'] === 'create') {
                return redirect()->route('blog.create')->with('error', "Unable to create post, Invalid html content");
            }else{
                return redirect()->route('blog.edit',$request['id'])->with('error', "Unable to update post, Invalid html content");
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


       $post->content_html = $dom->saveHTML();

        $post->content_text = "  ";
        //save post
        $post->save();
        
        //lest post to facebook
        //$request['storeType'] !== 'edit' &&
        /*
        if( $post->status == "PUBLISHED" && isset($request->facebook_page)){
            
            $data = explode("#####",$request->facebook_page);
         
            if(!empty($post->image)){
                $face = $this->facebook->post($data[1],$data[0], $post->excerpt,$post->permalink ,[public_path("/post_image/".$post->image)]);
            }else{
              $face =   $this->facebook->post($data[1],$data[0], $post->excerpt,$post->permalink ,[]);
            }
        }
        */
        //save post and tag relationship(table:post_tag)
        if(empty($request['postTag'])) {
            $post->tags()->sync([]);
        }else{
            //if request tag not in table:tag, insert it.
            $postTags = [];
            foreach ($request['postTag'] as $tag) {
                $result = Tag::where('id', '=', $tag)->count();
                if ($result == 0) {
                    $postId = Tag::insertGetId(['name' => $tag]);
                }else{
                    $postId = $tag;
                }
                array_push($postTags, $postId);
            }
            $post->tags()->sync($postTags);
        }

        //delect surplus tags
        Tag::whereNotIn('id', function($query){$query->select('tag_id')->from(with(new PostTag)->getTable());})->delete();

        if($request['storeType'] === 'create') {
            $extra_payload  = [
                "title"=>  $post->title,
                "body"=>$post->excerpt,
                "payload"=>json_encode([]),
                "device_ids"=>[],
                "no_of_device"=>0,
                "type"=>"topic",
                "status"=>"SENT",
            ];
            $payload = ['action'=>'CLICK','data'=>json_decode('{}'), "notification"=>$extra_payload];

            pushNotificationToTopic($payload, $post->title, $post->excerpt);

            return redirect()->route('blog.create')->with('success', "Post has been created successfully");
        }else{
            return redirect()->route('blog.edit',$request['id'])->with('success', "Post has been updated successfully");
        }
    }

    //posts-delete
    public function delete($id)
    {
        $post = Post::find($id);
        $post->delete();
        return redirect()->route('blog.lists')->with('success', "Post has been deleted successfully");
    }

    public function comments(){
        $comments = PostComment::orderby('id', 'desc')->get();
        $data = [
            'comments'=>$comments
        ];
        return view('admin.blog.post.commentlist', $data);
    }

    public function approved_comments($comment_id){
        $comment = PostComment::find($comment_id);
        $comment->status = "PUBLISHED";
        $comment->update();
        return redirect()->route('blog.comments')->with('success', "Post has been approved successfully");
    }


  public function delete_comments($comment_id){
        $comment = PostComment::find($comment_id);
        if($comment)
        {
            $comment->delete();
        }
        return redirect()->route('blog.comments')->with('success', "Comment has been deleted successfully");
    }

}
