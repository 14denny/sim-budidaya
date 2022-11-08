<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class UserBudidaya extends Model
{
    use HasFactory;

    protected $table = "users";

    function attempLogin($username, $password)
    {
        $user = DB::table($this->table)->where('username', $username)->first();
        if (!$user) {
            return false;
        }
        return password_verify($password, $user->password) ? $user : false;
    }

    public static function allUsers()
    {
        return DB::select("SELECT username, name as nama, email, (select keterangan from roles r where r.id=u.role) as rolename from users u");
    }

    public static function getAllRole()
    {
        return DB::table('roles')->get();
    }

    public function getUserByUsername($username){
        return DB::selectOne("SELECT username, name as nama, email, 
            (select keterangan from roles r where r.id=u.role) as rolename 
        from users u where username=?",[$username]);
    }

    public function addUser($newUser){
        return DB::table('users')->insert($newUser);
    }

    public function deleteUser($username){
        return DB::table('users')->where('username', $username)->delete();
    }

    public function changePassword($username, $password){
        return DB::table('users')->where('username', $username)->update([
            'password'=>password_hash($password, PASSWORD_BCRYPT)
        ]);
    }
}
