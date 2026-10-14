<?php

namespace App\Enums;

enum ContentKind: string
{
    case SERVICE = 'SERVICE';
    case PORTFOLIO = 'PORTFOLIO';
    case PAGE = 'PAGE';

    public function path(): string
    {
        return match ($this) {
            self::SERVICE => '/servicios/', self::PORTFOLIO => '/portfolio/', self::PAGE => '/'
        };
    }
}
