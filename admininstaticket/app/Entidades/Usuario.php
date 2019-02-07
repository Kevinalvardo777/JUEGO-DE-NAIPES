<?php

namespace Instaticket\Entidades;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Session;

class Usuario extends Authenticatable {

    protected $table = 'usuario';
    protected $primaryKey = 'usuario_id';
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'usuario_username', 
        'usuario_password',
        'usuario_nombre', 
        'usuario_apellido',
        'usuario_telefono', 
        'usuario_email',
        'tipo_usuario_id', 
        'estado_id',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'usuario_password', 'remember_token',
    ];

   
    public function tipoUsuario() {
        return $this->belongsTo('Instaticket\Entidades\TipoUsuarios');
    }

    public function logUsuario() {
        return $this->hasMany('Instaticket\Entidades\LogUsuarios', 'id_tabla');
    }

    public function estado() {
        return $this->belongsTo('Instaticket\Entidades\Estados');
    }

    //METODOS

    public static function buscarTodasUsuarios($tipoUsuario) {
        try {
            $lst = Usuario::with('tipoUsuario', 'estado')
                    ->where('tipo_usuario_id', $tipoUsuario)
                    ->get();
            return $lst;
        } catch (\Exception $ex) {
            Session::flash('messageError', $ex->getMessage());
            return redirect()->back();
        }
    }
    
    
   /* public static function buscarFiltroUsuarios($fecha_desde,$fecha_hasta) {
        try {
            $lst = Usuarios::with('tipoUsuario', 'estado', 'logUsuario', 'usuarioIngresa', 'usuarioModifica', 'informacionUsuario')
                    ->where('tipo_usuario_id', 1)
                    ->whereBetween('fecha_ingresa', array($fecha_desde, $fecha_hasta))
                    ->get();
            return $lst;
        } catch (\Exception $ex) {
            Session::flash('messageError', $ex->getMessage());
            return redirect()->back();
        }
    }
    
    
    public static function buscarFiltroPremioUsuarios($fecha_desde,$fecha_hasta) {
        try {
            $lst =  \DB::table('usuarios')
                    ->select(\DB::raw('SUM(IF(premio_usuarios.estado_id = 4,1,0)) retirado, SUM(IF(premio_usuarios.estado_id = 3,1,0)) por_retirar, SUM(IF(premio_usuarios.estado_id = 7,1,0)) sin_terminar, estados.nombre as estado, informacion_usuarios.*, usuarios.* '))
//                  ->where('tipo_usuario_id', 1)
                    ->join('premio_usuarios','usuarios.usuario_id','=','premio_usuarios.usuario_id')
                    ->join('estados','usuarios.estado_id','=','estados.estado_id')
                    ->join('informacion_usuarios','usuarios.usuario_id','=','informacion_usuarios.usuario_id')
                    ->whereBetween('usuarios.fecha_ingresa', array($fecha_desde, $fecha_hasta))
                    //->where('premio_usuarios.estado_id', 3)
                    ->groupBy('usuarios.usuario_id')
                    ->get();
            return $lst;
        } catch (\Exception $ex) {
            Session::flash('messageError', $ex->getMessage());
            return redirect()->back();
        }
    }
    
    
    public static function buscarTotalUsuariosInactivos() {
        try {
             $valor = \DB::table('usuarios')
                     ->select(\DB::raw('count(*) as usuario_inactivo'))
                     ->where('estado_id', 2)
                     ->first();
            return $valor;
        } catch (\Exception $ex) {
            Session::flash('messageError', $ex->getMessage());
            return redirect()->back();
        }
    }
    
    
    public static function buscarTotalUsuariosJugadores() {
        try {
             $valor = \DB::table('tiraje_usuario')
                     ->select(\DB::raw('count(usuario_id)'))
                     ->groupBy('usuario_id')
                     ->get();
            return $valor;
        } catch (\Exception $ex) {
            Session::flash('messageError', $ex->getMessage());
            return redirect()->back();
        }
    }
    
    
    public static function buscarTotalUsuariosGanadores() {
        try {
             $valor = \DB::table('premio_usuarios')
                     ->select(\DB::raw('count(usuario_id) as total_ganadores'))
                     ->where('estado_id',4)
                     ->groupBy('usuario_id')
                     ->get();
            return $valor;
        } catch (\Exception $ex) {
            Session::flash('messageError', $ex->getMessage());
            return redirect()->back();
        }
    }*/

}
