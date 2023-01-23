<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;

    protected $fillable = [
        'supplierid',
        'lotteryid',
        'price',
        'fractions',
        'serie',
        'emission',
        'active',
        'descripcion',
        'created_by',
        'updated_by'
    ];
}