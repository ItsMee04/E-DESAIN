<?php

namespace App\Models\Notification;

use App\Models\Notification\NotificationRead;
use App\Models\Pengajuan\Pengajuan;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Notification extends Model
{
    protected $fillable = [
        'pengajuan_id',
        'title',
        'message',
        'type',
    ];

    public function pengajuan(): BelongsTo
    {
        return $this->belongsTo(
            Pengajuan::class,
            'pengajuan_id'
        );
    }

    public function reads(): HasMany
    {
        return $this->hasMany(NotificationRead::class);
    }
}
