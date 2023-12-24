<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;

class UserProductController extends Controller
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
