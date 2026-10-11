<?php

namespace App\Enums;

enum ClientStatus: string
{
    case PENDING_VERIFICATION = 'PENDING_VERIFICATION';
    case ACTIVE = 'ACTIVE';
    case SUSPENDED = 'SUSPENDED';
    case DEACTIVATED = 'DEACTIVATED';
}
