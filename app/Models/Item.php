<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    protected $fillable = ['name', 'description', 'price', 'is_active'];

    public function scopeActive($query) {
        return $query->where('is_active', 1);
    }
}
