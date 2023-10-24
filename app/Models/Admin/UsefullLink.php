<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UsefullLink extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts   = [
        'id'           => 'integer',
        'type'         => 'string',
        'title'        => 'object',
        'slug'         => 'string',
        'url'          => 'string',
        'content'      => 'object',
        'status'       => 'integer',
        'editable'     => 'integer',
        'created_at'   => 'date:Y-m-d',
        'updated_at'   => 'date:Y-m-d',
    ];
}
