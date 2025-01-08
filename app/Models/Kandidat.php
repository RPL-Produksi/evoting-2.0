<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kandidat extends Model
{
    use HasFactory, HasUuids;

    public function voting()
    {
        return $this->hasMany(Voting::class);
    }

    public function pemilu()
    {
        return $this->belongsTo(Pemilu::class);
    }
}
