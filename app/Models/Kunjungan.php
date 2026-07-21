<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Kunjungan extends Model
{
    protected $table = 'kunjungan';

    protected $primaryKey = 'id_kunjungan';

    public $timestamps = false;

    protected $fillable = [
        'id_negara_asal',
        'id_negara_tujuan',
        'bulan',
        'jumlah'
    ];

    public function negaraAsal(): BelongsTo
    {
        return $this->belongsTo(Negara::class, 'id_negara_asal', 'id_negara');
    }

    public function negaraTujuan(): BelongsTo
    {
        return $this->belongsTo(Negara::class, 'id_negara_tujuan', 'id_negara');
    }
}