<?php
namespace Instaticket\Http\Controllers\Configuracion;
use Instaticket\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use Validator;
use Auth;
use Instaticket\Entidades\Eventos;
use Instaticket\Entidades\TipoUrl;
use Instaticket\Entidades\ParametroGeneral;
use Instaticket\Entidades\ConfiguracionEvento;
use Instaticket\Entidades\LogEventos;
use Instaticket\Entidades\CicloEvento;
use Illuminate\Support\Facades\Log;
class ConfiguracionEventoController extends Controller {
    /**
     * AUTOR: INNOVASYSTEM ECUADOR S.A
     * DESCRIPCION: MÉTODO REALIZA CARGAR PANTALLA: CATEGORIAS
     * @return type
     */
    public function index() {
        try {
            Log::info('Ingreso Interfaz CICLO EVENTO PREMIO, con usuario: ' . Auth::user()->usuario_username);
            $lstEventJuegosP = CicloEvento::buscarTodosCiclosEventosPuerta();
            $lstEventJuegosC = CicloEvento::buscarTodosCiclosEventosCarta();
            $lstTipoUrl = TipoUrl::buscarTipoUrl(1);
            $lstTipoUrlC = TipoUrl::buscarTipoUrl(2);
            $objTipoUrlVideo = ParametroGeneral::buscarParametroxNombre(ParametroGeneral::$tipoUrlVideo);
            if (empty($objTipoUrlVideo) || empty($objTipoUrlVideo->parametro_general_valor)) {
                Log::error('No se ha encontrado parámetro TIPO_URL_VIDEO.');
                Session::flash('messageError', 'No se ha encontrado el parámetro: TIPO_URL_VIDEO');
                return redirect('welcome');
            }
            $objTipoUrlSonido = ParametroGeneral::buscarParametroxNombre(ParametroGeneral::$tipoUrlSonido);
            if (empty($objTipoUrlSonido) || empty($objTipoUrlSonido->parametro_general_valor)) {
                Log::error('No se ha encontrado parámetro TIPO_URL_SONIDO.');
                Session::flash('messageError', 'No se ha encontrado el parámetro: TIPO_URL_SONIDO');
                return redirect('welcome');
            }
            return view('configuracion.configevento', compact('lstEventJuegosP','lstEventJuegosC','lstTipoUrl','lstTipoUrlC','objTipoUrlVideo','objTipoUrlSonido'));
        } catch (\Exception $ex) {
            Log::error('CONTROLLER Configuracion Evento /index' . $ex->getMessage());
            Session::flash('messageError', $ex->getMessage());
            return redirect('welcome');
        }
    }
    public function buscarConfiguracionEvento(Request $request) {
        try {
            if ($request->evento_id != null) {
                $lst = ConfiguracionEvento::buscarConfiguracionEventoxEvento($request->evento_id,$request->juego);
                if (!empty($lst)) {
                    return $lst;
                }
            }
        } catch (\Exception $ex) {
            Log::error('CONTROLLER Configuracion Evento/buscarConfiguracionEvento' . $ex->getMessage());
            Session::flash('messageError', $ex->getMessage());
            return redirect()->back();
        }
    }
    public function guardarConfiguracionEvento(Request $request) {
         try {
            $objRutaRecursos = ParametroGeneral::buscarParametroxNombre(ParametroGeneral::$rutaRecursos);
            if (empty($objRutaRecursos) || empty($objRutaRecursos->parametro_general_valor)) {
                Log::error('No se ha encontrado parámetro RUTA_RECURSOS.');
                Session::flash('messageError', 'No se ha encontrado el parámetro: RUTA_RECURSOS');
                return redirect()->back();
            }
            $objTipoUrlVideo = ParametroGeneral::buscarParametroxNombre(ParametroGeneral::$tipoUrlVideo);
            if (empty($objTipoUrlVideo) || empty($objTipoUrlVideo->parametro_general_valor)) {
                Log::error('No se ha encontrado parámetro TIPO_URL_VIDEO.');
                Session::flash('messageError', 'No se ha encontrado el parámetro: TIPO_URL_VIDEO');
                return redirect()->back();
            }
            $objTipoUrlSonido = ParametroGeneral::buscarParametroxNombre(ParametroGeneral::$tipoUrlSonido);
            if (empty($objTipoUrlSonido) || empty($objTipoUrlSonido->parametro_general_valor)) {
                Log::error('No se ha encontrado parámetro TIPO_URL_SONIDO.');
                Session::flash('messageError', 'No se ha encontrado el parámetro: TIPO_URL_SONIDO');
                return redirect()->back();
            }
            $objTipoUrlFondo = ParametroGeneral::buscarParametroxNombre(ParametroGeneral::$tipoUrlFondo);
            if (empty($objTipoUrlFondo) || empty($objTipoUrlFondo->parametro_general_valor)) {
                Log::error('No se ha encontrado parámetro TIPO_URL_FONDO.');
                Session::flash('messageError', 'No se ha encontrado el parámetro: TIPO_URL_FONDO');
                return redirect()->back();
            }
            $objTipoUrlPrimeraPuerta = ParametroGeneral::buscarParametroxNombre(ParametroGeneral::$tipoUrlPrimeraPuerta);
            if (empty($objTipoUrlPrimeraPuerta) || empty($objTipoUrlPrimeraPuerta->parametro_general_valor)) {
                Log::error('No se ha encontrado parámetro TIPO_URL_PRIMERA_PUERTA.');
                Session::flash('messageError', 'No se ha encontrado el parámetro: TIPO_URL_PRIMERA_PUERTA');
                return redirect()->back();
            } 
            $objTipoUrlSegundaPuerta = ParametroGeneral::buscarParametroxNombre(ParametroGeneral::$tipoUrlSegundaPuerta);
            if (empty($objTipoUrlSegundaPuerta) || empty($objTipoUrlSegundaPuerta->parametro_general_valor)) {
                Log::error('No se ha encontrado parámetro TIPO_URL_SEGUNDA_PUERTA.');
                Session::flash('messageError', 'No se ha encontrado el parámetro: TIPO_URL_SEGUNDA_PUERTA');
                return redirect()->back();
            }
            $objTipoUrlTerceraPuerta = ParametroGeneral::buscarParametroxNombre(ParametroGeneral::$tipoUrlTerceraPuerta);
            if (empty($objTipoUrlTerceraPuerta) || empty($objTipoUrlTerceraPuerta->parametro_general_valor)) {
                Log::error('No se ha encontrado parámetro TIPO_URL_TERCERA_PUERTA.');
                Session::flash('messageError', 'No se ha encontrado el parámetro: TIPO_URL_TERCERA_PUERTA');
                return redirect()->back();
            }
            $obj = new ConfiguracionEvento();
            \DB::transaction(function () use ($request, $obj, $objRutaRecursos, $objTipoUrlVideo, $objTipoUrlSonido,$objTipoUrlFondo,$objTipoUrlPrimeraPuerta,$objTipoUrlSegundaPuerta,$objTipoUrlTerceraPuerta) {
                $obj->evento_id = $request->evento_id;
                $obj->tipo_url_id = $request->tipo_url_id;
                $obj->tipo_juego_id = $request->tipo_juego_id;
                $fileimagen = $request->file('configuracion_evento_url');
                if (!empty($fileimagen)) {
                                if($request->tipo_url_id == $objTipoUrlVideo->parametro_general_valor){
                                    $nombre = 'eventos/videos/Configuracionvideoevento_' . $fileimagen->getClientOriginalName();
                                }
                                else{
                                    if($request->tipo_url_id == $objTipoUrlSonido->parametro_general_valor){
                                        $nombre = 'eventos/sonidos/Configuracionsonidoevento_' . $fileimagen->getClientOriginalName();
                                    }
                                    else{
                                        $nombre = 'eventos/imagenes/Configuracionimagenevento_' . $fileimagen->getClientOriginalName();
                                    }
                                }
                                $array = explode(".", $nombre);
                                $extencion = end($array);
                                $tamañoArchivoByte = filesize($fileimagen);
                                if($request->tipo_url_id == $objTipoUrlVideo->parametro_general_valor){
                                    if ($tamañoArchivoByte > 15437184) {
                                            Log::error('El tamaño del video supera los 15MB.');
                                            Session::flash('messageError', 'El tamaño del video supera los 15Mb.');
                                            return redirect()->back();
                                    }
                                }
                                else{
                                    if($request->tipo_url_id == $objTipoUrlSonido->parametro_general_valor){
                                        if ($tamañoArchivoByte > 5048576) {
                                                Log::error('El tamaño del sonido supera los 5MB.');
                                                Session::flash('messageError', 'El tamaño del sonido supera los 5Mb.');
                                                return redirect()->back();
                                        }    
                                    }
                                    else{
                                        if($request->tipo_url_id == $objTipoUrlFondo->parametro_general_valor){
                                            if ($tamañoArchivoByte > 204800) {
                                                    Log::error('El tamaño del fondo supera los 200kb.');
                                                    Session::flash('messageError', 'El tamaño del fondo supera los 200kb.');
                                                    return redirect()->back();       
                                            }            
                                        }
                                        else{
                                            if($request->tipo_url_id == $objTipoUrlPrimeraPuerta->parametro_general_valor){
                                                if ($tamañoArchivoByte > 2097152) {
                                                        Log::error('El tamaño de la primera puerta supera los 2MB.');
                                                        Session::flash('messageError', 'El tamaño de la primera puerta supera los 2MB.');
                                                        return redirect()->back();               
                                                }                   
                                            }
                                            else{
                                                if($request->tipo_url_id == $objTipoUrlSegundaPuerta->parametro_general_valor){
                                                    if ($tamañoArchivoByte > 2097152) {
                                                            Log::error('El tamaño de la segunda puerta supera los 2MB.');
                                                            Session::flash('messageError', 'El tamaño de la segunda puerta supera los 2MB.');
                                                            return redirect()->back();
                                                    }
                                                }
                                                else{
                                                    if($request->tipo_url_id == $objTipoUrlTerceraPuerta->parametro_general_valor){
                                                        if ($tamañoArchivoByte > 2097152) {
                                                                Log::error('El tamaño de la tercera puerta supera los 2MB.');
                                                                Session::flash('messageError', 'El tamaño de la tercera puerta supera los 2MB.');
                                                                return redirect()->back();
                                                        }
                                                    }
                                                    else{
                                                        if ($tamañoArchivoByte > 4097152) {
                                                                Log::error('El tamaño de la imagen supera los 100kb.');
                                                                Session::flash('messageError', 'El tamaño de la imagen supera los 100kb.');
                                                                return redirect()->back();
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                                $ubicacionTemporal = NULL;
                                if ($fileimagen->isLink()) {
                                    $ubicacionTemporal = $fileimagen->getLinkTarget();
                                } else {
                                    $ubicacionTemporal = $fileimagen->getRealPath();
                                }
                                $directorio = $objRutaRecursos->parametro_general_valor;
                                if ((!is_dir($directorio)) || (!file_exists($directorio))) {
                                    Log::error('El directorio ' . $directorio . ' no existe');
                                    Session::flash('messageError', 'El directorio ' . $directorio . ' no existe');
                                    return redirect()->back();
                                }
                                if (!is_writable($directorio)) {
                                    Log::error('No tiene acceso a grabar en el directorio ' . $directorio);
                                    Session::flash('messageError', 'No tiene acceso a grabar en el directorio ' . $directorio);
                                    return redirect()->back();
                                }
                                $obj->configuracion_evento_url = $nombre;
                                $obj->save();
                                $objC = $lstConfoguracionEventoxEvento = ConfiguracionEvento::with('tipoUrl','evento')->where('configuracion_evento_id', $obj->configuracion_evento_id)->first();
                                if($request->tipo_url_id == $objTipoUrlVideo->parametro_general_valor){
                                    $objC->configuracion_evento_url = 'eventos/videos/configuracionevento_'. $objC->configuracion_evento_id .'_evento_' . $objC->evento_id . '.' . $extencion;
                                    $nombre2 = 'eventos/videos/configuracionevento_'. $objC->configuracion_evento_id .'_evento_' . $objC->evento_id . '.' . $extencion;
                                }
                                else{
                                    if($request->tipo_url_id == $objTipoUrlSonido->parametro_general_valor){
                                        $objC->configuracion_evento_url = 'eventos/sonidos/configuracionevento_'. $objC->configuracion_evento_id .'_evento_' . $objC->evento_id . '.' . $extencion;
                                        $nombre2 = 'eventos/sonidos/configuracionevento_'. $objC->configuracion_evento_id .'_evento_' . $objC->evento_id . '.' . $extencion;
                                    }
                                    else{
                                        $objC->configuracion_evento_url = 'eventos/imagenes/configuracionevento_'. $objC->configuracion_evento_id .'_evento_' . $objC->evento_id . '.' . $extencion;
                                        $nombre2 = 'eventos/imagenes/configuracionevento_'. $objC->configuracion_evento_id .'_evento_' . $objC->evento_id . '.' . $extencion;
                                    }
                                }
                                move_uploaded_file($ubicacionTemporal, $directorio . $nombre2);
                                $objC->save();
                                $var = 'EVENTO_ID: ' . $objC->evento_id .
                                        ' |NOMBRE: ' . strtoupper($objC->evento->evento_nombre) .
                                        ' |CONFIGURACION EVENTO: ' . $objC->configuracion_evento_id .
                                        ' |TIPO URL: ' . $objC->tipo_url_id .' - '.$objC->tipoUrl->tipo_url_nombre.
                                        ' |IMAGEN: ' . $objC->configuracion_evento_url ;
                                $log = new LogEventos();
                                $log->registro_nuevo = $var;
                                $log->id_tabla = $obj->evento_id;
                                $log->tipo_accion = 'A';
                                $log->save();
                               Session::flash('message', 'CONFIGURACION EVENTO:  ha sido registrado con éxito.');
                } else {
                    Session::flash('messageError', 'No se ha cargado la imagen del evento.');
                    return redirect()->back();
                }
            });
            return redirect()->back();
        } catch (\Exception $ex) {
            \DB::rollback();
            Log::error('CONTROLLER Eventos/guardarEvento' . $ex->getMessage());
            Session::flash('messageError', $ex->getMessage());
            return redirect()->back();
        }
    }
    public function eliminarConfiguracionEvento(Request $request) {
         try {
            \DB::transaction(function () use ($request) {
                $objC = ConfiguracionEvento::with('tipoUrl','evento')->where('configuracion_evento_id', $request->configuracion_evento_id)->first();
                $objC->delete();
                $var = 'EVENTO_ID: ' . $objC->evento->evento_id .
                        ' |NOMBRE: ' . strtoupper($objC->evento->evento_nombre) .
                        ' |CONFIGURACION EVENTO: ' . $objC->configuracion_evento_id .
                        ' |TIPO URL: ' . $objC->tipo_url_id .' - '.$objC->tipoUrl->tipo_url_nombre.
                        ' |IMAGEN: ' . $objC->configuracion_evento_url ;
                $log = new LogEventos();
                $log->registro_nuevo = $var;
                $log->id_tabla = $objC->evento->evento_id;
                $log->tipo_accion = 'E';
                $log->save();
               Session::flash('message', 'CONFIGURACION EVENTO:  ha sido eliminado con éxito.');
            });
            return redirect()->back();
        } catch (\Exception $ex) {
            \DB::rollback();
            Log::error('CONTROLLER Eventos/eliminarConfiguracionEvento' . $ex->getMessage());
            Session::flash('messageError', $ex->getMessage());
            return redirect()->back();
        }
    }
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
}
