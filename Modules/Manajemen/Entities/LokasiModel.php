<?php

namespace Modules\Manajemen\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\DB;

class LokasiModel extends Model
{
    use HasFactory;

    protected $fillable = [];

    protected static function newFactory()
    {
        return \Modules\Manajemen\Database\factories\LokasiModelFactory::new();
    }

    function getAllLokasi()
    {
        return DB::table('lokasi_kerja')
            ->select([
                'lokasi_kerja.*',
                DB::raw('(SELECT propinsi from geolokasi g where substr(g.id,1,2)=lokasi_kerja.propinsi limit 1) as ket_propinsi'),
                DB::raw('(SELECT kabdankot from geolokasi g where substr(g.id,1,5)=lokasi_kerja.kabkota limit 1) as ket_kabkota'),
                DB::raw('(SELECT kecamatan from geolokasi g where substr(g.id,1,8)=lokasi_kerja.kecamatan limit 1) as ket_kecamatan'),
                DB::raw('(SELECT desa from geolokasi g where g.id=lokasi_kerja.desa limit 1) as ket_desa'),
            ])
            ->get();
    }

    function getLokasiById($id)
    {
        return DB::table('lokasi_kerja')
            ->select([
                'lokasi_kerja.*',
                DB::raw('(SELECT propinsi from geolokasi g where substr(g.id,1,2)=lokasi_kerja.propinsi limit 1) as ket_propinsi'),
                DB::raw('(SELECT kabdankot from geolokasi g where substr(g.id,1,5)=lokasi_kerja.kabkota limit 1) as ket_kabkota'),
                DB::raw('(SELECT kecamatan from geolokasi g where substr(g.id,1,8)=lokasi_kerja.kecamatan limit 1) as ket_kecamatan'),
                DB::raw('(SELECT desa from geolokasi g where g.id=lokasi_kerja.desa limit 1) as ket_desa'),
            ])
            ->where('id', $id)->first();
    }

    function checkLokasiUsed($id)
    {
        return DB::table('lokasi_kerja_peserta')->where('id_lokasi', $id)->first();
    }

    function deleteLokasi($id)
    {
        return DB::table('lokasi_kerja')->where('id', $id)->delete();
    }

    function insert($newLokasi)
    {
        return DB::table('lokasi_kerja')->insertGetId($newLokasi);
    }

    function updateLokasi($id, $updateLokasi)
    {
        return DB::table('lokasi_kerja')->where('id', $id)->update($updateLokasi);
    }

    function getPesertaLokasi($id)
    {
        return DB::select("SELECT p.npm, p.nama, p.prodi, p.fakultas,
            (select keterangan from jenis_kelamin jk where jk.id=p.jenis_kelamin) as jenis_kelamin
            from (select * from lokasi_kerja_peserta where id_lokasi=?) lkp
            join peserta p on p.npm=lkp.npm
            order by nama", [$id]);
    }

    function checkPesertaLokasiExists($idLokasi, $npm)
    {
        return DB::table('lokasi_kerja_peserta')->where('npm', $npm)->where('id_lokasi', $idLokasi)->first();
    }

    function insertPesertaLokasi($data)
    {
        $data['user_insert'] = session('username');
        return DB::table('lokasi_kerja_peserta')->insert($data);
    }

    function insertPesertaLokasiBatch($data)
    {
        return DB::table('lokasi_kerja_peserta')->insert($data);
    }

    function deletePesertaLokasi($idLokasi, $npm)
    {
        return DB::table('lokasi_kerja_peserta')->where('npm', $npm)
            ->where('id_lokasi', $idLokasi)->delete();
    }

    function getAllProp()
    {
        return DB::table('geolokasi')->select(['propinsi as ket', DB::raw('substr(id, 1,2) as id')])->distinct()->get();
    }

    function getKabkota($idProp)
    {
        return DB::table('geolokasi')
            ->select(['kabdankot as ket', DB::raw('substr(id,1,5) as id')])
            ->where(DB::raw('substr(id,1,2)'), '=', $idProp)
            ->distinct()->get();
    }

    function getKecamatan($idKabkota)
    {
        return DB::table('geolokasi')
            ->select(['kecamatan as ket', DB::raw('substr(id,1,8) as id')])
            ->where(DB::raw('substr(id,1,5)'), '=', $idKabkota)
            ->distinct()->get();
    }

    function getDesa($idKecamatan)
    {
        return DB::table('geolokasi')
            ->select(['desa as ket', 'id'])
            ->where(DB::raw('substr(id,1,8)'), '=', $idKecamatan)
            ->distinct()->get();
    }
}
