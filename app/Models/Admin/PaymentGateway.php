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
        'input_fields'              => 'object',
        'supported_currencies'      => 'object',
        'credentials'               => 'object',
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

    public function scopeAddMoney($query)
    {
        return $query->where(function ($q) {
            $q->where('slug', PaymentGatewayConst::add_money_slug());
        });
    }

    public function scopeMoneyOut($query)
    {
        return $query->where(function ($q) {
            $q->where('slug', PaymentGatewayConst::money_out_slug());
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
    
}
