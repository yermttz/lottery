<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Entire extends Model
{
    use HasFactory;

    protected $fillable = [
        'ticketid',
        'customerid',
        'entires',
        'fractions',
        'action',
        'active',
        'description',
        'created_by',
        'updated_by'
    ];
}