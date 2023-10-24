<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BasicSettings extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'mail_config'              => 'object',
        'sms_config'               => 'object',
        'push_notification_config' => 'object',
        'broadcast_config'         => 'object',
        'site_logo_dark'           => 'string',
        'site_logo'                => 'string',
        'site_fav_dark'            => 'string',
        'site_fav'                 => 'string',
    ];


    public function mailConfig() {
        
    }
}
