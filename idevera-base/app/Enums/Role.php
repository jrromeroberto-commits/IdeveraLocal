<?php

namespace App\Enums;

enum Role: string
{
    case CLIENT = 'CLIENT';
    case STAFF = 'STAFF';
    case OWNER = 'OWNER';

    public function dashboardRoute(): string
    {
        return strtolower($this->value).'.dashboard';
    }
}
