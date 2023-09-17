<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Traits\ResponseTrait;
use Carbon\Carbon;
use DB;
use Hash;
use Illuminate\Http\Request;
use Mail;

class ForgotPasswordController extends Controller
{
    use ResponseTrait;
    public function submitForgetPasswordForm(Request $request)
    {
        try {
            $request->validate([
                'email' => 'required|email|exists:users',
            ]);
            $user = User::where('email', $request->email)->first();
            $token = $user->createToken($request->email)->plainTextToken;
            DB::table('password_resets')->insert([
                'email' => $request->email,
                'token' => $token,
                'created_at' => Carbon::now(),
            ]);
            Mail::send('email.forgetPassword', ['token' => $token], function ($message) use ($request) {
                $message->to($request->email);
                $message->subject('Reset Password');

            });
            return $this->returnSuccess(__('messages.We have e-mailed your password reset link!'), 200);
        } catch (\Exception $e) {
            return $this->returnError($e->getMessage(), 500);
        }

    }

    public function submitResetPasswordForm(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users',
            'password' => 'required|string|min:3|confirmed',
            'password_confirmation' => 'required',
        ]);

        $updatePassword = DB::table('password_resets')
            ->where([
                'email' => $request->email,
                'token' => $request->token,
            ])

            ->first();
        if (!$updatePassword) {

            return $this->returnError('Login invalid', 503);
        }

        $user = User::where('email', $request->email)

            ->update(['password' => Hash::make($request->password)]);

        DB::table('password_resets')->where(['email' => $request->email])->delete();
        return $this->returnSuccess(__('messages.Your password has been changed!'), 200);

    }

}
