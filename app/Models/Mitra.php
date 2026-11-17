<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Mitra extends Model
{
    protected $fillable = ['sobat_id', 'nama', 'posisi', 'email'];

    public function penugasans(): HasMany
    {
        return $this->hasMany(Penugasan::class);
    }

    public function honorBerjalans(): HasMany
    {
        return $this->hasMany(HonorBerjalan::class);
    }
}
