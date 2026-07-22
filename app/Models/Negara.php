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

    protected $appends = ['id', 'country_name', 'jan', 'feb', 'mar', 'apr', 'may'];

    public function getIdAttribute()
    {
        return $this->id_negara;
    }

    public function getCountryNameAttribute()
    {
        return $this->nama_negara;
    }

    public function getJanAttribute()
    {
        return $this->kunjunganAsal()->where('bulan', 'Januari')->sum('jumlah');
    }

    public function getFebAttribute()
    {
        return $this->kunjunganAsal()->where('bulan', 'Februari')->sum('jumlah');
    }

    public function getMarAttribute()
    {
        return $this->kunjunganAsal()->where('bulan', 'Maret')->sum('jumlah');
    }

    public function getAprAttribute()
    {
        return $this->kunjunganAsal()->where('bulan', 'April')->sum('jumlah');
    }

    public function getMayAttribute()
    {
        return $this->kunjunganAsal()->where('bulan', 'Mei')->sum('jumlah');
    }

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