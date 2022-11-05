<?php

namespace Modules\Auth\Http\Controllers;

use App\Helpers\MenuHelper;
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
    public function index()
    {
        return view('auth::index');
    }

    public function logout(Request $request)
    {
        $request->session()->flush();
    }
}
