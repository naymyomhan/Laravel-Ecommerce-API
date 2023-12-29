<?php

namespace App\Http\Controllers\Common;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    use ResponseTrait;

    public function getAllProducts(Request $request)
    {
        $query = Product::query();

        //Filters
        $query->when($request->filled('category_id'), function ($query) use ($request) {
            $query->where('category_id', $request->category_id);
        });

        $query->when($request->filled('sub_category_id'), function ($query) use ($request) {
            $query->where('sub_category_id', $request->sub_category_id);
        });
        // Add more filters price range, brand, search keyword

        $query->when($request->filled('search'), function ($query) use ($request) {
            $query->where(function ($query) use ($request) {
                $query->where('name', 'like', '%' . $request->search . '%')
                    ->orWhere('description', 'like', '%' . $request->search . '%')
                    ->orWhereHas('brand', function ($query) use ($request) {
                        $query->where('name', 'like', '%' . $request->search . '%');
                    })
                    ->orWhereHas('category', function ($query) use ($request) {
                        $query->where('name', 'like', '%' . $request->search . '%');
                    })
                    ->orWhereHas('subCategory', function ($query) use ($request) {
                        $query->where('name', 'like', '%' . $request->search . '%');
                    });
            });
        });

        $products = $query->with(['category', 'subCategory', 'seller', 'brand'])->paginate(5);
        // return $this->success("Get Products Successful", ProductResource::collection($products));
        // return $this->success("Get Products Successful", $products);
        return $this->success("Book List", [
            'current_page' => $products->currentPage(),
            'last_page' => $products->lastPage(),
            'per_page' => $products->perPage(),
            'data' => ProductResource::collection($products->items()),
        ]);
    }
}
