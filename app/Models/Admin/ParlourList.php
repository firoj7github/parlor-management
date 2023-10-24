<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ParlourList extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'area_id'                => 'integer',
        'slug'                   => 'string',
        'name'                   => 'string',
        'manager_name'           => 'string',
        'experience'             => 'string',
        'speciality'             => 'string',
        'contact'                => 'string',
        'address'                => 'string',
        'price'                  => 'decimal:8',
        'off_days'               => 'string',
        'image'                  => 'string',
        'status'                 => 'integer',
        'created_at'             => 'date:Y-m-d',
        'updated_at'             => 'date:Y-m-d',
    ];

    public function schedules(){
        return $this->hasMany(ParlourListHasSchedule::class,'parlour_list_id');
    }
    
    public function area(){
        return $this->belongsTo(Area::class,'area_id');
    }
}
