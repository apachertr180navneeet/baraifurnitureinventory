<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StockInItem extends Model
{
    use HasFactory , SoftDeletes;

    protected $table = 'stockin_items';

    protected $fillable = ['stock_in_id', 'item_id', 'qty', 'price', 'total'];

    // Belongs to StockIn
    public function stockin()
    {
        return $this->belongsTo(StockIn::class, 'stock_in_id');
    }

    // Belongs to Item
    public function item()
    {
        return $this->belongsTo(Item::class);
    }
}
