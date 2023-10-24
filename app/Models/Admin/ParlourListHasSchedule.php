<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ParlourListHasSchedule extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'parlour_list_id'   => 'integer',
        'week_id'           => 'integer',
        'from_time'         => 'string',
        'to_time'           => 'string',
        'max_client'        => 'integer',
        'status'            => 'integer',
    ];
    public function parlour(){
        return $this->belongsTo(ParlourList::class,'parlour_list_id');
    }
    public function week(){
        return $this->belongsTo(Week::class,'week_id');
    }
}
