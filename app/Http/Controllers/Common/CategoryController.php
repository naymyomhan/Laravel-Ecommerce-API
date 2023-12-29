<?php

namespace App\Http\Controllers\Common;

use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryDetailResource;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CategoryController extends Controller
{
    use ResponseTrait;
    public function getCategories()
    {
        try {
            $categories = Category::all();
            return $this->success("Get categories successful", CategoryDetailResource::collection($categories));
        } catch (\Throwable $th) {
            Log::error('CategoryController : getCategories() :' . $th->getMessage());
            return $this->fail("Something went wrong", 500);
        }
    }
}
