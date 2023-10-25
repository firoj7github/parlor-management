<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceType extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    protected $casts    = [
        'id'            => 'integer',
        'slug'          => 'string',
        'name'          => 'string',
        'price'         => 'decimal:8',
        'status'        => 'boolean',
    ];
}
