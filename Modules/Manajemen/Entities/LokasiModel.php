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

    public function getAllLokasi(){
        return DB::table('lokasi_kerja')->get();
    }

    public function getLokasiById($id){
        return DB::table('lokasi_kerja')->where('id', $id)->first();
    }

    public function checkLokasiUsed($id){
        return DB::table('lokasi_kerja_peserta')->where('id_lokasi', $id)->first();
    }

    public function deleteLokasi($id)
    {
        return DB::table('lokasi_kerja')->where('id', $id)->delete();
    }

    public function insert($newLokasi){
        return DB::table('lokasi_kerja')->insertGetId($newLokasi);
    }

    public function updateLokasi($id, $updateLokasi){
        return DB::table('lokasi_kerja')->where('id',$id)->update($updateLokasi);
    }

    public function getPesertaLokasi($id){
        return DB::select("SELECT p.npm, p.nama, p.prodi, p.fakultas,
            (select keterangan from jenis_kelamin jk where jk.id=p.jenis_kelamin) as jenis_kelamin
            from (select * from lokasi_kerja_peserta where id_lokasi=?) lkp
            join peserta p on p.npm=lkp.npm", [$id]);
    }

    public function checkPesertaLokasiExists($idLokasi, $npm){
        return DB::table('lokasi_kerja_peserta')->where('npm', $npm)->where('id_lokasi', $idLokasi)->first();
    }

    public function insertPesertaLokasi($data){
        $data['user_insert'] = session('username');
        return DB::table('lokasi_kerja_peserta')->insert($data);
    }

    public function deletePesertaLokasi($idLokasi, $npm){
        return DB::table('lokasi_kerja_peserta')->where('npm', $npm)
            ->where('id_lokasi', $idLokasi)->delete();
    }
}
