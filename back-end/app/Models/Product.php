<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    const IN_STOCK = 1;
    const LOW_STOCK = 2;
    const OUT_STOCK = 3;

    const statuses = [
        1 => 'IN_STOCK',
        2 => 'LOW_STOCK',
        3 => 'OUT_STOCK',
    ];
    protected $guarded = [];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function orders()
    {
        return $this->belongsToMany(Order::class,'order_item');
    }
}
