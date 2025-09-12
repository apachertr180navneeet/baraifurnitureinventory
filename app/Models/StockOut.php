<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StockOut extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'stockouts';

    protected $fillable = ['date', 'customer_id', 'status'];

    // StockIn belongs to a Vendor
    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    // StockIn has many StockInItems
    public function items()
    {
        return $this->hasMany(StockInItem::class, 'stock_in_id');
    }

    public function stockOutItems()
    {
        return $this->hasMany(StockOutItem::class, 'stock_out_id');
    }
}
