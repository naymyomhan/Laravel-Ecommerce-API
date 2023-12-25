<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;

class SellerProductController extends Controller
{
    use ResponseTrait;

    public function getAllProducts()
    {
        $data = [
            'data' => "just a dummy data",
        ];
        return $this->success("Get Products Successful", $data);
    }
}
