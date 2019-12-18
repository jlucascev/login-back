<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Acceso;

class UserController extends Controller
{
    public function login(Request $r){
        $infologin = $r->logindata;
        Log::info("El usuario ".$infologin->email." se ha registrado");

        //registre acceso en BD
        $acceso = new Acceso();
        $acceso->email = $infologin->email;
        $acceso->fecha = date('Y-m-d H:i:s');
        $acceso->save();

        return response("Acceso correcto");
    }

    public function accesos(){
    	$accesos = Acceso::orderBy('id','DESC')->get();

    	return response()->json($accesos);
    }
}
