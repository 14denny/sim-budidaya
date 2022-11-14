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

    function getAllLokasiPeserta($npm){
        return DB::table('lokasi_kerja_peserta')
        ->select([
            'lokasi_kerja.*',
            DB::raw('(SELECT propinsi from geolokasi g where substr(g.id,1,2)=lokasi_kerja.propinsi limit 1) as ket_propinsi'),
            DB::raw('(SELECT kabdankot from geolokasi g where substr(g.id,1,5)=lokasi_kerja.kabkota limit 1) as ket_kabkota'),
            DB::raw('(SELECT kecamatan from geolokasi g where substr(g.id,1,8)=lokasi_kerja.kecamatan limit 1) as ket_kecamatan'),
            DB::raw('(SELECT desa from geolokasi g where g.id=lokasi_kerja.desa limit 1) as ket_desa'),
        ])
        ->join('lokasi_kerja', 'lokasi_kerja.id','=', 'lokasi_kerja_peserta.id_lokasi')->where('lokasi_kerja_peserta.npm', $npm)->get();
    }
}
