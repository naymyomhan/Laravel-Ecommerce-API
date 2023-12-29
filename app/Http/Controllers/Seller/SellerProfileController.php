<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Http\Resources\SellerResource;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SellerProfileController extends Controller
{
    use ResponseTrait;

    public function getProfile()
    {
        $seller = Auth::guard('seller')->user();
        // return $seller;
        return $this->success("Ger profile successful", new SellerResource($seller));
    }
}
