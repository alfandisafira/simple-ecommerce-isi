<?php

namespace App\Models;

use App\Models\Merk;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory;

    protected $table = 'products';

    protected $primaryKey = 'id';

    protected $fillable = [
        'id',
        'name',
        'discount',
        'price',
        'merk_id',
        'category_id',
        'status',
        'created_at',
        'updated_at'
    ];

    public function merk()
    {
        return $this->belongsTo(Merk::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function order_items()
    {
        return $this->hasMany(OrderItem::class);
    }
}
