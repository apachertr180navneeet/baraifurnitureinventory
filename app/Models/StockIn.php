<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StockIn extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'stockins';

    protected $fillable = ['date', 'vendor_id', 'status'];

    // StockIn belongs to a Vendor
    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }

    // StockIn has many StockInItems
    public function items()
    {
        return $this->hasMany(StockInItem::class, 'stock_in_id');
    }

    public function stockInItems()
    {
        return $this->hasMany(StockInItem::class, 'stock_in_id');
    }
}
