<?php

namespace App\Enums;

enum TaskStatus: string
{
    case Pending    = 'pending';
    case InProgress = 'in_progress';
    case Completed  = 'completed';

    public function label(): string
    {
        return match ($this) {
            self::Pending    => 'Pending',
            self::InProgress => 'In Progress',
            self::Completed  => 'Completed',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::Pending    => 'bg-amber-50 text-amber-700 ring-amber-600/20',
            self::InProgress => 'bg-blue-50 text-blue-700 ring-blue-600/20',
            self::Completed  => 'bg-emerald-50 text-emerald-700 ring-emerald-600/20',
        };
    }

    public function dot(): string
    {
        return match ($this) {
            self::Pending    => 'bg-amber-500',
            self::InProgress => 'bg-blue-500',
            self::Completed  => 'bg-emerald-500',
        };
    }
}