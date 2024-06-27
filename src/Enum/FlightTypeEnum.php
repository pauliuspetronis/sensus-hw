<?php

declare(strict_types=1);

namespace App\Enum;

enum FlightTypeEnum: string
{
    case arriving = 'arriving';
    case departing = 'departing';
}
