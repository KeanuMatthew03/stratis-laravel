<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Negara extends Model
{
    protected $table = 'negara';

    protected $primaryKey = 'id_negara';

    public $timestamps = false;

    protected $fillable = [
        'nama_negara',
        'id_sumber'
    ];

    public function sumber(): BelongsTo
    {
        return $this->belongsTo(Sumber::class, 'id_sumber', 'id_sumber');
    }

    public function kunjunganAsal(): HasMany
    {
        return $this->hasMany(Kunjungan::class, 'id_negara_asal', 'id_negara');
    }

    public function kunjunganTujuan(): HasMany
    {
        return $this->hasMany(Kunjungan::class, 'id_negara_tujuan', 'id_negara');
    }
}