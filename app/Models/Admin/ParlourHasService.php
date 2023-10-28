<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ParlourHasService extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    protected $casts = [
        'parlour_list_id'   => 'integer',
        'service_name'      => 'string',
        'price'             => 'decimal:8',
    ];
    public function parlour(){
        return $this->belongsTo(ParlourList::class,'parlour_list_id');
    }
}
