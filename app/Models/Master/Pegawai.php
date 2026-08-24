<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
}
