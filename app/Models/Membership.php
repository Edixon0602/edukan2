<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable(['name', 'price_monthly', 'price_yearly', 'benefits', 'visual_style'])]
class Membership extends Model
{
    protected function casts(): array
    {
        return [
            'benefits' => 'array',
        ];
    }
}
