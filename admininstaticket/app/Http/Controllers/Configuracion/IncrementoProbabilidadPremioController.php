<?php
namespace Instaticket\Http\Controllers\Configuracion;
use Instaticket\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use Validator;
use Auth;
use Instaticket\Entidades\ParametroGeneral;
use Instaticket\Entidades\Categoria;
use Instaticket\Entidades\CicloEventoPremio;
use Instaticket\Entidades\IncrementoProbabilidadPremio;
use Instaticket\Entidades\LogIncrementoProbabilidadPremio;
use Instaticket\Entidades\Estados;
use Illuminate\Support\Facades\Log;
class IncrementoProbabilidadPremioController extends Controller {
    /**
     * AUTOR: INNOVASYSTEM ECUADOR S.A
     * DESCRIPCION: MÉTODO REALIZA CARGAR PANTALLA: CATEGORIAS
     * @return type
     */
    public function index() {
        try {
            Log::info('Ingreso Interfaz CICLO EVENTO, con usuario: ' . Auth::user()->usuario_username);
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
            $lstCategorias = Categoria::buscarTodasCategorias();
            $lstCicloEventoPremio = CicloEventoPremio::buscarTodosCiclosEventosPremios();
            $lstIncrementoProbabilidadesPremios = IncrementoProbabilidadPremio::buscarTodosIncrementoProbabilidadPremio();
            return view('configuracion.incrementoprobabilidadpremio', compact('objUrlRecursos','lstCategorias','lstCicloEventoPremio','lstIncrementoProbabilidadesPremios'));
        } catch (\Exception $ex) {
            Log::error('CONTROLLER IncrementoProbabilidad Premio/index' . $ex->getMessage());
            Session::flash('messageError', $ex->getMessage());
            return redirect('welcome');
        }
    }
    public function validarIncrementoProbabilidadPremio(Request $request) {
       try {
            $lstError = '';
            $array = array();
            $messages = [
                'inc_pro_pre_nombre.required' => 'El campo nombre es requerido.',
                'inc_pro_pre_nombre.max' => 'El campo nombre debe tener un tamaño de 45 caracteres.',
                'inc_pro_pre_nombre.unique' => 'Nombre ' . $request->inc_pro_pre_nombre . ' ya se encuentra registrado.',
                'inc_pro_pre_descripcion.required' => 'El campo descripción es requerido.',
                'inc_pro_pre_descripcion.max' => 'El campo descripción debe tener un tamaño de 250 caracteres.',
                'inc_pro_pre_cantidad.required' => 'El campo cantidad es requerido.',
                'inc_pro_pre_cantidad.max' => 'El campo cantidad debe tener un tamaño de 11 caracteres.',
                'inc_pro_pre_url_imagen.required' => 'El campo url imagen es requerido.',
                'inc_pro_pre_url_imagen.max' => 'El campo url imagen debe tener un tamaño de 250 caracteres.',
                'inc_pro_pre_porcentaje_probabilidad.required' => 'El campo probabilidad es requerido.',
                'inc_pro_pre_porcentaje_probabilidad.max' => 'El campo probabilidad debe tener un tamaño de 11 caracteres.',
                'inc_pro_pre_fecha_incremento.required' => 'El campo fecha es requerido.',
                'inc_pro_pre_fecha_incremento.max' => 'El campo fecha debe tener un tamaño de 250 caracteres.',
                'inc_pro_pre_hora_inicio_incremento.required' => 'El campo hora inicio es requerido.',
                'inc_pro_pre_hora_inicio_incremento.max' => 'El campo hora inicio debe tener un tamaño de 250 caracteres.',
                'inc_pro_pre_hora_fin_incremento.required' => 'El campo hora fin es requerido.',
                'inc_pro_pre_hora_fin_incremento.max' => 'El campo hora fin debe tener un tamaño de 250 caracteres.',
                'ciclo_evento_premio_id.required' => 'El campo premio es requerido.',
                'ciclo_evento_premio_id.max' => 'El campo premio debe tener un tamaño de 11 caracteres.',
                'categoria_id.required' => 'El campo categoría es requerido.',
                'categoria_id.max' => 'El campo categoría debe tener un tamaño de 11 caracteres.',
                'estado_id.required' => 'El campo estado es requerido.',
                'estado_id.max' => 'El campo estado debe tener un tamaño de 11 caracteres.',
            ];
            if (!empty($request->inc_pro_pre_id)) {
                $validator = Validator::make($request->all(), [
                            'inc_pro_pre_nombre' => 'max:45|required|unique:incremento_probabilidad_premio,inc_pro_pre_nombre,' . $request->inc_pro_pre_id . ',inc_pro_pre_id',
                            'inc_pro_pre_descripcion' => 'max:250|required|',
                            'inc_pro_pre_cantidad' => 'required|',
                            'inc_pro_pre_fecha_incremento' => 'required|',
                            'inc_pro_pre_hora_inicio_incremento' => 'required|',
                            'inc_pro_pre_hora_fin_incremento' => 'required|',
                            'inc_pro_pre_porcentaje_probabilidad' => 'required|',
                            'ciclo_evento_premio_id' => 'required',
                            'categoria_id' => 'required',
                            'estado_id' => 'required|',
                                ], $messages);
                if ($validator->fails()) {
                    $lstError = $validator->errors();
                } else {
                    if ($request->inc_pro_pre_cantidad <= 0) {
                        $msj = array("La cantidad debe ser mayor a 0.");
                        array_push($array, $msj);
                    }
                    if (($request->inc_pro_pre_porcentaje_probabilidad < 1) || ($request->inc_pro_pre_porcentaje_probabilidad > 100)) {
                        $msj = array("El porcentaje debe ser entre 1% y 100%.");
                        array_push($array, $msj);
                    }
                    $fechaActual = date("Y-m-d");
                    if ($request->inc_pro_pre_fecha_incremento < $fechaActual) {
                        $msj = array("La fecha Incentivo no debe ser menor a la fecha Actual.");
                        array_push($array, $msj);
                    }
                    $horaActual = date("H:i:s");
                    $incremento = IncrementoProbabilidadPremio::with('cicloEventoPremio','cicloEventoPremio.cicloEvento','cicloEventoPremio.cicloEvento.ciclo')->find($request->inc_pro_pre_id);
                    if ($incremento->inc_pro_pre_fecha_incremento >= $incremento->cicloEventoPremio->cicloEvento->ciclo->ciclo_fecha_inicio &&
                            $incremento->inc_pro_pre_fecha_incremento <= $incremento->cicloEventoPremio->cicloEvento->ciclo->ciclo_fecha_fin && 
                             $horaActual >= $incremento->inc_pro_pre_hora_inicio_incremento &&
                             $horaActual <= $incremento->inc_pro_pre_hora_fin_incremento ) {
                        if ($request->estado_id == Estados::$estadoInactivo) {
                            $msj = array("No es posible inactivar el incremento de probabilidad del premio.");
                            array_push($array, $msj);
                        }
                        if ($incremento->inc_pro_pre_cantidad > $request->inc_pro_pre_cantidad) {
                            $msj = array("No es posible actualizar la cantidad del incremento con un valor menor a ".$incremento->inc_pro_pre_cantidad." porque el incremento ya se encuentra en curso.");
                            array_push($array, $msj);
                        }
                        if ($incremento->inc_pro_pre_fecha_incremento != $request->inc_pro_pre_fecha_incremento) {
                            $msj = array("No es posible actualizar la fecha del incremento porque ya se encuentra en curso.");
                            array_push($array, $msj);
                        }
                        if ($incremento->categoria_id != $request->categoria_id) {
                            $msj = array("No es posible actualizar la categoría del incremento porque ya se encuentra en curso.");
                            array_push($array, $msj);
                        }
                        if ($incremento->inc_pro_pre_porcentaje_probabilidad != $request->inc_pro_pre_porcentaje_probabilidad) {
                            $msj = array("No es posible actualizar la probabilidad del incremento porque ya se encuentra en curso.");
                            array_push($array, $msj);
                        }
                        if ($incremento->ciclo_evento_premio_id != $request->ciclo_evento_premio_id) {
                            $msj = array("No es posible actualizar el ciclo evento del incremento porque ya se encuentra en curso.");
                            array_push($array, $msj);
                        }
                        if ($incremento->inc_pro_pre_hora_inicio_incremento != $request->inc_pro_pre_hora_inicio_incremento) {
                            $msj = array("No es posible actualizar la hora inicio del incremento porque ya se encuentra en curso.");
                            array_push($array, $msj);
                        }
                        if ($incremento->inc_pro_pre_hora_fin_incremento != $request->inc_pro_pre_hora_fin_incremento) {
                            $msj = array("No es posible actualizar la hora fin del incremento porque ya se encuentra en curso.");
                            array_push($array, $msj);
                        }
                    }
                    if (count($array) > 0) {
                        $lstError = $array;
                    }
                }
            } else {
                $validator = Validator::make($request->all(), [
                            'inc_pro_pre_nombre' => 'max:45|required|unique:incremento_probabilidad_premio,inc_pro_pre_nombre',
                            'inc_pro_pre_descripcion' => 'max:250|required|',
                            'inc_pro_pre_cantidad' => 'required|',
                            'inc_pro_pre_fecha_incremento' => 'required|',
                            'inc_pro_pre_hora_inicio_incremento' => 'required|',
                            'inc_pro_pre_hora_fin_incremento' => 'required|',
                            'inc_pro_pre_porcentaje_probabilidad' => 'required|',
                            'ciclo_evento_premio_id' => 'required',
                            'categoria_id' => 'required',
                            'estado_id' => 'required|',
                                ], $messages);
            }
            if ($validator->fails()) {
                $lstError = $validator->errors();
            } else {
                if ($request->inc_pro_pre_cantidad <= 0) {
                    $msj = array("La cantidad debe ser mayor a 0.");
                    array_push($array, $msj);
                }
                if (($request->inc_pro_pre_porcentaje_probabilidad < 1) || ($request->inc_pro_pre_porcentaje_probabilidad > 100)) {
                    $msj = array("El porcentaje debe ser entre 1% y 100%.");
                    array_push($array, $msj);
                }
                $fechaActual = date("Y-m-d");
                if ($request->inc_pro_pre_fecha_incremento < $fechaActual) {
                    $msj = array("La fecha del incremento de probabilidad del premio no debe ser menor a la fecha Actual.");
                    array_push($array, $msj);
                }
                $objCiEvePre = CicloEventoPremio::with('cicloEvento','cicloEvento.ciclo')->find($request->ciclo_evento_premio_id);
                if (empty($objCiEvePre) || empty($objCiEvePre->ciclo_evento_premio_id)) {
                    $msj = array("No se ha encontrado ciclo evento premio.");
                    array_push($array, $msj);
                }
                else{
                    if($objCiEvePre->ciclo_evento_premio_stock_disponible < $request->inc_pro_pre_cantidad){
                        $msj = array("La cantidad del incremento es mayor a el disponible de premios ".$objCiEvePre->ciclo_evento_premio_stock_disponible);
                        array_push($array, $msj);
                    }
                    if ($request->inc_pro_pre_fecha_incremento >= $objCiEvePre->cicloEvento->ciclo->ciclo_fecha_inicio &&
                            $request->inc_pro_pre_fecha_incremento <= $objCiEvePre->cicloEvento->ciclo->ciclo_fecha_fin  ) {
                       
                    }
                    else{
                        $msj = array("La fecha del incremento no corresponde dentro del ciclo: " .$objCiEvePre->cicloEvento->ciclo->ciclo_fecha_inicio." / ".$objCiEvePre->cicloEvento->ciclo->ciclo_fecha_fin);
                        array_push($array, $msj);
                    }
                }
                
                if (count($array) > 0) {
                    $lstError = $array;
                }
            }
            return $lstError;
        } catch (\Exception $ex) {
            Log::error('CONTROLLER IncrementoProbabilidadPremio/validarIncrementoProbabilidadPremio' . $ex->getMessage());
            return array($ex->getMessage());
        }
    }
    public function guardarIncrementoProbabilidadPremio(Request $request) {
         try {
            $objRutaRecursos = ParametroGeneral::buscarParametroxNombre(ParametroGeneral::$rutaRecursos);
            if (empty($objRutaRecursos) || empty($objRutaRecursos->parametro_general_valor)) {
                Log::error('No se ha encontrado parámetro RUTA_RECURSOS.');
                Session::flash('messageError', 'No se ha encontrado el parámetro: RUTA_RECURSOS');
                return redirect('welcome');
            }
            $objCiEvePre = CicloEventoPremio::with('estado','cicloEvento','cicloEvento.ciclo','cicloEvento.evento','logCicloEventoPremio','premio')->find($request->ciclo_evento_premio_id);
            if (empty($objCiEvePre) || empty($objCiEvePre->ciclo_evento_premio_id)) {
                    Log::error('No se ha encontrado ciclo evento premio.');
                    Session::flash('messageError', 'No se ha encontrado el ciclo evento premio');
                    return redirect('welcome');
            }
            $objPremio = $objCiEvePre->premio;
            if (empty($objPremio) || empty($objPremio->premio_url_imagen)) {
                    Log::error('No se ha encontrado premio.');
                    Session::flash('messageError', 'No se ha encontrado el premio');
                    return redirect('welcome');
            }
            $obj = new IncrementoProbabilidadPremio();
            \DB::transaction(function () use ($request, $obj, $objRutaRecursos,$objPremio,$objCiEvePre) {
                $obj->inc_pro_pre_nombre = strtoupper($request->inc_pro_pre_nombre);
                $obj->inc_pro_pre_descripcion = $request->inc_pro_pre_descripcion;
                $obj->inc_pro_pre_cantidad = $request->inc_pro_pre_cantidad;
                $obj->inc_pro_pre_cantidad_disponible = $request->inc_pro_pre_cantidad;
                $obj->inc_pro_pre_cantidad_virtual = 0;
                $obj->inc_pro_pre_fecha_incremento = $request->inc_pro_pre_fecha_incremento;
                $obj->inc_pro_pre_hora_inicio_incremento = $request->inc_pro_pre_hora_inicio_incremento;
                $obj->inc_pro_pre_hora_fin_incremento = $request->inc_pro_pre_hora_fin_incremento;
                $obj->inc_pro_pre_porcentaje_probabilidad = $request->inc_pro_pre_porcentaje_probabilidad;
                $obj->ciclo_evento_premio_id = $request->ciclo_evento_premio_id;
                $obj->categoria_id = $request->categoria_id;
                $obj->estado_id = $request->estado_id;
                $obj->premio_id = $objCiEvePre->premio->premio_id;
                $obj->evento_id = $objCiEvePre->cicloEvento->evento->evento_id;
                $obj->inc_pro_pre_url_imagen = $objPremio->premio_url_imagen;
                $obj->save();
                $objCiEvePre->ciclo_evento_premio_stock_disponible = $objCiEvePre->ciclo_evento_premio_stock_disponible - $obj->inc_pro_pre_cantidad;
                $objCiEvePre->save();
                $objC = IncrementoProbabilidadPremio::with('estado', 'logIncrementoProbabilidadPremio', 'categoria', 'cicloEventoPremio','cicloEventoPremio.cicloEvento','cicloEventoPremio.cicloEvento.ciclo','premio','evento')->find($obj->inc_pro_pre_id);
                    $var = 'INCREMENTO_PROBABILIDAD_PREMIO_ID: ' . $objC->inc_pro_pre_id .
                            ' |NOMBRE: ' . $objC->inc_pro_pre_nombre .
                            ' |DESCRIPCIÓN: ' . $objC->inc_pro_pre_descripcion .
                            ' |CANTIDAD: ' . $objC->inc_pro_pre_cantidad .
                            ' |CANTIDAD_DISPONIBLE: ' . $objC->inc_pro_pre_cantidad_disponible .
                            ' |CANTIDAD_VIRTUAL: ' . $objC->inc_pro_pre_cantidad_virtual .
                            ' |URL_IMAGEN: ' . $objC->inc_pro_pre_url_imagen .
                            ' |FECHA_INCENTIVO: ' . $objC->inc_pro_pre_fecha_incremento .
                            ' |HORA_INICIO: ' . $objC->inc_pro_pre_hora_inicio_incremento .
                            ' |HORA_FIN: ' . $objC->inc_pro_pre_hora_fin_incremento .
                            ' |PORCENTAJE: ' . $objC->inc_pro_pre_porcentaje_probabilidad .
                            ' |CATEGORÍA: ' . $objC->categoria_id . '-' . $objC->categoria->categoria_nombre .
                            ' |CICLO: ' . $objC->cicloEventoPremio->cicloEvento->ciclo->ciclo_id . '-' . $objC->cicloEventoPremio->cicloEvento->ciclo->ciclo_nombre .
                            ' |PREMIO: ' . $objC->premio_id . '-' . $objC->premio->premio_nombre .
                            ' |EVENTO: ' . $objC->evento_id . '-' . $objC->evento->evento_nombre .
                            ' |ESTADO: ' . $objC->estado_id . '-' . $objC->estado->estado_nombre ;
                    $log = new LogIncrementoProbabilidadPremio();
                    $log->registro_nuevo = $var;
                    $log->id_tabla = $objC->inc_pro_pre_id;
                    $log->tipo_accion = 'I';
                    $log->save();
                Session::flash('message', 'INCREMENTO PROBABILIDAD PREMIO: ' . strtoupper($obj->inc_pro_pre_nombre) . ' ha sido registrado con éxito.');
            });
            return redirect()->back();
        } catch (\Exception $ex) {
            \DB::rollback();
            Log::error('CONTROLLER IncrementoProbabilidadPremio/guardarIncrementoProbabilidadPremio' . $ex->getMessage());
            Session::flash('messageError', $ex->getMessage());
            return redirect()->back();
        }
    }
    public function buscarIncrementoProbabilidadPremio(Request $request) {
        try {
            if ($request->inc_pro_pre_id != null) {
                $obj = IncrementoProbabilidadPremio::with('estado', 'logIncrementoProbabilidadPremio', 'categoria', 'cicloEventoPremio','cicloEventoPremio.cicloEvento','cicloEventoPremio.cicloEvento.ciclo','premio','evento')->find($request->inc_pro_pre_id);
                if (!empty($obj)) {
                    return $obj;
                }
            }
        } catch (\Exception $ex) {
            Log::error('CONTROLLER IncrementoProbabilidadPremio/buscarIncrementoProbabilidadPremio' . $ex->getMessage());
            Session::flash('messageError', $ex->getMessage());
            return redirect()->back();
        }
    }
    public function actualizarIncrementoProbabilidadPremio(Request $request) {
        try {
            if ($request->inc_pro_pre_id != null) {
                $obj = IncrementoProbabilidadPremio::with('estado', 'logIncrementoProbabilidadPremio', 'categoria', 'cicloEventoPremio','cicloEventoPremio.cicloEvento','cicloEventoPremio.cicloEvento.ciclo','premio','evento')->find($request->inc_pro_pre_id);
                if (!empty($obj)) {
                    $objPremio = $obj->premio;
                    if (empty($objPremio) || empty($objPremio->premio_url_imagen)) {
                            Log::error('No se ha encontrado premio.');
                            Session::flash('messageError', 'No se ha encontrado el premio');
                            return redirect('welcome');
                    }
                    $var = 'INCREMENTO_PROBABILIDAD_PREMIO_ID: ' . $obj->inc_pro_pre_id .
                            ' |NOMBRE: ' . $obj->inc_pro_pre_nombre .
                            ' |DESCRIPCIÓN: ' . $obj->inc_pro_pre_descripcion .
                            ' |CANTIDAD: ' . $obj->inc_pro_pre_cantidad .
                            ' |CANTIDAD_DISPONIBLE: ' . $obj->inc_pro_pre_cantidad_disponible .
                            ' |CANTIDAD_VIRTUAL: ' . $obj->inc_pro_pre_cantidad_virtual .
                            ' |URL_IMAGEN: ' . $obj->inc_pro_pre_url_imagen .
                            ' |FECHA_INCENTIVO: ' . $obj->inc_pro_pre_fecha_incremento .
                            ' |HORA_INICIO: ' . $obj->inc_pro_pre_hora_inicio_incremento .
                            ' |HORA_FIN: ' . $obj->inc_pro_pre_hora_fin_incremento .
                            ' |PORCENTAJE: ' . $obj->inc_pro_pre_porcentaje_probabilidad .
                            ' |CATEGORÍA: ' . $obj->categoria_id . '-' . $obj->categoria->categoria_nombre .
                            ' |CICLO: ' . $obj->cicloEventoPremio->cicloEvento->ciclo->ciclo_id . '-' . $obj->cicloEventoPremio->cicloEvento->ciclo->ciclo_nombre .
                            ' |PREMIO: ' . $obj->premio_id . '-' . $obj->premio->premio_nombre .
                            ' |EVENTO: ' . $obj->evento_id . '-' . $obj->evento->evento_nombre .
                            ' |ESTADO: ' . $obj->estado_id . '-' . $obj->estado->estado_nombre ;
                    \DB::transaction(function () use ($request, $obj, $var,$objPremio) {
                        $objCicloEventoPremio = $obj->cicloEventoPremio;
                        $objCicloEventoPremio->ciclo_evento_premio_stock_disponible = $objCicloEventoPremio->ciclo_evento_premio_stock_disponible + $obj->inc_pro_pre_cantidad;
                        $objCicloEventoPremio->save();
                        $obj->inc_pro_pre_nombre = strtoupper($request->inc_pro_pre_nombre);
                        $obj->inc_pro_pre_descripcion = $request->inc_pro_pre_descripcion;
                        $obj->inc_pro_pre_cantidad = $request->inc_pro_pre_cantidad;
                        $obj->inc_pro_pre_cantidad_disponible = $obj->inc_pro_pre_cantidad - $obj->inc_pro_pre_cantidad_virtual ;
                        $obj->inc_pro_pre_fecha_incremento = $request->inc_pro_pre_fecha_incremento;
                        $obj->inc_pro_pre_hora_inicio_incremento = $request->inc_pro_pre_hora_inicio_incremento;
                        $obj->inc_pro_pre_hora_fin_incremento = $request->inc_pro_pre_hora_fin_incremento;
                        $obj->inc_pro_pre_porcentaje_probabilidad = $request->inc_pro_pre_porcentaje_probabilidad;
                        $obj->premio_id = $obj->premio->premio_id;
                        $obj->categoria_id = $request->categoria_id;
                        $obj->estado_id = $request->estado_id;
                        $obj->evento_id = $obj->evento->evento_id;
                        $obj->ciclo_evento_premio_id = $request->ciclo_evento_premio_id;
                        $obj->inc_pro_pre_url_imagen = $objPremio->premio_url_imagen;
                        $obj->save();
                        $objC = IncrementoProbabilidadPremio::with('estado', 'logIncrementoProbabilidadPremio', 'categoria', 'cicloEventoPremio','cicloEventoPremio.cicloEvento','cicloEventoPremio.cicloEvento.ciclo','premio','evento')->find($obj->inc_pro_pre_id);
                        $objCicloEventoPremioA = $objC->cicloEventoPremio;
                        $objCicloEventoPremioA->ciclo_evento_premio_stock_disponible = $objCicloEventoPremioA->ciclo_evento_premio_stock_disponible - $objC->inc_pro_pre_cantidad;
                        $objCicloEventoPremioA->save();
                        $var2 = 'INCREMENTO_PROBABILIDAD_PREMIO_ID: ' . $objC->inc_pro_pre_id .
                                ' |NOMBRE: ' . $objC->inc_pro_pre_nombre .
                                ' |DESCRIPCIÓN: ' . $objC->inc_pro_pre_descripcion .
                                ' |CANTIDAD: ' . $objC->inc_pro_pre_cantidad .
                                ' |CANTIDAD_DISPONIBLE: ' . $objC->inc_pro_pre_cantidad_disponible .
                                ' |CANTIDAD_VIRTUAL: ' . $objC->inc_pro_pre_cantidad_virtual .
                                ' |URL_IMAGEN: ' . $objC->inc_pro_pre_url_imagen .
                                ' |FECHA_INCENTIVO: ' . $objC->inc_pro_pre_fecha_incremento .
                                ' |HORA_INICIO: ' . $objC->inc_pro_pre_hora_inicio_incremento .
                                ' |HORA_FIN: ' . $objC->inc_pro_pre_hora_fin_incremento .
                                ' |PORCENTAJE: ' . $objC->inc_pro_pre_porcentaje_probabilidad .
                                ' |CATEGORÍA: ' . $objC->categoria_id . '-' . $objC->categoria->categoria_nombre .
                                ' |CICLO: ' . $objC->cicloEventoPremio->cicloEvento->ciclo->ciclo_id . '-' . $objC->cicloEventoPremio->cicloEvento->ciclo->ciclo_nombre .
                                ' |PREMIO: ' . $objC->premio_id . '-' . $objC->premio->premio_nombre .
                                ' |EVENTO: ' . $objC->evento_id . '-' . $objC->evento->evento_nombre .
                                ' |ESTADO: ' . $objC->estado_id . '-' . $objC->estado->estado_nombre ;
                        $log = new LogIncrementoProbabilidadPremio();
                        $log->registro_anterior = $var;
                        $log->registro_nuevo = $var2;
                        $log->id_tabla = $obj->inc_pro_pre_id;
                        $log->tipo_accion = 'A';
                        $log->save();
                         Session::flash('message', 'INCREMENTO PROBABILIDAD PREMIO: ' . strtoupper($obj->inc_pro_pre_nombre) . ' ha sido registrado con éxito.');
                    });
                } else {
                    Session::flash('messageError', "No se ha encontrado el incremento probabilidad premio a actualizar.");
                }
            } else {
                Session::flash('messageError', "No se ha seleccionado el incremento probabilidad premio.");
            }
            return redirect()->back();
        } catch (\Exception $ex) {
            \DB::rollback();
            Log::error('CONTROLLER IncrementoProbabilidadPremio/actualizarIncrementoProbabilidadPremio' . $ex->getMessage());
            Session::flash('messageError', $ex->getMessage());
            return redirect()->back();
        }
    }
}
