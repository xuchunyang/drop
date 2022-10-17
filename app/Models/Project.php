<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    public function url(): Attribute
    {
        return Attribute::make(
            get: fn($value, $attributes) => sprintf('https://%s.%s', $attributes['name'], request()->getHost()),
        );
    }
}
