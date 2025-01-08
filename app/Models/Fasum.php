<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Fasum extends Model
{
    use HasFactory;

    protected $table = 'fasums';
    protected $fillable = ['nama', 'tipe', 'luas', 'dinas_terkait', 'asal_fasilitas', 'lat', 'long', 'img_path'];

    public function dinas() : BelongsTo
    {
        return $this->belongsTo(Dinas::class, 'dinas_terkait', 'id');
    }

    public function kategori() : BelongsToMany
    {
        return $this->belongsToMany(Kategori::class, 'kategori_fasums', 'fasum_id', 'kategori_id');
    }

    public function laporan() : BelongsToMany
    {
        return $this->belongsToMany(Laporan::class, 'laporan_fasums', 'fasum_id', 'laporan_id')
            ->withPivot('status', 'image_path', 'image_selesai', 'deskripsi', 'keterangan_dinas')
            ->withTimestamps();
    }


}
