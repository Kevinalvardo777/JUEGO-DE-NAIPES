<?php

namespace Instaticket\Entidades;

use Illuminate\Database\Eloquent\Model;

class ParametroGeneral extends Model
{
     protected $table = 'parametro_general';
    protected $primaryKey = 'parametro_general_id';
    public $timestamps = false;
        
    public static $estadoActivo = 'ESTADO_ACTIVO';
    public static $estadoInactivo = 'ESTADO_INACTIVO';
    public static $tipoUsuarioTelevisor = 'TIPO_USUARIO_TELEVISOR';
    public static $tipoUsuarioAdmin = 'TIPO_USUARIO_ADMIN';
    public static $tipoUsuarioRegistrador = 'TIPO_USUARIO_REGISTRADOR';
    public static $rutaRecursos = 'RUTA_RECURSOS';
    public static $urlRecursos = 'URL_RECURSOS';
    public static $tipoUrlVideo = 'TIPO_URL_VIDEO';
    public static $tipoUrlSonido = 'TIPO_URL_SONIDO';
    public static $cicloEventoUsuario = 'CICLO_EVENTO_USUARIO';
    public static $tipoUrlFondo = 'TIPO_URL_FONDO';
    public static $tipoUrlPrimeraPuerta = 'TIPO_URL_PRIMERA_PUERTA';
    public static $tipoUrlSegundaPuerta = 'TIPO_URL_SEGUNDA_PUERTA';
    public static $tipoUrlTerceraPuerta = 'TIPO_URL_TERCERA_PUERTA';
    public static $impresoraActiva = 'IMPRESORA_ACTIVA';
    
    

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'parametro_general_nombre',
        'parametro_general_valor',
        'parametro_general_descripcion'
    ];

    
    public static function buscarParametroxNombre($nombreParametro) {
        try {
            $lstParametro = ParametroGeneral::where('parametro_general_nombre',$nombreParametro)->first();
            return $lstParametro;
        } catch (\Exception $ex) {
            Session::flash('messageError', $ex->getMessage());
            return redirect()->back();
        }
    }
    
    public static function buscarTodosParametros() {
        try {
            $lstParametros = ParametroGeneral::get();
            return $lstParametros;
        } catch (\Exception $ex) {
            Session::flash('messageError', $ex->getMessage());
            return redirect()->back();
        }
    }
}
