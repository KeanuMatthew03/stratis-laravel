<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Sumber extends Model
{
    protected $table = 'sumber';

    protected $primaryKey = 'id_sumber';

    public $timestamps = false;

    protected $fillable = [
        'nama_sumber'
    ];

    public function negara(): HasMany
    {
        return $this->hasMany(Negara::class, 'id_sumber', 'id_sumber');
    }
}