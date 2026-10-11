<?php

namespace App\Enums;

enum ProjectResponsibility: string
{
    case TEAM_MEMBER = 'TEAM_MEMBER';
    case PROJECT_LEAD = 'PROJECT_LEAD';
    case SCRUM_MASTER = 'SCRUM_MASTER';
}
