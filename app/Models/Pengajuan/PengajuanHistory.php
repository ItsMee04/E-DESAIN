<?php

namespace App\Models\Pengajuan;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PengajuanHistory extends Model
{
    use HasFactory;
    protected $hidden = ['created_at', 'updated_at'];
    protected $table = 'pengajuanhistory';
    protected $fillable = [
        'pengajuan_id',
        'statuspengajuan_id',
        'user_id',
        'catatan',
    ];

    /**
     * Get the pengajuan that owns the PengajuanHistory
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function pengajuan(): BelongsTo
    {
        return $this->belongsTo(Pengajuan::class, 'pengajuan_id', 'id');
    }

    /**
     * Get the statuspengajuan that owns the PengajuanHistory
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function statuspengajuan(): BelongsTo
    {
        return $this->belongsTo(StatusPengajuan::class, 'statuspengajuan_id', 'id');
    }

    /**
     * Get the user that owns the PengajuanHistory
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
