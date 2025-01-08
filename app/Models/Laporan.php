<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Laporan extends Model
{
    use HasFactory;

    protected $table = 'laporans';

    protected $fillable = ['dinas_id', 'kategori_id', 'image_path', 'status'];

    public function create_by()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    public function update_by()
    {
        return $this->belongsTo(User::class, 'updated_by', 'id');
    }

    public function fasum() : BelongsToMany
    {
        return $this->belongsToMany(Fasum::class, 'laporan_fasums', 'laporan_id', 'fasum_id')
            ->withPivot('status', 'image_path', 'image_selesai', 'deskripsi', 'keterangan_dinas')
            ->withTimestamps();
    }

    public function logStatus() : HasMany
    {
        return $this->hasMany(LogStatus::class, 'laporan_id', 'id');
    }
}
