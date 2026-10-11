<?php

namespace App\Enums;

enum StaffSpecialty: string
{
    case DEVELOPER = 'DEVELOPER';
    case ARTIST = 'ARTIST';
    case DESIGNER = 'DESIGNER';
    case ANIMATOR = 'ANIMATOR';
    case DATA = 'DATA';
    case AUTOMATION = 'AUTOMATION';
    case OTHER = 'OTHER';
}
