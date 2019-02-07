<?php
namespace Instaticket\Http\Controllers\Configuracion;
use Instaticket\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use Validator;
use Auth;
use Instaticket\Entidades\CicloEventoPremio;
use Instaticket\Entidades\CicloEvento;
use Instaticket\Entidades\LogCicloEventoPremio;
use Instaticket\Entidades\Premios;
use Instaticket\Entidades\Estados;
use Illuminate\Support\Facades\Log;
class CicloEventoPremioController extends Controller {
    /**
     * AUTOR: INNOVASYSTEM ECUADOR S.A
     * DESCRIPCION: MÉTODO REALIZA CARGAR PANTALLA: CATEGORIAS
     * @return type
     */
    public function index() {
        try {
            Log::info('Ingreso Interfaz CICLO EVENTO PREMIO, con usuario: ' . Auth::user()->usuario_username);
            $lstCiclosEventoPremios = CicloEventoPremio::buscarTodosCiclosEventosPremios();
            $lstCiclosEvento = CicloEvento::buscarTodosCiclosEventosActivos();
            $lstPremios = Premios::buscarTodosPremiosActivos();
            return view('configuracion.cicloeventopremio', compact('lstCiclosEventoPremios','lstCiclosEvento','lstPremios'));
        } catch (\Exception $ex) {
            Log::error('CONTROLLER Ciclo Evento Premios/index' . $ex->getMessage());
            Session::flash('messageError', $ex->getMessage());
            return redirect('welcome');
        }
    }
    public function validarCicloEventoPremio(Request $request) {
        try {
            $lstError = '';
            $array = array();
            $messages = [
                'ciclo_evento_premio_stock_total.required' => 'El campo stock total es requerido.',
                'ciclo_evento_premio_stock_total.max' => 'El campo stock total debe tener un tamaño de 11 caracteres.',
                'premio_id.required' => 'El campo premio es requerido.',
                'premio_id.max' => 'El campo premio debe tener un tamaño de 11 caracteres.',
                'ciclo_evento_id.required' => 'El campo ciclo es requerido.',
                'ciclo_evento_id.max' => 'El campo ciclo debe tener un tamaño de 11 caracteres.',
                'estado_id.required' => 'El campo estado es requerido.',
                'estado_id.max' => 'El campo estado debe tener un tamaño de 11 caracteres.',
            ];
            $validator = Validator::make($request->all(), [
                        'ciclo_evento_premio_stock_total' => 'required',
                        'premio_id' => 'required',
                        'ciclo_evento_id' => 'required',
                        'estado_id' => 'required',
                            ], $messages);

            if ($validator->fails()) {
                $lstError = $validator->errors();
            } else {
                if ($request->ciclo_evento_premio_stock_total <= 0) {
                    $msj = array("El stock total debe ser mayor a 0.");
                    array_push($array, $msj);
                }
                if (!empty($request->ciclo_evento_premio_id)) {
                    $cPremios = CicloEventoPremio::with('cicloEvento')->where('ciclo_evento_premio_id', $request->ciclo_evento_premio_id)->first();
                    if (!empty($cPremios)) {
                        if (!empty($cPremios->cicloEvento)) {
                            $fechaActual = date("Y-m-d");
                            if ($fechaActual >= $cPremios->cicloEvento->ciclo->ciclo_fecha_inicio && $fechaActual <= $cPremios->cicloEvento->ciclo->ciclo_fecha_fin) {
                                if($request->estado_id == Estados::$estadoInactivo){
                                    $msj = array("El Ciclo Evento ya se inició, y no se permite inactivar.");
                                    array_push($array, $msj);
                                }
                                if($cPremios->premio_id != $request->premio_id){
                                    $msj = array("El Ciclo Evento ya se inició, y no se actualizar premio.");
                                    array_push($array, $msj);
                                }
                                if($cPremios->ciclo_evento_id != $request->ciclo_evento_id){
                                    $msj = array("El Ciclo Evento ya se inició, y no se premite actualizar el ciclo evento.");
                                    array_push($array, $msj);
                                }
                                if($cPremios->ciclo_evento_premio_stock_total > $request->ciclo_evento_premio_stock_total){
                                    $msj = array("El stock del premio no puede ser menos a ".$cPremios->ciclo_evento_premio_stock_total.".");
                                    array_push($array, $msj);
                                }
                            } 
                                $cicloPremios = CicloEventoPremio::where('ciclo_evento_id', $request->ciclo_evento_id)->
                                        where('premio_id', $request->premio_id)->
                                        where('ciclo_evento_premio_id', '!=', $request->ciclo_evento_premio_id)->
                                        get();
                                if (count($cicloPremios) > 0) {
                                    $msj = array("El Ciclo- Evento y Premio ya se encuentran registrados.");
                                    array_push($array, $msj);
                                } else {
                                    $ciclo = CicloEvento::find($request->ciclo_evento_id);
                                    if (!empty($ciclo)) {
                                        $totalCicloPremio = \DB::table('ciclo_evento_premio')->
                                                        select(\DB::raw('coalesce(sum(ciclo_evento_premio_stock_total), 0) as total_premios'))->
                                                        where('ciclo_evento_id', $ciclo->ciclo_evento_id)->
                                                        where('ciclo_evento_premio_id', '!=', $request->ciclo_evento_premio_id)->first();
                                        $totalPremio = $totalCicloPremio->total_premios + $request->ciclo_evento_premio_stock_total;
                                        if ($totalPremio > $ciclo->total_premios) {
                                            $msj = array("El total del stock (" . $totalCicloPremio->total_premios . "), más el stock ingresado (" . $request->ciclo_evento_premio_stock_total . "), es mayor al total de premios permitidos por el ciclo:" . $ciclo->total_premios);
                                            array_push($array, $msj);
                                        }
                                    } else {
                                        $msj = array("No se ha encontrado el Ciclo - Evento.");
                                        array_push($array, $msj);
                                    }
                                }
                            
                        } else {
                            $msj = array("No se ha encontrado el Ciclo-Evento relacionado al Ciclo-Evento Premio.");
                            array_push($array, $msj);
                        }
                    } else {
                        $msj = array("No se ha encontrado el Ciclo-Evento Premio.");
                        array_push($array, $msj);
                    }
                } else {
                    $cicloM = CicloEvento::find($request->ciclo_evento_id);
                    $fechaActual = date("Y-m-d");
                    if (!empty($cicloM)) {
                            $cicloPremios = CicloEventoPremio::where('ciclo_evento_id', $request->ciclo_evento_id)->
                                            where('premio_id', $request->premio_id)->get();
                            if (count($cicloPremios) > 0) {
                                $msj = array("El Ciclo-Evento y Premio ya se encuentran registrados.");
                                array_push($array, $msj);
                            } else {
                                $totalCicloPremio = \DB::table('ciclo_evento_premio')->
                                                select(\DB::raw('coalesce(sum(ciclo_evento_premio_stock_total), 0) as total_premios'))->
                                                where('ciclo_evento_id', $cicloM->ciclo_evento_id)->first();
                                $totalPremio = $totalCicloPremio->total_premios + $request->ciclo_evento_premio_stock_total;
                                if ($totalPremio > $cicloM->total_premios) {
                                    $msj = array("El total del stock (" . $totalCicloPremio->total_premios . "), más el stock ingresado (" . $request->ciclo_evento_premio_stock_total . "), es mayor al total de premios permitidos por el ciclo: " . $cicloM->total_premios);
                                    array_push($array, $msj);
                                }
                            }
                    } else {
                        $msj = array("No se ha encontrado el Ciclo-Evento.");
                        array_push($array, $msj);
                    }
                }
                if (count($array) > 0) {
                    $lstError = $array;
                }
            }
            return $lstError;
        } catch (\Exception $ex) {
            Log::error('CONTROLLER Ciclo Evento Premios/validarCicloEventoPremio' . $ex->getMessage());
            return array($ex->getMessage());
        }
    }
    public function guardarCicloEventoPremio(Request $request) {
        try {
            $obj = new CicloEventoPremio();
            $objC = new CicloEventoPremio();
            \DB::transaction(function () use ($request, $obj, $objC) {
                $obj->ciclo_evento_id = $request->ciclo_evento_id;
                $obj->premio_id = $request->premio_id;
                $obj->ciclo_evento_premio_stock_total = $request->ciclo_evento_premio_stock_total;
                $obj->ciclo_evento_premio_stock_disponible = $request->ciclo_evento_premio_stock_total;
                $obj->ciclo_evento_premio_stock_virtual = 0;
                $obj->estado_id = $request->estado_id;
                $obj->save();
                $objC = CicloEventoPremio::with('estado','cicloEvento','premio','logCicloEventoPremio')->find($obj->ciclo_evento_premio_id);
                $var = 'CICLO_EVENTO_PREMIO_ID: ' . $objC->ciclo_evento_premio_id .
                        ' |CICLO - EVENTO: ' . $objC->ciclo_evento_id . '-' . $objC->cicloEvento->ciclo->ciclo_nombre . '-' . $objC->cicloEvento->evento->evento_nombre .
                        ' |PREMIO: ' . $objC->premio_id . '-' . $objC->premio->premio_nombre .
                        ' |STOCK TOTAL: ' . $objC->ciclo_evento_premio_stock_total .
                        ' |STOCK DISPONIBLE: ' . $objC->ciclo_evento_premio_stock_disponible .
                        ' |ESTADO: ' . $objC->estado_id . '-' . $objC->estado->estado_nombre;

                $log = new LogCicloEventoPremio();
                $log->registro_nuevo = $var;
                $log->id_tabla = $obj->ciclo_evento_premio_id;
                $log->tipo_accion = 'I';
                $log->save();
                Session::flash('message', 'CICLO EVENTO PREMIO: CICLO: ' .  $objC->cicloEvento->ciclo->ciclo_nombre  . ' - EVENTO: ' .  $objC->cicloEvento->evento->evento_nombre . ' - PREMIO: ' .  $objC->premio->premio_nombre . ' ha sido registrado con éxito.');
            });
            return redirect()->back();
        } catch (\Exception $ex) {
            \DB::rollback();
            Log::error('CONTROLLER Ciclo Evento Premio /guardarCicloEventoPremio' . $ex->getMessage());
            Session::flash('messageError', $ex->getMessage());
            return redirect()->back();
        }
    }
    public function buscarCicloEventoPremio(Request $request) {
        try {
            if ($request->ciclo_evento_premio_id != null) {
                $obj = CicloEventoPremio::with('estado','cicloEvento','premio','logCicloEventoPremio')->find($request->ciclo_evento_premio_id);
                if (!empty($obj)) {
                    return $obj;
                }
            }
        } catch (\Exception $ex) {
            Log::error('CONTROLLER Ciclo Evento/buscarCicloEvento' . $ex->getMessage());
            Session::flash('messageError', $ex->getMessage());
            return redirect()->back();
        }
    }
    public function actualizarCicloEventoPremio(Request $request) {
        try {
            if ($request->ciclo_evento_premio_id != null) {
                $obj = CicloEventoPremio::with('estado','cicloEvento','premio','logCicloEventoPremio','cicloEvento.ciclo','cicloEvento.evento')->find($request->ciclo_evento_premio_id);
                if (!empty($obj)) {
                    $var = 'CICLO_EVENTO_PREMIO_ID: ' . $obj->ciclo_evento_premio_id .
                        ' |CICLO - EVENTO: ' . $obj->ciclo_evento_id . '-' . $obj->cicloEvento->ciclo->ciclo_nombre . '-' . $obj->cicloEvento->evento->evento_nombre .
                        ' |PREMIO: ' . $obj->premio_id . '-' . $obj->premio->premio_nombre .
                        ' |STOCK TOTAL: ' . $obj->ciclo_evento_premio_stock_total .
                        ' |STOCK DISPONIBLE: ' . $obj->ciclo_evento_premio_stock_disponible .
                        ' |ESTADO: ' . $obj->estado_id . '-' . $obj->estado->estado_nombre;
                    \DB::transaction(function () use ($request, $obj, $var) {
                        $obj->ciclo_evento_id = $request->ciclo_evento_id;
                        $obj->premio_id = $request->premio_id;
                        $obj->estado_id = $request->estado_id;
                        $fechaActual = date("Y-m-d");
                        if ($fechaActual >= $obj->cicloEvento->ciclo->ciclo_fecha_inicio && $fechaActual <= $obj->cicloEvento->ciclo->ciclo_fecha_fin) {
                                $diferencia = $request->ciclo_evento_premio_stock_total - $obj->ciclo_evento_premio_stock_total;
                                $obj->ciclo_evento_premio_stock_disponible = $obj->ciclo_evento_premio_stock_disponible + $diferencia;
                        }
                        else{
                            $obj->ciclo_evento_premio_stock_disponible = $request->ciclo_evento_premio_stock_total;
                        }        
                        $obj->ciclo_evento_premio_stock_total = $request->ciclo_evento_premio_stock_total;
                        $obj->save();
                        $objC = CicloEventoPremio::with('estado','cicloEvento','premio','logCicloEventoPremio','cicloEvento.ciclo','cicloEvento.evento')->find($obj->ciclo_evento_premio_id);
                        $var2 = 'CICLO_EVENTO_PREMIO_ID: ' . $objC->ciclo_evento_premio_id .
                                ' |CICLO - EVENTO: ' . $objC->ciclo_evento_id . '-' . $objC->cicloEvento->ciclo->ciclo_nombre . '-' . $objC->cicloEvento->evento->evento_nombre .
                                ' |PREMIO: ' . $objC->premio_id . '-' . $objC->premio->premio_nombre .
                                ' |STOCK TOTAL: ' . $objC->ciclo_evento_premio_stock_total .
                                ' |STOCK DISPONIBLE: ' . $objC->ciclo_evento_premio_stock_disponible .
                                ' |ESTADO: ' . $objC->estado_id . '-' . $objC->estado->estado_nombre;
                        $log = new LogCicloEventoPremio();
                        $log->registro_anterior = $var;
                        $log->registro_nuevo = $var2;
                        $log->id_tabla = $obj->ciclo_evento_premio_id;
                        $log->tipo_accion = 'A';
                        $log->save();
                    });
                    Session::flash('message', 'CICLO EVENTO PREMIO ha sido actualizado con éxito.');
                } else {
                    Session::flash('messageError', "No se ha encontrado el ciclo evento premio a actualizar.");
                }
            } else {
                Session::flash('messageError', "No se ha seleccionado ciclo evento.");
            }
            return redirect()->back();
        } catch (\Exception $ex) {
            \DB::rollback();
            Log::error('CONTROLLER Ciclo Evento Premio/actualizarCicloEventoPremio' . $ex->getMessage());
            Session::flash('messageError', $ex->getMessage());
            return redirect()->back();
        }
    }
}
