<?php
namespace Instaticket\Http\Controllers\Mantenimientos;
use Instaticket\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use Auth;
use Validator;
use Instaticket\Entidades\Premios;
use Instaticket\Entidades\Categoria;
use Instaticket\Entidades\ParametroGeneral;
use Instaticket\Entidades\LogPremios;
use Illuminate\Support\Facades\Log;
class PremiosController extends Controller {
    /**
     * AUTOR: INNOVASYSTEM ECUADOR S.A
     * DESCRIPCION: MÉTODO REALIZA CARGAR PANTALLA: CATEGORIAS
     * @return type
     */
    public function index() {
        try {
            Log::info('Ingreso Interfaz PREMIOS, con usuario: ' . Auth::user()->usuario_username);
            $lstPremios = Premios::buscarTodosPremios();
            $lstCategoriasActivas = Categoria::buscarTodasCategoriasActivas();
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
            return view('mantenimientos.premio', compact('lstPremios','objRutaRecursos','objUrlRecursos','lstCategoriasActivas'));
        } catch (\Exception $ex) {
            Log::error('CONTROLLER Premios/index' . $ex->getMessage());
            Session::flash('messageError', $ex->getMessage());
            return redirect('welcome');
        }
    }
    public function validarPremio(Request $request) {
        try {
            $lstError = '';
            $messages = [
                'premio_nombre.required' => 'El campo nombre es requerido.',
                'premio_nombre.max' => 'El campo nombre debe tener un tamaño de 45 caracteres.',
                'premio_nombre.unique' => 'Nombre ' . $request->premio_nombre . ' ya se encuentra registrado.',
                'premio_descripcion.required' => 'El campo descripción es requerido.',
                'premio_descripcion.max' => 'El campo descripción debe tener un tamaño de 250 caracteres.',
                'estado_id.required' => 'El campo estado es requerido.',
                'estado_id.max' => 'El campo estado debe tener un tamaño de 11 caracteres.',
                'categoria_id.required' => 'El campo estado es requerido.',
                'categoria_id.max' => 'El campo estado debe tener un tamaño de 11 caracteres.',
                'premio_url_imagen' => 'El campo imagen es requerido.',
            ];
            if (!empty($request->premio_id)) {
                $validator = Validator::make($request->all(), [
                            'premio_nombre' => 'max:45|required|unique:premio,premio_nombre,' . $request->premio_id . ',premio_id',
                            'premio_descripcion' => 'max:250|required|',
                            'categoria_id' => 'required|',
                            'estado_id' => 'required|',
                                ], $messages);
            } else {
                $validator = Validator::make($request->all(), [
                            'premio_nombre' => 'max:45|required|unique:premio,premio_nombre',
                            'premio_descripcion' => 'max:250|required|',
                            'estado_id' => 'required|',
                            'categoria_id' => 'required|',
                                ], $messages);
            }
            if ($validator->fails()) {
                $lstError = $validator->errors();
            } 
            return $lstError;
        } catch (\Exception $ex) {
            Log::error('CONTROLLER Premio/validarPremio' . $ex->getMessage());
            return array($ex->getMessage());
        }
    }
    public function guardarPremio(Request $request) {
         try { 
            $objRutaRecursos = ParametroGeneral::buscarParametroxNombre(ParametroGeneral::$rutaRecursos);
            if (empty($objRutaRecursos) || empty($objRutaRecursos->parametro_general_valor)) {
                Log::error('No se ha encontrado parámetro RUTA_RECURSOS.');
                Session::flash('messageError', 'No se ha encontrado el parámetro: RUTA_RECURSOS');
                return redirect()->back();
            }
            $obj = new Premios();
            \DB::transaction(function () use ($request, $obj, $objRutaRecursos) {
                $obj->premio_nombre = strtoupper($request->premio_nombre);
                $obj->premio_descripcion = $request->premio_descripcion;
                $obj->categoria_id = $request->categoria_id;
                $obj->estado_id = $request->estado_id;
                $fileimagen = $request->file('premio_url_imagen');
                if (!empty($fileimagen)) {   
                                $nombre = 'premios/imagenes/premio_' . $fileimagen->getClientOriginalName();
                                $array = explode(".", $nombre);
                                $extencion = end($array);
                                $tamañoArchivoByte = filesize($fileimagen);
                                if ($tamañoArchivoByte > 602400) {
                                    Log::error('El tamaño de la imagen supera los 600kb.');
                                    Session::flash('messageError', 'El tamaño de la imagen supera los 600kb.');
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
                                $obj->premio_url_imagen = $nombre;
                                $obj->save();
                                $objC = Premios::with('estado','categoria')->find($obj->premio_id);
                                $objC->premio_url_imagen = 'premios/imagenes/premio_' . $objC->premio_id . '.' . $extencion;
                                $nombre2 = 'premios/imagenes/premio_' . $objC->premio_id . '.' . $extencion;
                                move_uploaded_file($ubicacionTemporal, $directorio . $nombre2);
                                $objC->save();
                                $var = 'PREMIO_ID: ' . $objC->premio_id .
                                        ' |NOMBRE: ' . strtoupper($objC->premio_nombre) .
                                        ' |DESCRIPCIÓN: ' . $objC->premio_descripcion .
                                        ' |CATEGORÍA: ' . $obj->categoria_id . '-' . $obj->categoria->categoria_nombre .
                                        ' |IMAGEN: ' . $objC->premio_url_imagen .
                                        ' |ESTADO: ' . $objC->estado_id . '-' . $objC->estado->estado_nombre;
                                $log = new LogPremios();
                                $log->registro_nuevo = $var;
                                $log->id_tabla = $obj->premio_id;
                                $log->tipo_accion = 'I';
                                $log->save();
                                Session::flash('message', 'PREMIO: ' . strtoupper($request->premio_nombre) . ' ha sido registrado con éxito.');
                } else {
                    Session::flash('messageError', 'No se ha cargado la imagen del premio.');
                    return redirect()->back();
                }
                Session::flash('message', 'PREMIO: ' . strtoupper($obj->premio_nombre) . ' ha sido registrado con éxito.');
            });
            Session::flash('message', 'PREMIO: ' . strtoupper($obj->premio_nombre) . ' ha sido registrado con éxito.');
            return redirect()->back();
        } catch (\Exception $ex) {
            \DB::rollback();
            Log::error('CONTROLLER Premio/guardarPremio' . $ex->getMessage());
            Session::flash('messageError', $ex->getMessage());
            return redirect()->back();
        }
    }
    public function buscarPremio(Request $request) {
        try {
            if ($request->premio_id != null) {
                $obj = Premios::with('estado', 'categoria')->find($request->premio_id);
                $objUrlRecursos = ParametroGeneral::buscarParametroxNombre(ParametroGeneral::$urlRecursos);
                if (empty($obj) || empty($objUrlRecursos) || empty($objUrlRecursos->parametro_general_valor)) {
                    return '';
                } else {
                    $ruta_imagenes = $objUrlRecursos->parametro_general_valor;
                    return array($obj, $ruta_imagenes);
                }
            }
        } catch (\Exception $ex) {
            Log::error('CONTROLLER Premios/buscarPremio' . $ex->getMessage());
            Session::flash('messageError', $ex->getMessage());
            return redirect()->back();
        }
    }
    public function actualizarPremio(Request $request) {
        try {
            $objRutaRecursos = ParametroGeneral::buscarParametroxNombre(ParametroGeneral::$rutaRecursos);
            if (empty($objRutaRecursos) || empty($objRutaRecursos->parametro_general_valor)) {
                Log::error('No se ha encontrado parámetro RUTA_RECURSOS.');
                Session::flash('messageError', 'No se ha encontrado el parámetro: RUTA_RECURSOS');
                return redirect()->back();
            }
            if ($request->premio_id != null) {
                $obj = Premios::with('estado','categoria')->find($request->premio_id);
                if (!empty($obj)) {
                    $var = 'PREMIO_ID: ' . $obj->premio_id .
                            ' |NOMBRE: ' . strtoupper($obj->premio_nombre) .
                            ' |DESCRIPCIÓN: ' . $obj->premio_descripcion .
                            ' |CATEGORÍA: ' . $obj->categoria_id . '-' . $obj->categoria->categoria_nombre .
                            ' |IMAGEN: ' . $obj->premio_url_imagen .
                            ' |ESTADO: ' . $obj->estado_id . '-' . $obj->estado->estado_nombre;
                    \DB::transaction(function () use ($request, $obj, $var, $objRutaRecursos) {
                        $obj->premio_nombre = strtoupper($request->premio_nombre);
                        $obj->premio_descripcion = $request->premio_descripcion;
                        $obj->categoria_id = $request->categoria_id;
                        $obj->estado_id = $request->estado_id;
                        $file = $request->file('premio_url_imagen');
                        if (!empty($file)) {
                            $tamañoArchivoByte = filesize($file);
                            if ($tamañoArchivoByte > 102400) {
                                Log::error('El tamaño de la imagen supera los 100kb.');
                                Session::flash('messageError', 'El tamaño de la imagen supera los 100kb.');
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
                            $imagen = 'premios/imagenes/premio_' . $file->getClientOriginalName();
                            $array = explode(".", $imagen);
                            $extencion = end($array);
                            $nombre = 'premios/imagenes/premio_' . $obj->premio_id . '.' . $extencion;
                            move_uploaded_file($ubicacionTemporal, $directorio . $nombre);
                            $obj->premio_url_imagen = $nombre;
                        }
                        $obj->save();
                            $objC = Premios::with('estado','categoria')->find($request->premio_id);
                            $var2 = 'PREMIO_ID: ' . $objC->premio_id .
                                    ' |NOMBRE: ' . strtoupper($objC->premio_nombre) .
                                    ' |DESCRIPCIÓN: ' . $objC->premio_descripcion .
                                    ' |CATEGORÍA: ' . $obj->categoria_id . '-' . $obj->categoria->categoria_nombre .
                                    ' |IMAGEN: ' . $objC->premio_url_imagen .
                                    ' |ESTADO: ' . $objC->estado_id . '-' . $objC->estado->estado_nombre;
                            $log = new LogPremios();
                            $log->registro_anterior = $var;
                            $log->registro_nuevo = $var2;
                            $log->id_tabla = $obj->premio_id;
                            $log->tipo_accion = 'A';
                            $log->save();
                            Session::flash('message', 'Premio: ' . strtoupper($request->premio_nombre) . ' ha sido actualizada con éxito.');
                    });
                    Session::flash('message', 'Premio: ' . strtoupper($request->premio_nombre) . ' ha sido actualizada con éxito.');
                } else {
                    Session::flash('messageError', "No se ha encontrado el premio a actualizar.");
                }
            } else {
                Session::flash('messageError', "No se ha seleccionado el premio.");
            }
            return redirect()->back();
        } catch (\Exception $ex) {
            \DB::rollback();
            Log::error('CONTROLLER Premios/actualizarPremio' . $ex->getMessage());
            Session::flash('messageError', $ex->getMessage());
            return redirect()->back();
        }
    }
}
