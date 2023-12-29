<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Http\Requests\Seller\SellerEmailVerifyRequest;
use App\Http\Requests\Seller\SellerLoginRequest;
use App\Http\Requests\Seller\SellerRegisterRequest;
use App\Mail\VerifyMail;
use App\Models\Seller;
use App\Models\SellerAddress;
use App\Traits\ResponseTrait;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SellerAuthController extends Controller
{
    use ResponseTrait;

    public function register(SellerRegisterRequest $request)
    {
        DB::beginTransaction();
        try {
            $verification_token = random_int(100000, 999999);

            $seller = Seller::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'verification_token' => $verification_token,
            ]);

            $data = [
                'token' => $seller->createToken("seller-token")->plainTextToken,
            ];
            DB::commit();
            return $this->success('Seller register successful', $data, 200, false);
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::error('SellerAuthController : register() :' . $th->getMessage());
            return $this->fail("Something went wrong", 500);
        }
    }

    public function login(SellerLoginRequest $request)
    {
        DB::beginTransaction();
        try {
            $seller = Seller::where('email', $request->email)->first();

            if (!$seller || !Hash::check($request->password, $seller->password)) {
                return $this->fail("Email or password is not correct", 401);
            }

            $data = [
                'token' => $seller->createToken("seller-token")->plainTextToken,
            ];
            DB::commit();

            if ($seller->email_verified_at == null || $seller->email_verified_at == "") {
                return $this->success('Seller login successful', $data, 200, false);
            } else {
                return $this->success('Seller login successful', $data, 200, true);
            }


            return $this->success('Seller login successful', $data, 200, false);
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::error('SellerAuthController : login() :' . $th->getMessage());
            return $this->fail("Something went wrong", 500);
        }
    }


    public function requestVerificationToken()
    {
        /** @var \App\Models\Seller $seller **/
        DB::beginTransaction();
        try {
            $seller = Auth::user();
            if (!$seller) {
                return $this->fail("unauthorized", 401);
            }

            if ($seller->email_verified_at !== null && $seller->email_verified_at !== "") {
                return $this->fail("Already verify", 400);
            }

            $token_request_at = $seller->token_request_at;
            if ($token_request_at !== null) {
                $token_request_date = Carbon::createFromFormat('Y-m-d H:i:s', $token_request_at)->format('Y-m-d');
                $today = Carbon::now()->format('Y-m-d');
                if ($token_request_date == $today) {
                    $today_token_request_count = $seller->token_request_count;
                    if ($today_token_request_count >= 3) {
                        return $this->fail("too many attempt, please try tomorrow", 429);
                    }
                } else {
                    $seller->token_request_count = 0;
                    /** @var \App\Models\Seller $seller **/
                    $seller->save();
                }
            }

            $verification_token = random_int(100000, 999999);
            $seller->verification_token = Crypt::encryptString($verification_token);
            $seller->token_request_at = Carbon::now();
            /** @var \App\Models\Seller $seller **/
            $seller->increment('token_request_count', 1);
            $seller->save();


            //TODO::Send email
            Mail::to($seller->email)->send(new VerifyMail($verification_token));


            DB::commit();
            return $this->success("Send OTP successful", $verification_token, 200, false); //TODO::remove code
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::error('SellerAuthController : requestVerificationToken() :' . $th->getMessage());
            return $this->fail("Something went wrong", 500);
        }
    }

    public function verifyEmail(SellerEmailVerifyRequest $request)
    {
        /** @var \App\Models\Seller $seller **/
        $seller = Auth::user();
        if (!$seller) {
            return $this->fail("unauthorized", 401);
        }

        if ($seller->email_verified_at !== null && $seller->email_verified_at !== "") {
            return $this->fail("Already verified", 400);
        }

        DB::beginTransaction();
        try {
            $verifyAttemptAt = $seller->verify_attempt_at;

            if ($verifyAttemptAt !== null) {
                $verifyAttemptTime = Carbon::createFromFormat('Y-m-d H:i:s', $verifyAttemptAt)->format('YmdHis');
                $currentTime = Carbon::now()->format('YmdHis');

                if ($currentTime - $verifyAttemptTime < 60) {
                    $todayVerifyAttemptCount = $seller->verify_attempt_count;
                    if ($todayVerifyAttemptCount >= 5) {
                        return $this->fail("Too many attempt, please try again later", 429);
                    }
                } else {
                    $seller->verify_attempt_count = 0;
                    $seller->save();
                }
            }



            $decryptedOTP = Crypt::decryptString($seller->verification_token);
            if ($decryptedOTP !== $request->verification_token) {
                $seller->verify_attempt_at = Carbon::now();
                $seller->increment('verify_attempt_count', 1);
                $seller->save();
                DB::commit();
                return $this->fail("Verification fail", 401);
            } else {
                $seller->email_verified_at = Carbon::now();
                $seller->verify_attempt_at = null;
                $seller->verify_attempt_count = 0;
                $seller->save();
                DB::commit();
                return $this->success("Verify email successful");
            }
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::error('SellerAuthController : verifyEmail() :' . $th->getMessage());
            return $this->fail("Something went wrong", 500);
        }
    }
}
