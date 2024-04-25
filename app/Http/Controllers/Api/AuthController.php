<?php

namespace App\Http\Controllers\Api;

use App\Country;
use App\State;
use App\Transaction;
use App\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Validator;

class AuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login','register']]);
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        $credentials = request(['email', 'password']);
        if (! $token = auth('api')->attempt($credentials)) {
            return response()->json(['status'=>false, 'error' => 'Invalid Email Address or Password'], 200);
        }

        $user = auth('api')->user();
        $digits = 4;
        $device_key =  str_pad(rand(0, pow(10, $digits)-1), $digits, '0', STR_PAD_LEFT);

        $up = User::find($user->id);
        $up->device_key = $device_key;
        if(!empty($request->device_push_token)){
            $up->device_push_token = $request->device_push_token;
        }

        $up->save();
        return $this->respondWithToken($token);
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        $data = [
            "me"=>auth('api')->user(),
            "transactions"=>Transaction::orderBy("id","DESC")->where("user_id",auth('api')->id())->limit(15)->get()->toArray()
        ];
        return response()->json($data);
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(auth('api')->refresh());
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'status'=>true,
            'access_token' => $token,
            'token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60,
            'user'=>User::find(auth('api')->id())->toArray(),
            "transactions"=>Transaction::orderBy("id","DESC")->where("user_id",auth('api')->id())->limit(15)->get()->toArray()
        ]);
    }


    public function register(Request $request) {

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


        $validator = Validator::make($request->all(), $validated_array);


        if($validator->fails()){
            $feedback = "";
            $errors = $validator->errors()->toArray();
            foreach($errors as $key=>$error){
                $feedback.= implode("<br/>",$error)."<br/>";
            }
            return response()->json(['status'=>false,"error"=>$feedback], 200);
        }
        $data =  $validator->validated();


        if(isset($request->device_push_token)){
            $data['device_push_token'] = $request->device_push_token;
        }
        $digits = 4;
        $data['device_key'] =  str_pad(rand(0, pow(10, $digits)-1), $digits, '0', STR_PAD_LEFT);

        $data['role'] = "SUBSCRIBER";

        $data['country_id'] = Country::where('name',$request->country_id)->first()->id;
        $data['state_id'] = State::where('name',$request->state_id)->first()->id;

        $data['password'] = Hash::make($request->password);
        $user = User::create($data);

        event(new Registered($user));

        $user["email_verified_at"] = "2021-11-09 17:09:09";


        $credentials = request(['email', 'password']);

        $token = auth('api')->attempt($credentials);

        return response()->json([
            'status'=>true,
            'message' => 'User successfully registered',
            'token' => $token,
            'device_key' =>  $data['device_key'],
            'user' => User::find($user['id'])->toArray(),
            "transactions"=>Transaction::orderBy("id","DESC")->where("user_id",auth('api')->id())->limit(15)->get()->toArray()
        ], Response::HTTP_OK);
    }


}
