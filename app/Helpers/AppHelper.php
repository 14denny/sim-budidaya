<?php

namespace App\Helpers;

use Illuminate\Support\Facades\DB;

class AppHelper
{

    public function __construct()
    {
    }

    public static function generateRandomString($length = 10)
    {
        $characters = '23456789abcdefghkmnpqrstuwxyzABCDEFGHJKLMNPQRSTUWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    public static function swal($request, $status, $msg)
    {
        $request->session()->flash('status', $status);
        $request->session()->flash('msg', $msg);
    }

    public static function pkcs7_pad($data, $size)
    {
        $length = $size - strlen($data) % $size;
        return $data . str_repeat(chr($length), $length);
    }

    public static function pkcs7_unpad($data)
    {
        return substr($data, 0, -ord($data[strlen($data) - 1]));
    }

    public static function url_api_mhs($endpoint)
    {
        return "https://api.usk.ac.id/api-mhs/$endpoint";
    }

    public static function iv()
    {
        return env('API_KRS_IV_KEY');
    }

    public static function encryption_key()
    {
        return env('API_KRS_ENC_KEY');
    }

    public static function user_key()
    {
        return env('API_KRS_USER_KEY');
    }

    public static function encrypt($plain_data)
    {
        return openssl_encrypt(
            self::pkcs7_pad($plain_data, 32), // padded data
            'AES-256-CBC',        // cipher and mode
            self::encryption_key(),      // secret key
            0,                    // options (not used)
            self::iv()                   // initialization vector
        );
    }

    public static function decrypt($encrypted_data)
    {
        return json_decode(self::pkcs7_unpad(openssl_decrypt(
            $encrypted_data,
            'AES-256-CBC',
            self::encryption_key(),
            0,
            self::iv()
        )));
    }

    public static function post_encrypt_curl($endpoint, $data)
    {
        $data_encrypted = self::encrypt(json_encode($data));

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => self::url_api_mhs($endpoint),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode(array('data' => $data_encrypted)),
            CURLOPT_HTTPHEADER => array(
                'x-userkey: ' . self::user_key(),
                'Content-type: application/json'
            ),
        ));

        $response = curl_exec($curl);

        $curl_error = curl_error($curl);
        if ($curl_error) {
            return $curl_error;
        }

        $response = json_decode($response);
        if ($response) {
            if (isset($response->result)) {
                $response->result = self::decrypt($response->result);
            }
            return ($response);
        } else {
            return -1;
        }
    }

    public static function reverse_date_format(string $date)
    {
        return join('-', array_reverse(explode('-', $date)));
    }

    public static function get_all_bulans(){
        return [
            ['num' => '01', 'name' => 'Januari'],
            ['num' => '02', 'name' => 'Febriari'],
            ['num' => '03', 'name' => 'Maret'],
            ['num' => '04', 'name' => 'April'],
            ['num' => '05', 'name' => 'Mei'],
            ['num' => '06', 'name' => 'Juni'],
            ['num' => '07', 'name' => 'Juli'],
            ['num' => '08', 'name' => 'Agustus'],
            ['num' => '09', 'name' => 'September'],
            ['num' => '10', 'name' => 'Oktober'],
            ['num' => '11', 'name' => 'November'],
            ['num' => '12', 'name' => 'Desember'],
        ];
    }

    public static function get_nama_bulan($bulan){
        $bulans = self::get_all_bulans();
        if (intval($bulan) >= 1 && intval($bulan) <= 12) {
            return $bulans[$bulan - 1]['name'];
        }
        return '';
    }
}
