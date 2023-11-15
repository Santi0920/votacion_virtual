<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SessionsController extends Controller
{
    //
    public function login()
    {
        return view("login");
    }

    public function login_post(Request $request)
    {
        if (auth()->attempt(request(['email', 'password'])) == false) {
            return back()->withErrors([
                'message' => 'El usuario o la contraseña es incorrecto!'
            ]);
        }
        

        $user = auth()->user();
    
        if ($user->activo == 0) {
            auth()->logout();
    
            return back()->withErrors([
                'message' => 'Tu cuenta está desactivada. Por favor, contacta al administrador.'
            ]);
        }
        
    
        if ($user->rol == 'Agencia') {

            $usuarioActual = Auth::user();
            $nombre = $usuarioActual->name;
            $rol = $usuarioActual->rol;
            
            date_default_timezone_set('America/Bogota');

            $fechaHoraActual = date('Y-m-d H:i:s');
            $ip = $_SERVER['REMOTE_ADDR'];
            $agencia = $usuarioActual->agenciau;                  
            $login = DB::insert("INSERT INTO auditoria (Hora_login, Usuario_nombre, Usuario_Rol, AgenciaU, Acción_realizada, Hora_Accion, Cedula_Registrada, cerro_sesion, IP) VALUES (?, ?, ?, ?, 'Ingreso', ?, ?, ?, ?)", [
                $fechaHoraActual,
                $nombre,
                $rol,
                $agencia,
                null,
                null,
                null,
                $ip
            ]);




            return redirect()->to('entrance'); 

        
                      
        
        
        } elseif ($user->rol == 'Admin') {

            $usuarioActual = Auth::user();
            $nombre = $usuarioActual->name;
            $rol = $usuarioActual->rol;
            
            date_default_timezone_set('America/Bogota');

            $fechaHoraActual = date('Y-m-d H:i:s');
            $ip = $_SERVER['REMOTE_ADDR'];
            $agencia = $usuarioActual->agenciau;                  
            $login = DB::insert("INSERT INTO auditoria (Hora_login, Usuario_nombre, Usuario_Rol, AgenciaU, Acción_realizada, Hora_Accion, Cedula_Registrada, cerro_sesion, IP) VALUES (?, ?, ?, ?, 'Ingreso', ?, ?, ?, ?)", [
                $fechaHoraActual,
                $nombre,
                $rol,
                $agencia,
                null,
                null,
                null,
                $ip
            ]);
            return redirect()->to('/');
        }

    
        return redirect()->to('login');
    }

    public function destroy(Request $request){

        $usuarioActual = Auth::user();
        $nombre = $usuarioActual->name;
        $rol = $usuarioActual->rol;
        
        date_default_timezone_set('America/Bogota');

        $fechaHoraActual = date('Y-m-d H:i:s');
        $ip = $_SERVER['REMOTE_ADDR'];
        $agencia = $usuarioActual->agenciau;                  
        $login = DB::insert("INSERT INTO auditoria (Hora_login, Usuario_nombre, Usuario_Rol, AgenciaU, Acción_realizada, Hora_Accion, Cedula_Registrada, cerro_sesion, IP) VALUES (?, ?, ?, ?, 'Cerro_Sesion', ?, ?, ?, ?)", [
            null,
            $nombre,
            $rol,
            $agencia,
            null,
            null,
            $fechaHoraActual,
            $ip
        ]);



        auth()->logout();

        

        return redirect()->to('login');
    }
}
