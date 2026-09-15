<?php

namespace App\Models\Master;

use App\Models\Master\UserPermission;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Module extends Model
{
    use HasFactory;

    protected $table = 'module';
    protected $fillable = [
        'parent_id',
        'name',
        'key',
        'route',
        'icon',
        'sort_order',
        'status',
    ];

    public function parent()
    {
        return $this->belongsTo(Module::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Module::class, 'parent_id')
            ->orderBy('sort_order');
    }

    public function permissions()
    {
        return $this->hasMany(UserPermission::class);
    }
}
