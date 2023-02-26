<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CouponDiscount extends Model
{
    use HasFactory;

    protected $table = 'coupon_discounts';

    protected $primaryKey = 'id';

    protected $fillable = [
        'id',
        'code',
        'discount_amount',
        'desc',
        'created_at',
        'updated_at',
    ];
}
