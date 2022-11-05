<?php

namespace App\Helpers;

use Illuminate\Support\Facades\DB;

class MenuHelper
{

    public function __construct()
    {
    }

    public static function getListMenu(){
        $roleid = session('roleid');

        if(!$roleid){
            return [];
        }

        return DB::table('menu')
            ->select(['menu.nama', 'menu.url', 'parent_menu.nama as parent', 'parent_menu.icon as icon_parent', 'menu.icon as menu_icon'])
            ->join('role_menu', 'role_menu.id_menu', '=', 'menu.id')
            ->leftJoin('parent_menu', 'parent_menu.id', '=', 'menu.id_parent_menu')
            ->where('role_menu.id_role', '=', $roleid)
            ->orderBy('parent_menu.order')
            ->orderBy('menu.order')
            ->get();
    }
}