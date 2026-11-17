<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LimitHonor extends Model
{
    protected $fillable = ['jenis', 'jabatan', 'limit'];

    protected $casts = ['limit' => 'integer'];
}
