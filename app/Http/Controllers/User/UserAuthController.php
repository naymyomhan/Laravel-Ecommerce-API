<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserEmailVerifyRequest;
use App\Http\Requests\UserLoginRequest;
use App\Http\Requests\UserRegisterRequest;
use App\Models\User;
use App\Traits\ResponseTrait;
use Carbon\Carbon;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserAuthController extends Controller
{
    use ResponseTrait;

    public function register(UserRegisterRequest $request)
    {
        DB::beginTransaction();
        try {
            $verification_token = random_int(100000, 999999);
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'verification_token' => $verification_token,
            ]);

            $data = [
                'token' => $user->createToken("user-token")->plainTextToken,
            ];
            DB::commit();
            return $this->success('User register successful', $data);
        } catch (\Throwable $th) {
            DB::rollBack();
            return $this->fail("Server Error : " . $th);
        }
    }

    public function login(UserLoginRequest $request)
    {
        DB::beginTransaction();
        try {
            $user = User::where('email', $request->email)->first();
            if (!$user || !Hash::check($request->password, $user->password)) {
                return $this->fail("Email or password is not correct", 401);
            }

            if ($user->email_verified_at == null || $user->email_verified_at == "") {
                $data = [
                    'token' => $user->createToken("user-token")->plainTextToken,
                    'verified' => false
                ];
            } else {
                $data = [
                    'token' => $user->createToken("user-token")->plainTextToken,
                    'verified' => true
                ];
            }
            DB::commit();
            return $this->success('User register successful', $data);
        } catch (\Throwable $th) {
            DB::rollBack();
            return $this->fail("Server Error : " . $th);
        }
    }

    public function requestVerificationToken()
    {
        DB::beginTransaction();
        try {
            $user = Auth::user();
            if (!$user) {
                return $this->fail("unauthorized", 401);
            }

            if ($user->email_verified_at !== null && $user->email_verified_at !== "") {
                return $this->fail("Already verify", 400);
            }

            $token_request_at = $user->token_request_at;
            if ($token_request_at !== null) {
                $token_request_date = Carbon::createFromFormat('Y-m-d H:i:s', $token_request_at)->format('Y-m-d');
                $today = Carbon::now()->format('Y-m-d');
                if ($token_request_date == $today) {
                    $today_token_request_count = $user->token_request_count;
                    if ($today_token_request_count >= 3) {
                        return $this->fail("too many attempt, please try tomorrow", 429);
                    }
                } else {
                    $user->token_request_count = 0;
                    /** @var \App\Models\User $user **/
                    $user->save();
                }
            }

            $random_otp = random_int(100000, 999999);
            $user->verification_token = Crypt::encryptString($random_otp);
            $user->token_request_at = Carbon::now();
            /** @var \App\Models\User $user **/
            $user->increment('token_request_count', 1);
            $user->save();
            DB::commit();
            return $this->success("Send OTP successful", $random_otp); //TODO::remove code
        } catch (\Throwable $th) {
            DB::rollBack();
            return $this->fail($th->getMessage() ? $th->getMessage() : "server error", 500);
        }
    }

    public function verifyEmail(UserEmailVerifyRequest $request)
    {
        /** @var \App\Models\User $user **/
        $user = Auth::user();
        if (!$user) {
            return $this->fail("unauthorized", 401);
        }

        if ($user->email_verified_at !== null && $user->email_verified_at !== "") {
            return $this->fail("Already verified", 400);
        }

        DB::beginTransaction();
        try {
            $verifyAttemptAt = $user->verify_attempt_at;

            if ($verifyAttemptAt !== null) {
                $verifyAttemptTime = Carbon::createFromFormat('Y-m-d H:i:s', $verifyAttemptAt)->format('YmdHis');
                $currentTime = Carbon::now()->format('YmdHis');

                if ($currentTime - $verifyAttemptTime < 60) {
                    $todayVerifyAttemptCount = $user->verify_attempt_count;
                    if ($todayVerifyAttemptCount >= 5) {
                        return $this->fail("Too many attempt, please try again later", 429);
                    }
                } else {
                    $user->verify_attempt_count = 0;
                    $user->save();
                }
            }



            $decryptedOTP = Crypt::decryptString($user->verification_token);
            if ($decryptedOTP !== $request->verification_token) {
                $user->verify_attempt_at = Carbon::now();
                $user->increment('verify_attempt_count', 1);
                $user->save();
                DB::commit();
                return $this->fail("Verification fail", 401);
            } else {
                $user->email_verified_at = Carbon::now();
                $user->verify_attempt_at = null;
                $user->verify_attempt_count = 0;
                $user->save();
                $data = ["verified" => true];
                DB::commit();
                return $this->success("Verify email successful", $data);
            }
        } catch (\Throwable $th) {
            DB::rollBack();
            return $this->fail("Server Error : " . $th);
        }
    }
}
