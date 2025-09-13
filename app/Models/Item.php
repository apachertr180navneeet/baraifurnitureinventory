<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Item extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'code',
        'category_id',
        'qty',
        'image',
        'status',
        'price',
    ];

    // Relationship: Item belongs to a Category
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Get all manufacturing records for this item.
     */
    public function manufacturings()
    {
        return $this->hasMany(Manufacturing::class, 'item_id');
    }
}
