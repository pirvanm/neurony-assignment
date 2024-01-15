<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;

    public function candidates()
    {
        return $this->belongsToMany(Candidate::class)
            ->withPivot('status')
            ->withTimestamps();
    }

    public function wallet()
    {
        return $this->hasOne(Wallet::class);
    }
}
