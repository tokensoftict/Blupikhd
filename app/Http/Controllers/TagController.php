<?php

namespace App\Http\Controllers;

use App\Tag;
use Illuminate\Support\Facades\Input;

class TagController extends Controller
{
    public function lists()
    {
        $tags = Tag::orderby('name')->get();
        return view('admin.blog.tag.lists', compact('tags'));
    }

    public function store()
    {
        $request = Input::all();
        $name = $request['name'];
        $check = Tag::where('name', '=', $name)->count();
        if($check > 0){
            $returnData['type'] = 'error';
            $returnData['message'] = 'already exists ' . $name ;
            return $returnData;
        }else{
            $returnData['id'] = Tag::insertGetId(['name' => $name,]);
            $returnData['name'] = $name ;
            $returnData['type'] = 'create';
            return $returnData;
        }

    }

    public function edit($id)
    {
        $inputName = Input::get('name');
        $tag = Tag::find($id);
        $tag->name = $inputName;
        $tag->save();

        $returnData['id'] = $tag->id;
        $returnData['name'] = $inputName ;
        $returnData['type'] = 'edit';

        return $returnData;
    }

    public function delete($id)
    {
        $category = Tag::find($id);
        $category->delete();
        return redirect()->route('tag.lists');
    }
}
