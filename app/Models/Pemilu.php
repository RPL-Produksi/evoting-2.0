<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pemilu extends Model
{
    use HasFactory, HasUuids;

    public function notification()
    {
        return $this->hasMany(Notification::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function voting()
    {
        return $this->hasMany(Voting::class);
    }

    public function voteLogs()
    {
        return $this->hasMany(VoteLogs::class);
    }
}
