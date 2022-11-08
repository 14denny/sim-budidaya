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

}
