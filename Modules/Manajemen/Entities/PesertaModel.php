<?php

namespace Modules\Manajemen\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\DB;

class PesertaModel extends Model
{
    use HasFactory;

    protected $fillable = [];

    protected static function newFactory()
    {
        return \Modules\Manajemen\Database\factories\PesertaModelFactory::new();
    }

    public function getPesertaByNpm($npm)
    {
        return DB::selectOne(
            "SELECT npm, nama, prodi, fakultas, 
            (select keterangan from jenis_kelamin jk where jk.id=p.jenis_kelamin) as jenis_kelamin
            from peserta p where npm=?",
            [$npm]
        );
    }

    public function searchPeserta($npm){
        return DB::table('peserta')->where('npm', $npm)
            ->get(['npm as nim13', 'nama as nama_mhs', 'prodi as nama_prodi', 'fakultas as nama_fakultas', 'jenis_kelamin'])->first();
    }

    public function insertPeserta($data){
        return DB::table('peserta')->insert($data);
    }
}
