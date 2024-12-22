<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LogStatus extends Model
{
    use HasFactory;
    protected $table = 'log_status';
    protected $fillable = ['laporan_id', 'status_sebelum','status_sesudah','changed_by'];

    public function laporan() : BelongsTo
    {
        return $this->belongsTo(Laporan::class, 'laporan_id', 'id');
    }

    public function user() : BelongsTo
    {
        return $this->belongsTo(User::class, 'changed_by', 'id');
    }
}
