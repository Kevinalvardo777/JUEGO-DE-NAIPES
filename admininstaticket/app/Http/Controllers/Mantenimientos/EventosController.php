<?php
namespace Instaticket\Http\Controllers\Mantenimientos;
use Instaticket\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use Validator;
use Auth;
use Instaticket\Entidades\Eventos;
use Instaticket\Entidades\LogEventos;
use Instaticket\Entidades\ParametroGeneral;
use Illuminate\Support\Facades\Log;
class EventosController extends Controller {
    /**
     * AUTOR: INNOVASYSTEM ECUADOR S.A
     * DESCRIPCION: MÉTODO REALIZA CARGAR PANTALLA: CATEGORIAS
     * @return type
     */
    public function index() {
        try {
            Log::info('Ingreso Interfaz EVENTOS, con usuario: ' . Auth::user()->usuario_username);
            $lstEventos = Eventos::buscarTodosEventos();
            $objRutaRecursos = ParametroGeneral::buscarParametroxNombre(ParametroGeneral::$rutaRecursos);
            if (empty($objRutaRecursos) || empty($objRutaRecursos->parametro_general_valor)) {
                Log::error('No se ha encontrado parámetro RUTA_RECURSOS.');
                Session::flash('messageError', 'No se ha encontrado el parámetro: RUTA_RECURSOS');
                return redirect('welcome');
            }
            $objUrlRecursos = ParametroGeneral::buscarParametroxNombre(ParametroGeneral::$urlRecursos);
           if (empty($objUrlRecursos) || empty($objUrlRecursos->parametro_general_valor)) {
                Log::error('No se ha encontrado parámetro URL_RECURSOS.');
                Session::flash('messageError', 'No se ha encontrado el parámetro: URL_RECURSOS');
                return redirect('welcome');
            }
            return view('mantenimientos.evento', compact('lstEventos','objRutaRecursos','objUrlRecursos'));
        } catch (\Exception $ex) {
            Log::error('CONTROLLER Eventos/index' . $ex->getMessage());
            Session::flash('messageError', $ex->getMessage());
            return redirect('welcome');
        }
    }
    public function validarEvento(Request $request) {
        try {
            $lstError = '';
            $messages = [
                'evento_nombre.required' => 'El campo nombre es requerido.',
                'evento_nombre.max' => 'El campo nombre debe tener un tamaño de 45 caracteres.',
                'evento_nombre.unique' => 'Nombre ' . $request->evento_nombre . ' ya se encuentra registrado.',
                'evento_descripcion.required' => 'El campo descripción es requerido.',
                'evento_descripcion.max' => 'El campo descripción debe tener un tamaño de 250 caracteres.',
                'estado_id.required' => 'El campo estado es requerido.',
                'estado_id.max' => 'El campo estado debe tener un tamaño de 11 caracteres.',
                'evento_url_imagen' => 'El campo imagen es requerido.',
            ];
            if (!empty($request->evento_id)) {
                $validator = Validator::make($request->all(), [
                            'evento_nombre' => 'max:45|required|unique:evento,evento_nombre,' . $request->evento_id . ',evento_id',
                            'evento_descripcion' => 'max:250|required|',
                            'estado_id' => 'required|',
                                ], $messages);
            } else {
                $validator = Validator::make($request->all(), [
                            'evento_nombre' => 'max:45|required|unique:evento,evento_nombre',
                            'evento_descripcion' => 'max:250|required|',
                            'estado_id' => 'required|',
                                ], $messages);
            }
            if ($validator->fails()) {
                $lstError = $validator->errors();
            } 
            return $lstError;
        } catch (\Exception $ex) {
            Log::error('CONTROLLER Evento/validarEvento' . $ex->getMessage());
            return array($ex->getMessage());
        }
    }
    public function guardarEvento(Request $request) {
         try {
            $objRutaRecursos = ParametroGeneral::buscarParametroxNombre(ParametroGeneral::$rutaRecursos);
            if (empty($objRutaRecursos) || empty($objRutaRecursos->parametro_general_valor)) {
                Log::error('No se ha encontrado parámetro RUTA_RECURSOS.');
                Session::flash('messageError', 'No se ha encontrado el parámetro: RUTA_RECURSOS');
                return redirect()->back();
            }
            $obj = new Eventos();
            \DB::transaction(function () use ($request, $obj, $objRutaRecursos) {
                $obj->evento_nombre = strtoupper($request->evento_nombre);
                $obj->evento_descripcion = $request->evento_descripcion;
                $obj->evento_total_jugadas = 0;
                $obj->estado_id = $request->estado_id;
                $fileimagen = $request->file('evento_url_imagen');
                if (!empty($fileimagen)) {
                                $nombre = 'eventos/imagenes/evento_' . $fileimagen->getClientOriginalName();
                                $array = explode(".", $nombre);
                                $extencion = end($array);
                                $tamañoArchivoByte = filesize($fileimagen);
                                if ($tamañoArchivoByte > 102400) {
                                    Log::error('El tamaño de la imagen supera los 200kb.');
                                    Session::flash('messageError', 'El tamaño de la imagen supera los 200kb.');
                                    return redirect()->back();
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
                                $obj->evento_url_imagen = $nombre;
                                $obj->save();
                                $objC = Eventos::with('estado')->find($obj->evento_id);
                                $objC->evento_url_imagen = 'eventos/imagenes/evento_' . $objC->evento_id . '.' . $extencion;
                                $nombre2 = 'eventos/imagenes/evento_' . $objC->evento_id . '.' . $extencion;
                                move_uploaded_file($ubicacionTemporal, $directorio . $nombre2);
                                $objC->save();
                                $var = 'EVENTO_ID: ' . $objC->evento_id .
                                        ' |NOMBRE: ' . strtoupper($objC->evento_nombre) .
                                        ' |DESCRIPCIÓN: ' . $objC->evento_descripcion .
                                        ' |TOT. JUAGADAS: ' . $objC->evento_total_jugadas .
                                        ' |IMAGEN: ' . $objC->evento_url_imagen .
                                        ' |ESTADO: ' . $objC->estado_id . '-' . $objC->estado->estado_nombre;
                                $log = new LogEventos();
                                $log->registro_nuevo = $var;
                                $log->id_tabla = $obj->evento_id;
                                $log->tipo_accion = 'I';
                                $log->save();
                                Session::flash('message', 'EVENTO: ' . strtoupper($request->evento_nombre) . ' ha sido registrado con éxito.');
                } else {
                    Session::flash('messageError', 'No se ha cargado la imagen del evento.');
                    return redirect()->back();
                }
                Session::flash('message', 'EVENTO: ' . strtoupper($obj->evento_nombre) . ' ha sido registrado con éxito.');
            });
            Session::flash('message', 'EVENTO: ' . strtoupper($obj->evento_nombre) . ' ha sido registrado con éxito.');
            return redirect()->back();
        } catch (\Exception $ex) {
            \DB::rollback();
            Log::error('CONTROLLER Eventos/guardarEvento' . $ex->getMessage());
            Session::flash('messageError', $ex->getMessage());
            return redirect()->back();
        }
    }
    public function buscarEvento(Request $request) {
        try {
            if ($request->evento_id != null) {
                $obj = Eventos::find($request->evento_id);
                $objUrlRecursos = ParametroGeneral::buscarParametroxNombre(ParametroGeneral::$urlRecursos);
                if (empty($obj) || empty($objUrlRecursos) || empty($objUrlRecursos->parametro_general_valor)) {
                    return '';
                } else {
                    $ruta_imagenes = $objUrlRecursos->parametro_general_valor;
                    return array($obj, $ruta_imagenes);
                }
            }
        } catch (\Exception $ex) {
            Log::error('CONTROLLER Eventos/buscarEvento' . $ex->getMessage());
            Session::flash('messageError', $ex->getMessage());
            return redirect()->back();
        }
    }
    public function actualizarEvento(Request $request) {
        try {
            $objRutaRecursos = ParametroGeneral::buscarParametroxNombre(ParametroGeneral::$rutaRecursos);
            if (empty($objRutaRecursos) || empty($objRutaRecursos->parametro_general_valor)) {
                Log::error('No se ha encontrado parámetro RUTA_RECURSOS.');
                Session::flash('messageError', 'No se ha encontrado el parámetro: RUTA_RECURSOS');
                return redirect()->back();
            }
            if ($request->evento_id != null) {
                $obj = Eventos::with('estado')->find($request->evento_id);
                if (!empty($obj)) {
                    $var = 'EVENTO_ID: ' . $obj->evento_id .
                            ' |NOMBRE: ' . strtoupper($obj->evento_nombre) .
                            ' |DESCRIPCIÓN: ' . $obj->evento_descripcion .
                            ' |TOT. JUGADAS: ' . $obj->evento_total_jugadas .
                            ' |IMAGEN: ' . $obj->evento_url_imagen .
                            ' |ESTADO: ' . $obj->estado_id . '-' . $obj->estado->estado_nombre;
                    \DB::transaction(function () use ($request, $obj, $var, $objRutaRecursos) {
                        $obj->evento_nombre = strtoupper($request->evento_nombre);
                        $obj->evento_descripcion = $request->evento_descripcion;
                        $obj->evento_total_jugadas = $request->evento_total_jugadas;
                        $obj->estado_id = $request->estado_id;
                        $file = $request->file('evento_url_imagen');
                        if (!empty($file)) {
                            $tamañoArchivoByte = filesize($file);
                            if ($tamañoArchivoByte > 102400) {
                                Log::error('El tamaño de la imagen supera los 200kb.');
                                Session::flash('messageError', 'El tamaño de la imagen supera los 200kb.');
                                return redirect()->back();
                            }
                            $ubicacionTemporal = NULL;
                            if ($file->isLink()) {
                                $ubicacionTemporal = $file->getLinkTarget();
                            } else {
                                $ubicacionTemporal = $file->getRealPath();
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
                            $imagen = 'eventos/imagenes/evento_' . $file->getClientOriginalName();
                            $array = explode(".", $imagen);
                            $extencion = end($array);
                            $nombre = 'eventos/imagenes/evento_' . $obj->evento_id . '.' . $extencion;
                            move_uploaded_file($ubicacionTemporal, $directorio . $nombre);
                            $obj->evento_url_imagen = $nombre;
                        }
                        $obj->save();
                            $objC = Eventos::with('estado')->find($obj->evento_id);
                            $var2 = 'EVENTO_ID: ' . $objC->evento_id .
                                    ' |NOMBRE: ' . strtoupper($objC->evento_nombre) .
                                    ' |DESCRIPCIÓN: ' . $objC->evento_descripcion .
                                    ' |TOT. JUGADAS: ' . $objC->evento_total_jugadas .
                                    ' |IMAGEN: ' . $objC->evento_url_imagen .
                                    ' |ESTADO: ' . $objC->estado_id . '-' . $objC->estado->estado_nombre;
                            $log = new LogEventos();
                            $log->registro_anterior = $var;
                            $log->registro_nuevo = $var2;
                            $log->id_tabla = $obj->evento_id;
                            $log->tipo_accion = 'A';
                            $log->save();
                            Session::flash('message', 'Evento: ' . strtoupper($request->evento_nombre) . ' ha sido actualizada con éxito.');
                    });
                    Session::flash('message', 'Evento: ' . strtoupper($request->evento_nombre) . ' ha sido actualizada con éxito.');
                } else {
                    Session::flash('messageError', "No se ha encontrado el evento a actualizar.");
                }
            } else {
                Session::flash('messageError', "No se ha seleccionado el evento.");
            }
            return redirect()->back();
        } catch (\Exception $ex) {
            \DB::rollback();
            Log::error('CONTROLLER Eventos/actualizarEvento' . $ex->getMessage());
            Session::flash('messageError', $ex->getMessage());
            return redirect()->back();
        }
    }
}
