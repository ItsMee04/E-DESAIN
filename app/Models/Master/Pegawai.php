<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pegawai extends Model
{
    use HasFactory;
    protected $hidden = ['created_at', 'updated_at'];
    protected $table = 'pegawai';
    protected $fillable = [
        'nip',
        'nama',
        'tempat',
        'tanggal',
        'jeniskelamin_id',
        'alamat',
        'agama_id',
        'profesi_id',
        'kontak',
        'image',
        'status'
    ];

    /**
     * Get the jeniskelamin that owns the Pegawai
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function jeniskelamin(): BelongsTo
    {
        return $this->belongsTo(JenisKelamin::class, 'jeniskelamin_id', 'id');
    }

    /**
     * Get the agama that owns the Pegawai
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function agama(): BelongsTo
    {
        return $this->belongsTo(Agama::class, 'agama_id', 'id');
    }

    /**
     * Get the profesi that owns the Pegawai
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function profesi(): BelongsTo
    {
        return $this->belongsTo(Profesi::class, 'profesi_id', 'id');
    }
}
