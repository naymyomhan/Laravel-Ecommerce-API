<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SellerAddress extends Model
{
    use HasFactory;

    protected $fillable = [
        'seller_id',
        'street',
        'city',
        'state',
        'country',
        'postal_code',
    ];
}
