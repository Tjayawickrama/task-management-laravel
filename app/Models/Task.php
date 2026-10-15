<?php

namespace App\Models;

use App\Enums\TaskStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'description', 'due_date', 'status'];

    protected function casts(): array
    {
        return [
            'due_date' => 'date',
            'status'   => TaskStatus::class,
        ];
    }

    public function scopeSearch($query, ?string $term)
    {
        return $query->when($term, fn ($q) => $q->where('title', 'like', "%{$term}%"));
    }

    public function scopeStatus($query, ?string $status)
    {
        return $query->when($status, fn ($q) => $q->where('status', $status));
    }

    public function isOverdue(): bool
    {
        return $this->due_date
            && $this->due_date->lt(today())
            && $this->status !== TaskStatus::Completed;
    }
}