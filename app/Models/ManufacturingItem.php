<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ManufacturingItem extends Model
{
    use HasFactory , SoftDeletes;

    protected $table = 'manufacturing_item';

    protected $fillable = ['manufacturing_id', 'item_id', 'qty'];


    // Belongs to Manufacturing
    public function manufacturing()
    {
        return $this->belongsTo(Manufacturing::class, 'manufacturing_id');
    }

    // Belongs to Item
    public function item()
    {
        return $this->belongsTo(Item::class);
    }

}
