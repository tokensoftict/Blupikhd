<?php

namespace App\Http\Controllers;

use App\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Str;

class CategoryController extends Controller
{

    public function lists()
    {
        $categories = Category::orderby('name')->get();
        return view('admin.blog.category.lists', compact('categories'));
    }

    public function store()
    {
        $request = Input::all();
        $parent_id = 0;
        $order = 1;
        $name = $request['name'];
        $slug = Str::slug($request['name']);
        $check = Category::where('name', '=', $name)->orWhere('slug', '=', $slug)->count();

        if($check > 0){
            $returnData['tpye'] = 'error';
            $returnData['message'] = 'cant to use' . $name ;
            return $returnData;
        }else{
            $returnData['id'] = Category::insertGetId(['parent_id' => $parent_id, 'order' => $order, 'name' => $name, 'slug' => $slug]);
            $returnData['parent_id'] = $parent_id ;
            $returnData['order'] = $order ;
            $returnData['name'] = $name ;
            $returnData['slug'] = $slug ;
            $returnData['type'] = 'create';
            return $returnData;
        }

    }

    public function edit($id)
    {
        $inputName = Input::get('name');
        $category = Category::find($id);
        $category->name = $inputName;
        $category->slug = Str::slug($inputName);
        $category->save();

        $returnData['id'] = $category->id;
        $returnData['name'] = $inputName ;
        $returnData['slug'] = Str::slug($inputName) ;
        $returnData['type'] = 'edit';

        return $returnData;
    }

    public function delete($id)
    {
        $category = Category::find($id);
        $category->delete();
        return redirect()->route('category.lists');
    }
}
