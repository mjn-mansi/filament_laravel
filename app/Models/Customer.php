<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $fillable = ['name', 'email', 'phone', 'is_active'];

    public function scopeActive($query) {
        return $query->where('is_active', true);
    }
}
