<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Candidate extends Model
{
    use HasFactory;

    public function companies(): BelongsToMany
    {
        return $this->belongsToMany(Company::class)
            ->withPivot('status')
            ->withTimestamps();
    }

    public function scopeFilters($query, array $filters = [])
    {
        return $query->when($filters['strengths'] ?? null, function ($query, $strengths) {
            return $query->whereRaw('JSON_CONTAINS(strengths, ?)', [json_encode($strengths)]);
        })->when($filters['skills'] ?? null, function ($query, $skills) {
            return $query->whereRaw('JSON_CONTAINS(soft_skills, ?)', [json_encode($skills)]);
        });
    }
}
