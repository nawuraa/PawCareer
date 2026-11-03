<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HiringDecision extends Model
{
    protected $fillable = [
        'application_id',
        'decided_by',
        'result',
        'start_date',
        'note',
        'decided_at',
    ];

    protected $casts = [
        'start_date' => 'date',
        'decided_at' => 'datetime',
    ];

    public function application(): BelongsTo
    {
        return $this->belongsTo(Application::class);
    }

    public function decisionMaker(): BelongsTo
    {
        return $this->belongsTo(User::class, 'decided_by');
    }
}
