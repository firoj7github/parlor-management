<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TemporaryData extends Model
{
    use HasFactory;
    protected $table = "temporary_datas";

    protected $guarded = ['id'];

    protected $casts = [
        'id'             => 'integer',
        'type'           => 'string',
        'identifier'     => 'string',
        'gateway_code'   => 'string',
        'currency_code'  => 'string',
        'data'           => 'object',
        'created_at'     => 'date:Y-m-d',
        'updated_at'     => 'date:Y-m-d',
    ];
}
