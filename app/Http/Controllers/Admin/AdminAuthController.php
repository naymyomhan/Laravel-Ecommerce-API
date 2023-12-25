<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AdminLoginRequest;
use App\Models\Admin;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AdminAuthController extends Controller
{
    use ResponseTrait;

    public function login(AdminLoginRequest $request)
    {
        DB::beginTransaction();
        try {
            $admin = Admin::where('email', $request->email)->first();
            if (!$admin) {
                return $this->fail("Email or password is not correct", 401);
            }

            if (!Auth::guard('admin')->attempt($request->only(['email', 'password']))) {
                return $this->fail("Email or password is not correct", 401);
            }

            $data = [
                'token' => $admin->createToken("USER-TOKEN")->plainTextToken,
            ];
            DB::commit();
            return $this->success('User register successful', $data);
        } catch (\Throwable $th) {
            DB::rollBack();
            return $this->fail("Server Error : " . $th);
        }
    }
}
