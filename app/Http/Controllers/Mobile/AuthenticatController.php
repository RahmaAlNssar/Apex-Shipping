<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Traits\ResponseTrait;
class AuthenticatController extends Controller
{
    use ResponseTrait;
    public function login(Request $request)
    {

        $validate = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
            'device_token' => 'required',
            'code' => 'required'
        ]);
        if ($validate) {

            $admin = Admin::where('token', $request->device_token)->first();
            $code = Code::where('code', $request->code)->first();
            if (!$admin || !Hash::check($request->password, $admin->password)) {

                return $this->returnError('Login invalid', 503);
            } elseif (!empty($code->expired_at)) {
                return $this->returnError('code expired', 503);
            } else {

                $admin->codes()->where('code', $request->code)->update(['expired_at' => now()]);
                $token = $admin->createToken($request->device_token)->plainTextToken;

                return $this->returnData($token, true, 200);
            }
        } else {
            return $this->returnErrorData(false, $validate, 422);
        }
    }

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
