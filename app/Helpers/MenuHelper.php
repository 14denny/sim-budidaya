<?php

namespace App\Helpers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

class MenuHelper
{

    public function __construct()
    {
    }

    public static function getListMenu($roleid){

        if(!$roleid){
            return [];
        }

        return DB::table('menu')
            ->select(['menu.nama', 'menu.url', 'parent_menu.nama as parent', 'parent_menu.path as parent_path', 'parent_menu.icon as icon_parent', 'menu.icon as menu_icon'])
            ->join('role_menu', 'role_menu.id_menu', '=', 'menu.id')
            ->leftJoin('parent_menu', 'parent_menu.id', '=', 'menu.id_parent_menu')
            ->where('role_menu.id_role', '=', $roleid)
            ->orderBy('parent_menu.order')
            ->orderBy('menu.order')
            ->get();
    }

    public static function parentActive($parent){
        $current = Route::getFacadeRoot()->current()->uri();
        return str_starts_with($current, $parent);
    }

    public static function submenuActive($submenu){
        $current = Route::getFacadeRoot()->current()->uri();
        return str_starts_with($current, $submenu);
    }

    public static function getFirstMenuRole($roleid){
        return DB::selectOne('SELECT m.url, m.order, 
            (select pm.order from parent_menu pm where pm.id=m.id_parent_menu) as order_parent from 
            (select * from role_menu where id_role=?) rm 
            join (select * from menu where menu.id in (select id_menu from role_menu where id_role=?)) m on m.id=rm.id_menu
            where id_role=?
            order by m.order, order_parent
            limit 1', [$roleid, $roleid, $roleid])->url;
    }
}