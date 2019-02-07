<?php

namespace Instaticket\Http\ViewComposers;


use Instaticket\Entidades\Estados;
use Illuminate\Contracts\View\View;
use Instaticket\Entidades\ParametroGeneral;
use Instaticket\Entidades\CicloEvento;
use Instaticket\Entidades\Eventos;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;
use Auth;

class GeneralComposer {

    /**
     * Bind data to the view.
     * @param  View  $view
     * @return void
     */
    public function compose(View $view) {
        try {
            if (Auth::check()) {
                $objEstadoActivo = ParametroGeneral::buscarParametroxNombre(ParametroGeneral::$estadoActivo);
                $objEstadoInactivo = ParametroGeneral::buscarParametroxNombre(ParametroGeneral::$estadoInactivo);

                if (empty($objEstadoActivo) || empty($objEstadoActivo->parametro_general_valor)) {
                    Session::flash('messageError', 'No se ha encontrado el parámetro: ESTADO_ACTIVO');
                    return redirect('welcome');
                }
                if (empty($objEstadoInactivo) || empty($objEstadoInactivo->parametro_general_valor)) {
                    Session::flash('messageError', 'No se ha encontrado el parámetro: ESTADO_INACTIVO');
                    return redirect('welcome');
                }
                

                $estadoActivo = $objEstadoActivo->parametro_general_valor;
                $lstCodEstado = [$objEstadoActivo->parametro_general_valor, $objEstadoInactivo->parametro_general_valor];
                //$lstCodEstado2 = [$objEstadoNoLeido->valor];
                $lstEstado = Estados::whereIn('estado_id', $lstCodEstado)->get();
                
                $objUsuarioAdministrador = ParametroGeneral::buscarParametroxNombre(ParametroGeneral::$tipoUsuarioAdmin);
                if (empty($objUsuarioAdministrador) || empty($objUsuarioAdministrador->parametro_general_valor)) {
                    Session::flash('messageError', 'No se ha encontrado el parámetro: TIPO_USUARIO_ADMIN');
                    return redirect('welcome');
                }
               
                $objPGeneral = ParametroGeneral::buscarParametroxNombre(ParametroGeneral::$cicloEventoUsuario);
                if (empty($objPGeneral) || empty($objPGeneral->parametro_general_valor)) {
                    Session::flash('messageError', "No se ha encontrado el parámetro: EVENTO_USUARIO");
                    return redirect('welcome');
                }
                
                $objCicloEvento = CicloEvento::with('evento')->where('ciclo_evento_id', $objPGeneral->parametro_general_valor)->first();
                
                if ( $objCicloEvento == null || empty($objCicloEvento) || empty($objCicloEvento->evento_id)) {
                        $objEvento= new Eventos();
                        $objEvento->evento_nombre = "No existe Evento";
                }
                else{
                    $objEvento = Eventos::where('evento_id', $objCicloEvento->evento_id)->first();
                
                    if ( $objEvento ==null || empty($objEvento) || empty($objEvento->evento_id)) {
                            $objEvento= new Eventos();
                            $objEvento->evento_nombre = "No existe Evento";
                    }
                }
               
                
                $objUrlRecursos = ParametroGeneral::buscarParametroxNombre(ParametroGeneral::$urlRecursos);
                if (empty($objUrlRecursos) || empty($objUrlRecursos->parametro_general_valor)) {
                     Log::error('No se ha encontrado parámetro URL_RECURSOS.');
                     Session::flash('messageError', 'No se ha encontrado el parámetro: URL_RECURSOS');
                     return redirect('welcome');
                 }
                 
                $view->with('lstEstado', $lstEstado)->with('estadoActivo', $estadoActivo)
                        ->with('objUsuarioAdministrador', $objUsuarioAdministrador)->with('objEvento', $objEvento)
                        ->with('objUrlRecursos', $objUrlRecursos);
                
                //$lstCodTipo = [$objUsuarioAdministrador->parametro_general_valor, $objUsuarioRegistrador->parametro_general_valor];
                //$lstTipoUsuarios = TipoUsuarios::whereIn('tipo_usuario_id', $lstCodTipo)->get();

                
                
            }
        } catch (Exception $ex) {
            Log::error('COMPOSER General/compose'. $ex->getMessage());
            Session::flash('messageError', $ex->getMessage());
            return redirect()->back();
        }
    }
	
	function obtenetIp(){
		$ip = "";
       if(isset($_SERVER))
       {
           if (!empty($_SERVER['HTTP_CLIENT_IP'])) 
           {
               $ip=$_SERVER['HTTP_CLIENT_IP'];
            }
            elseif(!empty($_SERVER['HTTP_X_FORWARDED_FOR']))
            {
                $ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
            }
            else
            {
                $ip=$_SERVER['REMOTE_ADDR'];
            }
       }
       else
       {
            if ( getenv( 'HTTP_CLIENT_IP' ) )
            {
                $ip = getenv( 'HTTP_CLIENT_IP' );
            }
            elseif( getenv( 'HTTP_X_FORWARDED_FOR' ) )
            {
                $ip = getenv( 'HTTP_X_FORWARDED_FOR' );
            }
            else
            {
                $ip = getenv( 'REMOTE_ADDR' );
            }
       }  
        // En algunos casos muy raros la ip es devuelta repetida dos veces separada por coma 
       if(strstr($ip,','))
       {
            $ip = array_shift(explode(',',$ip));
       }
       return $ip;
	}

}
