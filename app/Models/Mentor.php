<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mentor extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'role',
        'image',
        'skills',
    ];

    /**
     * Get the mentor's skills as an array.
     */
    public function getSkillsArrayAttribute(): array
    {
        if (empty($this->skills)) {
            return [];
        }

        return array_values(array_filter(array_map('trim', explode(',', $this->skills))));
    }
}
