<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Position extends Model
{
    use SoftDeletes;

    protected $fillable = ['division_id', 'name', 'description'];

    public function division(): BelongsTo
    {
        return $this->belongsTo(Division::class)->withTrashed();
    }
}
