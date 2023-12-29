<?php

namespace App\Http\Controllers\Common;

use App\Http\Controllers\Controller;
use App\Http\Resources\BrandResource;
use App\Http\Resources\CategoryResource;
use App\Models\Brand;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class BrandController extends Controller
{
    use ResponseTrait;

    public function getBrands()
    {
        try {
            $brands = Brand::all();
            return $this->success("Get brands successful", BrandResource::collection($brands));
        } catch (\Throwable $th) {
            Log::error('CategoryController : getCategories() :' . $th->getMessage());
            return $this->fail("Something went wrong", 500);
        }
    }
}
