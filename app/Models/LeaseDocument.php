<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeaseDocument extends Model
{
    protected $guarded = [];

    public function lease()
    {
        return $this->belongsTo(Lease::class);
    }
}
