<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['module_id', 'title', 'video_url', 'description', 'resources', 'order_index'])]
class Lesson extends Model
{
    protected function casts(): array
    {
        return [
            'resources' => 'array',
        ];
    }

    public function module(): BelongsTo
    {
        return $this->belongsTo(Module::class);
    }
}
