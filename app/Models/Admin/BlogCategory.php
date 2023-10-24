<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BlogCategory extends Model
{
    use HasFactory;
    protected $guarded =['id'];

    protected $casts = [
        'id'         => 'integer',
        'slug'       => 'string',
        'name'       => 'object',
        'status'     => 'integer',
        'created-at' => 'date:Y-m-d',
        'updated_at' => 'date:Y-m-d',
    ];

    public function blog(){
        return $this->hasMany(Blog::class,'category_id');
    }
}
