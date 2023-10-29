<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransactionSetting extends Model
{
    use HasFactory;


    protected $guarded = ['id','slug'];

    protected $casts = [
        'admin_id'       => 'integer',
        'slug'           => 'string',
        'title'          => 'string',
        'fixed_charge'   => 'decimal:16',
        'percent_charge' => 'decimal:16',
        'status'         => 'integer',
        'feature_text'   => 'string',
        'created_at'     => 'date:Y-m-d',
        'updated_at'     => 'date:Y-m-d',
    ];

    protected $with = ['admin'];


    public function admin() {
        return $this->belongsTo(Admin::class);
    }
}
