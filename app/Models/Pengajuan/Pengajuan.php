<?php

namespace App\Models\Pengajuan;

use App\Models\Master\Pegawai;
use App\Models\Master\Unit;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Pengajuan extends Model
{
    use HasFactory;
    protected $hidden = ['created_at', 'updated_at'];
    protected $table = 'pengajuan';
    protected $fillable = [
        'nomor',
        'pegawai_id',
        'unit_id',
        'statuspengajuan_id',
        'nama_desain',
        'ukuran',
        'jumlah',
        'keperluan',
        'user_id',
        'status'
    ];

    /**
     * Get the pegawai that owns the Pengajuan
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function pegawai(): BelongsTo
    {
        return $this->belongsTo(Pegawai::class, 'pegawai_id', 'id');
    }

    /**
     * Get the unit that owns the Pengajuan
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function unit(): BelongsTo
    {
        return $this->belongsTo(Unit::class, 'unit_id', 'id');
    }

    /**
     * Get the statuspengajuan that owns the Pengajuan
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function statuspengajuan(): BelongsTo
    {
        return $this->belongsTo(StatusPengajuan::class, 'statuspengajuan_id', 'id');
    }

    /**
     * Get the user that owns the Pengajuan
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    /**
     * Get all of the pengajuanjenismedia for the Pengajuan
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function pengajuanjenismedia(): HasMany
    {
        return $this->hasMany(PengajuanJenisMedia::class, 'pengajuan_id', 'id');
    }

    /**
     * Get all of the pengajuanvalidasi for the Pengajuan
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function pengajuanvalidasi(): HasMany
    {
        return $this->hasMany(PengajuanValidasi::class, 'pengajuan_id', 'id');
    }

    /**
     * Get all of the pengajuanhistory for the Pengajuan
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function pengajuanhistory(): HasMany
    {
        return $this->hasMany(PengajuanHistory::class, 'pengajuan_id', 'id');
    }
}
