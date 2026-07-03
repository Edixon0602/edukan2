<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['module_id', 'question', 'type', 'options', 'correct_answer', 'order_index'])]
class Quiz extends Model
{
    protected function casts(): array
    {
        return [
            'options' => 'array',
        ];
    }

    public function module(): BelongsTo
    {
        return $this->belongsTo(Module::class);
    }
}
