<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Balance extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'appliedtype',
        'appliedid',
        'date',
        'type',
        'amount',
        'description',
        'created_by',
        'updated_by'
    ];
}
