<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Voting extends Model
{
    use HasFactory, HasUuids;

    public function kandidat()
    {
        return $this->belongsTo(Kandidat::class);
    }

    public function pemilu()
    {
        return $this->belongsTo(Pemilu::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
