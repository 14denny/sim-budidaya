<?php

namespace Modules\Auth\Http\Controllers;

use App\Helpers\MenuHelper;
use App\Models\UserBudidaya;
use Exception;
use Illuminate\Contracts\Session\Session;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class AuthController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(Request $request)
    {
        if ($request->session()->has('username')) {
            return redirect(route('beranda.index'));
        }
        return view('auth::index');
    }

    public function doLogin(Request $request)
    {
        if ($request->session()->has('username')) {
            echo json_encode(array(
                'status' => true,
                'msg' => "Ok",
                'csrf_token' => csrf_token()
            ));
            return;
        }

        try {
            $username = $request->post('username');
            $password = $request->post('password');

            $userModel = new UserBudidaya();
            $user = $userModel->attempLogin($username, $password);

            if (!$user) {
                throw new Exception("Username atau password salah");
            }

            $request->session()->regenerate();

            $listMenu = MenuHelper::getListMenu($user->role);
            $parent_menu = [];
            $submenu = [];
            $current_parent = "";
            foreach ($listMenu as $menu) {
                if (!$menu->parent) {
                    $current_parent = $menu->nama;
                    array_push($parent_menu, array(
                        'icon' => $menu->menu_icon,
                        'name' => $menu->nama,
                        'url' => $menu->url
                    ));
                } else {
                    if ($current_parent != $menu->parent) {
                        $current_parent = $menu->parent;
                        array_push($parent_menu, array(
                            'icon' => $menu->icon_parent,
                            'name' => $menu->parent,
                            'url' => null,
                        ));
                    }

                    if (!isset($submenu[$current_parent])) {
                        $submenu[$current_parent] = [];
                    }
                    array_push($submenu[$current_parent], array(
                        'name' => $menu->nama,
                        'url' => $menu->url
                    ));
                }
            }

            session([
                'roleid' => $user->role,
                'username' => $user->username,
                'name' => $user->name,
                'email' => $user->email,
                'menus' => array(
                    'parent' => $parent_menu,
                    'sub' => $submenu
                )
            ]);

            echo json_encode(array(
                'status' => true,
                'msg' => "Ok",
                'csrf_token' => csrf_token()
            ));
        } catch (Exception $e) {
            echo json_encode(array(
                'status' => false,
                'msg' => $e->getMessage(),
                'csrf_token' => csrf_token()
            ));
        }
    }

    public function cek()
    {
        print_r(session('menus'));
    }

    public function logout(Request $request)
    {
        $request->session()->flush();
        return redirect(route('auth.form'));
    }

    public function tes(Request $request)
    {
        if ($request->session()->has('username')) {
            echo "ada";
        } else {
            echo "tidah ada";
        }
        return;
    }
}
