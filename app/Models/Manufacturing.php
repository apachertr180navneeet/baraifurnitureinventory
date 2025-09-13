<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Manufacturing extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'manufacturing';

    protected $fillable = ['item_id', 'start_date', 'end_date', 'qty', 'add_amount','image','status'];

    /**
     * Get the item associated with this manufacturing record.
     */
    public function item()
    {
        return $this->belongsTo(Item::class, 'item_id');
    }


    public function manufacturingItem()
    {
        return $this->hasMany(ManufacturingItem::class, 'manufacturing_id');
    }

}
