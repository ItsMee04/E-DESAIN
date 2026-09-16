<?php

namespace App\Models\Pengajuan;

use App\Models\Master\JenisMedia;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PengajuanJenisMedia extends Model
{
    use HasFactory;
    protected $hidden = ['created_at', 'updated_at'];
    protected $table = 'pengajuanjenismedia';
    protected $fillable = [
        'pengajuan_id',
        'jenismedia_id',
    ];

    /**
     * Get the pengajuan that owns the PengajuanJenisMedia
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function pengajuan(): BelongsTo
    {
        return $this->belongsTo(Pengajuan::class, 'pengajuan_id', 'id');
    }

    /**
     * Get the jenismedia that owns the PengajuanJenisMedia
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function jenismedia(): BelongsTo
    {
        return $this->belongsTo(JenisMedia::class, 'jenismedia_id', 'id');
    }
}
