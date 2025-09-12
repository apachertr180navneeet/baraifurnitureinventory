<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Attendance extends Model
{
    use HasFactory , SoftDeletes;

    protected $table = 'attendance';

    protected $fillable = ['date', 'name', 'in_time', 'out_time', 'leave_day','leave_reason','status'];
}
