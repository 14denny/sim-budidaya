<?php

namespace Modules\Logbook\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\DB;

class LogbookModel extends Model
{
    use HasFactory;

    protected $fillable = [];

    protected static function newFactory()
    {
        return \Modules\Logbook\Database\factories\LogbookModelFactory::new();
    }

    function getAllFase()
    {
        return DB::table('fase')->get();
    }

    function getTahap($idFase)
    {
        return DB::table('tahap')->where('id_fase', $idFase)->get();
    }

    function getKegiatan($idTahap)
    {
        return DB::table('kegiatan')->where('id_tahap', $idTahap)->get();
    }

    function getDetilKegiatan($idKegiatan)
    {
        return DB::table('detil_kegiatan')->where('id_kegiatan', $idKegiatan)->get();
    }

    function getAllHamaPenyakit()
    {
        return DB::table('hama_penyakit')->select(['hama_penyakit.*', 'jenis_hama_penyakit.ket as jenis_hama_penyakit'])
            ->join('jenis_hama_penyakit', 'jenis_hama_penyakit.id', '=', 'hama_penyakit.jenis')
            ->orderBy('jenis')->get();
    }

    function checkIdHamaPenyakitTmpExists($idLokasi, $idHamaPenyakit)
    {
        return DB::table('hama_penyakit_log_tmp')
            ->where('id_lokasi', $idLokasi)
            ->where('id_hama_penyakit', $idHamaPenyakit)
            ->first();
    }

    function checkHamaPenyakitTmp($idLokasi, $idHamaPenyakitTmp)
    {
        return DB::table('hama_penyakit_log_tmp')
            ->where('id_lokasi', $idLokasi)
            ->where('id', $idHamaPenyakitTmp)
            ->first();
    }

    function deleteHamaPenyakitTmp($idLokasi, $idHamaPenyakitTmp)
    {
        return DB::table('hama_penyakit_log_tmp')
            ->where('id_lokasi', $idLokasi)
            ->where('id', $idHamaPenyakitTmp)
            ->delete();
    }

    function insertHamaPenyakitTmp($dataInsert)
    {
        return DB::table('hama_penyakit_log_tmp')->insertGetId($dataInsert);
    }

    function clearHamaPenyakitTmp($idLokasi)
    {
        return DB::table('hama_penyakit_log_tmp')
            ->where('id_lokasi', $idLokasi)
            ->delete();
    }

    function getHamaPenyakitTmp($idLokasi)
    {
        return DB::table('hama_penyakit_log_tmp')
            ->select(['hama_penyakit.*', 'jenis_hama_penyakit.ket as jenis_hama_penyakit', 'hama_penyakit_log_tmp.id as id_hama_penyakit_tmp'])
            ->join('hama_penyakit', 'hama_penyakit.id', '=', 'hama_penyakit_log_tmp.id_hama_penyakit')
            ->join('jenis_hama_penyakit', 'jenis_hama_penyakit.id', '=', 'hama_penyakit.jenis')
            ->where('hama_penyakit_log_tmp.id_lokasi', $idLokasi)
            ->orderBy('hama_penyakit_log_tmp.inserted_at')
            ->get();
    }
}
