<?php

namespace Modules\Logbook\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\DB;

class HamaPenyakitModel extends Model
{
    use HasFactory;

    protected $fillable = [];

    protected static function newFactory()
    {
        return \Modules\Logbook\Database\factories\HamaPenyakitModelFactory::new();
    }

    function getHamaPenyakitById($idHamaPenyakit)
    {
        return DB::table('hama_penyakit')->select(['hama_penyakit.*', 'jenis_hama_penyakit.ket as jenis_hama_penyakit'])
            ->join('jenis_hama_penyakit', 'jenis_hama_penyakit.id', '=', 'hama_penyakit.jenis')
            ->where('hama_penyakit.id', $idHamaPenyakit)->first();
    }
}
