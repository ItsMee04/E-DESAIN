<?php

namespace App\Models\Pengajuan;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PengajuanValidasi extends Model
{
    use HasFactory;
    protected $hidden = ['created_at', 'updated_at'];
    protected $table = 'pengajuanvalidasi';
    protected $fillable = [
        'pengajuan_id',
        'user_id',
        'jenis',
        'status',
        'catatan',
        'validated_at',
    ];

    /**
     * Get the pengajuan that owns the PengajuanValidasi
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function pengajuan(): BelongsTo
    {
        return $this->belongsTo(Pengajuan::class, 'pengajuan_id', 'id');
    }

    /**
     * Get the user that owns the PengajuanValidasi
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
