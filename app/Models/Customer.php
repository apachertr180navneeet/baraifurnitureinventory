<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'customers';

    protected $fillable = ['name', 'mobile', 'email', 'address'];

    // One vendor has many stockins
    public function stockins()
    {
        return $this->hasMany(StockIn::class);
    }
}
