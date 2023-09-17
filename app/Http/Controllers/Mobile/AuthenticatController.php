<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterUserRequest;
use App\Models\User;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;
use Laravel\Sanctum\PersonalAccessToken;
use Nette\Schema\Message;

class AuthenticatController extends Controller
{
    use ResponseTrait;
    public function register(RegisterUserRequest $request)
    {
        try {
            $user = User::create(array_merge($request->except('password'),['password'=>Hash::make($request->password)]));
            $token = $user->createToken('auth_token')->plainTextToken;
            return $this->returnData($user, true,200);
        
        } catch (\Exception $e) {
            return $this->returnError($e->getMessage(), 500);
        }
    }
    public function login(Request $request)
    {
       try{
        $user = User::where('email', $request->email)->first();
           
        if (!$user || !Hash::check($request->password, $user->password)) {

            return $this->returnError('Login invalid', 503);
        } else {

            $token = $user->createToken($request->email)->plainTextToken;

            return $this->returnData($token, true, 200);
        }
       }catch(\Exception $e){
        return $this->returnError($e->getMessage(), 500);
       }

    }

    // method for user logout and delete token
  

    public function logout(Request $request)
    {
        try {
            $request->user()->currentAccessToken()->delete();
            return $this->returnSuccess('logout success', 200);
        } catch (\Exception $e) {
            return $this->returnError($e->getMessage(), 500);
        }


    }




}
