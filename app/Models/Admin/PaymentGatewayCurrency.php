<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentGatewayCurrency extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function gateway() {
        return $this->belongsTo(PaymentGateway::class,"payment_gateway_id");
    }
}
