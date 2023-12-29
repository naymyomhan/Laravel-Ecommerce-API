<?php

namespace App\Http\Resources;

use App\Models\SubCategory;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'price' => $this->price,
            'quantity' => $this->quantity,
            'images' => $this->images,
            'category' => new CategoryResource($this->whenLoaded('category')),
            'sub_category' => new SubCategoryResource($this->whenLoaded('subCategory')),
            'seller' => new ShopResource($this->whenLoaded('seller')),
            'brand' => new BrandResource($this->whenLoaded('brand')),
            'is_featured' => $this->is_featured,
            'is_active' => $this->is_active,
            'tags' => $this->tags,
            'created_at' => $this->created_at->format('Y,M d'),
        ];
    }
}
