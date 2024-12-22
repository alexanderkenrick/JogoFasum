<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Dinas extends Model
{
    use HasFactory;

    protected $table = 'dinas';
    protected $fillable = ['nama'];

    public function user() : HasMany
    {
        return $this->hasMany(User::class, 'dinas_id', 'id');
    }

    public function fasum() : HasMany
    {
        return $this->hasMany(Fasum::class, 'dinas_terkait', 'id');
    }
}
