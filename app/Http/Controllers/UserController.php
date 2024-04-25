<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Hash;

class UserController extends Controller
{
    public function lists(){
        $data = [
            'users'=>User::where('role','ADMIN')->get(),
            'page_title'=>'User List(s)',
        ];

        return view('admin.user.lists',$data);
    }


    public function delete_users($id){
        $user = User::find($id);
        $role = $user->role;
        $user->transactions()->delete();
        $user->topupTransactions()->delete();
        $user->movieRequests()->delete();
        $user->postComments()->delete();
        $user->delete();
        if($role == "SUBSCRIBER"){
            return redirect()->route("subscribers.lists")->with('success','User has been deleted successfully');
        }
        return redirect()->route("users.lists")->with('success','User has been deleted successfully');
    }

    public function edit(Request $request, $id){
        if($request->method() == "POST"){
            $data = $request->only(['firstname','lastname','password','email','phoneno']);
            $user = User::find($id);
            if(empty($data['password']) || $data['password']==""){
                unset($data['password']);
            }else{
                $data['password'] = bcrypt( $data['password']);
            }
            foreach ($data as $key=>$value){
                $user->$key = $value;
            }
            $user->update();
            if($user->role == "SUBSCRIBER"){
                return redirect()->route("subscribers.lists")->with('success','User has been updated successfully');
            }
            return redirect()->route("users.lists")->with('success','User has been updated successfully');
        }else{
            $data = [
                'user'=>User::find($id),
                'page_title'=>'Update '.User::find($id)->role,
            ];

            return view('admin.user.edit',$data);
        }
    }

    public function add(Request $request){
        if($request->method() == "POST") {
            $this->validate($request,
                [
                    'firstname'=>'required',
                    'lastname'=>'required',
                    'username'=>'required|unique:users,username',
                    'email' => 'required|string|email|max:100|unique:users,email',
                    'password' => 'required|string|min:6',
                    'phoneno'=>'required',
                ]
            );
            $data = $request->only(['firstname', 'lastname','username' ,'password', 'email', 'phoneno']);
            $data['password'] = Hash::make( $data['password']);
            $data['role'] = "ADMIN";
            $user = new User;
            foreach ($data as $key=>$value){
                $user->$key = $value;
            }
            $user->save();
            return redirect()->route("users.lists")->with('success','User has been created successfully');
        }
        $data = [
            'page_title'=>'Add New User',
            'user'=>new User
        ];
        return view('admin.user.add',$data);
    }



}
