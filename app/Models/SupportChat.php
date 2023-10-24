<?php

namespace App\Models;

use App\Models\Admin\Admin;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SupportChat extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $with = [
        'supportTicket',
    ];
    protected $casts = [
        'id'                        => 'integer',
        'support_ticket_id'         => 'integer',
        'sender'                    => 'integer',
        'sender_type'               => 'string',
        'receiver'                  => 'integer',
        'receiver_type'             => 'string',
        'message'                   => 'string',
        'seen'                      => 'integer',
        'created_at'                => 'datetime',
        'updated_at'                => 'datetime',  
    ];

    protected $appends = ['senderImage'];

    public function scopeConversations($query,$id) {
        return $query->where("support_ticket_id",$id);
    }

    public function supportTicket() {
        return $this->belongsTo(SupportTicket::class,"support_ticket_id");
    }

    public function getSenderImageAttribute() {
        if($this->sender_type == "ADMIN") {
            $admin = Admin::find($this->sender);
            if($admin) {
                return get_image($admin->image,"admin-profile");
            }else {
                return files_asset_path("default");
            }
        }else if($this->sender_type == "USER"){
            return $this->supportTicket->creator->userImage;
        }
        return files_asset_path("default");
    }
}
