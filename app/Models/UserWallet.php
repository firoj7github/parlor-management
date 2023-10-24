<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserWallet extends Model
{
    use HasFactory;
    public $timestamps = true;
    protected $fillable = ['balance', 'status','user_id','currency_id','created_at','updated_at'];

}
