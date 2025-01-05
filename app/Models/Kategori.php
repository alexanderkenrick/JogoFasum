<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Kategori extends Model
{
    use HasFactory;

    protected $table = 'kategoris';
    protected $fillable = ['nama'];

    public $hidden = ['created_at'];
    public function fasum() : BelongsToMany
    {
        return $this->belongsToMany(Fasum::class, 'kategori_fasums', 'kategori_id', 'fasum_id');
    }
}
