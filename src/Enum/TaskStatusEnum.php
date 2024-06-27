<?php

declare(strict_types=1);

namespace App\Enum;

enum TaskStatusEnum: string
{
    case pending = 'pending';
    case completed = 'completed';
}
