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

            // $user->save();

            $data = [
                'token' => $user->createToken("USER-TOKEN")->plainTextToken,
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
        try {
            $user = User::where('email', $request->email)->first();
            if (!$user) {
                return $this->fail("Email or password is not correct", 401);
            }

            if (!Auth::guard('user')->attempt($request->only(['email', 'password']))) {
                return $this->fail("Email or password is not correct", 401);
            }

            if ($user->email_verified_at == null || $user->email_verified_at == "") {
                $data = [
                    'token' => $user->createToken("USER-TOKEN")->plainTextToken,
                    'verified' => false
                ];
            } else {
                $data = [
                    'token' => $user->createToken("USER-TOKEN")->plainTextToken,
                    'verified' => true
                ];
            }
            return $this->success('User register successful', $data);
        } catch (\Throwable $th) {
            DB::rollBack();
            return $this->fail("Server Error : " . $th);
        }
    }

    public function verifyEmail(UserEmailVerifyRequest $request)
    {
        $user = Auth::user();
        if (!$user) {
            return $this->fail("unauthorized", 401);
        }

        if ($user->email_verified_at !== null && $user->email_verified_at !== "") {
            return $this->fail("Already verified", 400);
        }

        if ($user->verification_token !== $request->verification_token) {
            return $this->fail("Verification fail", 401);
        }

        DB::beginTransaction();
        try {
            $user->email_verified_at = Carbon::now();
            /** @var \App\Models\User $user **/
            $user->save();

            $data = [
                "verified" => true,
            ];

            DB::commit();
            return $this->success("Verify email successful", $data);
        } catch (\Throwable $th) {
            DB::rollBack();
            return $this->fail("Server Error : " . $th);
        }
    }
}
