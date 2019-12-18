<?php

namespace App\Http\Middleware;
use Firebase\JWT\JWT;
use Illuminate\Support\Facades\Log;
use App\User;

use Closure;

class Login
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $key = env('JWT_KEY');
        $email = "";
        $pass = "";

        //decodificación
        try{
            $decode = JWT::decode($request->getContent(),$key, array('HS256'));
            $request->logindata = $decode;
            $email = $decode->email;
            $pass = $decode->password;
        }catch(\Exception $e){
            Log::alert("Intento de acceso fallido");
            return response("El token no es correcto",401);
        }

        //autenticación
        $user = User::find($email);

        //si no hay user
        if(!$user){
            Log::alert("Intento de acceso fallido con email ".$email);
            return response("Credenciales incorrectas",401);
        }

        //comprobar email+password

        if($pass !== $user->password){
            Log::alert("Intento de acceso fallido con email ".$email);
            return response("Credenciales incorrectas",401);
        }

        //comprobar si está activo

        if(!$user->activo){
            Log::alert("Intento de acceso fallido con email ".$email);
            return response("No puedes pasar!!!",401);
        }
        
        return $next($request);
    }
}
