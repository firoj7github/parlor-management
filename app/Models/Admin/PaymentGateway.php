<?php

namespace App\Models\Admin;

use App\Constants\PaymentGatewayConst;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentGateway extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'id'                   => 'integer',
        'slug'                 => 'string',
        'code'                 => 'integer',
        'type'                 => 'string',
        'name'                 => 'string',
        'title'                => 'string',
        'alias'                => 'string',
        'image'                => 'string',
        'credentials'          => 'object',
        'supported_currencies' => 'object',
        'crypto'               => 'boolean',
        'desc'                 => 'string',
        'input_fields'         => 'object',
        'env'                  => 'string',
        'status'               => 'integer',
        'last_edit_by'         => 'integer',
        'created_at'           => 'date:Y-m-d',
        'updated_at'           => 'date:Y-m-d',
    ];

    protected $with = [
        'currencies',
    ];

    public function scopeAutomatic($query)
    {
        return $query->where(function ($q) {
            $q->where("type", PaymentGatewayConst::AUTOMATIC);
        });
    }
    public function scopeGateway($query, $keyword)
    {
        if (is_numeric($keyword)) return $query->where('code', $keyword);
        return $query->where('alias', $keyword);
    }


    public function currencies()
    {
        return $this->hasMany(PaymentGatewayCurrency::class, 'payment_gateway_id')->orderBy("id", "DESC");
    }

    public function scopePaymentMethod($query)
    {
        return $query->where(function ($q) {
            $q->where('slug', PaymentGatewayConst::payment_method_slug());
        });
    }

    public function scopeManual($query)
    {
        return $query->where(function ($q) {
            $q->where("type", PaymentGatewayConst::MANUAL);
        });
    }
    public function scopeActive($query)
    {
        return $query->where(function ($q) {
            $q->where("status", PaymentGatewayConst::ACTIVE);
        });
    }
    public function isManual() {
        if($this->type == PaymentGatewayConst::MANUAL) {
            return true;
        }
        return false;
    }

    public function isAutomatic() {
        if($this->type == PaymentGatewayConst::AUTOMATIC) {
            return true;
        }
        return false;
    }

    
}
