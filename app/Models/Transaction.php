<?php

namespace App\Models;

use App\Models\Admin\PaymentGateway;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function user_wallets()
    {
        return $this->belongsTo(UserWallet::class, 'user_wallet_id');
    }
    public function payment_gateway()
    {
        return $this->belongsTo(PaymentGateway::class);
    }
}
