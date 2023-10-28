<?php

namespace App\Models;

use App\Models\Admin\ParlourList;
use App\Models\Admin\ParlourListHasSchedule;
use App\Models\Admin\PaymentGatewayCurrency;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ParlourBooking extends Model
{
    use HasFactory;
    protected $guarded  = ['id'];

    protected $casts                    = [
        'id'                            => 'integer',
        'parlour_id'                    => 'integer',
        'schedule_id'                   => 'integer',
        'user_id'                       => 'integer',
        'payment_gateway_currency_id'   => 'integer',
        'date'                          => 'string',
        'payment_method'                => 'string',
        'slug'                          => 'string',
        'price'                         => 'decimal:8',
        'gateway_payable_price'         => 'decimal:8',
        'service'                       => 'object',
        'message'                       => 'string',
        'serial_number'                 => 'integer',
        'status'                        => 'boolean',
    ];
    public function scopeAuth($query){
        $query->where('user_id',auth()->user()->id);
    }
    public function user(){
        return $this->belongsTo(User::class,'user_id');
    }
    public function schedule(){
        return $this->belongsTo(ParlourListHasSchedule::class,'schedule_id');
    }
    public function parlour(){
        return $this->belongsTo(ParlourList::class,'parlour_id');
    }
    public function payment_gateway(){
        return $this->belongsTo(PaymentGatewayCurrency::class,'payment_gateway_currency_id');
    }
}
