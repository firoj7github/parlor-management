<?php

namespace App\Traits\User;

use App\Models\UserLoginLog;
use Exception;
use Jenssegers\Agent\Agent;

trait LoggedInUsers {
    protected function createLoginLog($user) {
        $client_ip = request()->ip() ?? false;
        $location = geoip()->getLocation($client_ip);

        $agent = new Agent();

        $mac = "";

        $data = [
            'user_id'       => $user->id,
            'ip'            => $client_ip,
            'mac'           => $mac,
            'city'          => $location['city'] ?? "",
            'country'       => $location['country'] ?? "",
            'longitude'     => $location['lon'] ?? "",
            'latitude'      => $location['lat'] ?? "",
            'timezone'      => $location['timezone'] ?? "",
            'browser'       => $agent->browser() ?? "",
            'os'            => $agent->platform() ?? "",
        ];

        try{
            UserLoginLog::create($data);
        }catch(Exception $e) {
            // return false;
        }
    }
}