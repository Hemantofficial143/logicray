<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    use HasFactory;

    public function companies()
    {
        return $this->hasMany(Company::class);
    }

    public function scopeFilter($query, $name)
    {
        if ($name != null) {
            $query->where('name', $name);
        }
    }

}
