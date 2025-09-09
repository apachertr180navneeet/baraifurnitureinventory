<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Vendor extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'vendors';

    protected $fillable = ['name', 'mobile', 'email', 'address'];

    // One vendor has many stockins
    public function stockins()
    {
        return $this->hasMany(StockIn::class);
    }
}
