<?php

namespace Modules\Manajemen\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class lokasiPesertaModel extends Model
{
    use HasFactory;

    protected $fillable = [];
    
    protected static function newFactory()
    {
        return \Modules\Manajemen\Database\factories\LokasiPesertaModelFactory::new();
    }
}
