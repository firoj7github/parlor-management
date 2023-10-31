<?php

namespace App\Traits\User;

trait RegisteredUsers {
    protected function breakAuthentication($error) {
        return back()->with(['error' => [$error]]);
    }
}