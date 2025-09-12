<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StockOutItem extends Model
{
    use HasFactory , SoftDeletes;

    protected $table = 'stockout_items';

    protected $fillable = ['stock_out_id', 'item_id', 'qty', 'price', 'total'];

    // Belongs to StockOut
    public function stockout()
    {
        return $this->belongsTo(StockOut::class, 'stock_out_id');
    }

    // Belongs to Item
    public function item()
    {
        return $this->belongsTo(Item::class);
    }
}
