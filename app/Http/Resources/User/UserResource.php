<?php

namespace App\Http\Resources\User;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id'                => $this->id,
            'first_name'        => $this->firstname,
            'last_name'         => $this->lastname,
            'username'          => $this->username,
            'status'            => $this->status,
            'email'             => $this->email ?? null,
            'image'             => $this->image ? $this->image : null,
            'ver_code'          => $this->ver_code ?? null,
            'ver_code_send_at'  => $this->ver_code_send_at ?? null,
            'email_verified_at' => $this->email_verified_at ?? null,
            'email_verified'    => $this->email_verified ?? null,
            'sms_verified'      => $this->sms_verified ?? 0,
            'kyc_verified'      => $this->kyc_verified ?? 0,
            'created_at'        => $this->created_at,
            'updated_at'        => $this->updated_at,
        ];
    }
}
