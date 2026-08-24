<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JenisKelamin extends Model
{
    use HasFactory;
    protected $hidden = ['created_at', 'updated_at'];
    protected $table = 'jeniskelamin';
    protected $fillable = ['jeniskelamin', 'status'];
}
