<?php

namespace Instaticket\Http\Controllers\Configuracion;

use Instaticket\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use Validator;
use Auth;
use Instaticket\Entidades\CicloEvento;
use Instaticket\Entidades\LogCicloEvento;
use Instaticket\Entidades\Ciclo;
use Instaticket\Entidades\Eventos;
use Instaticket\Entidades\Estados;
use Illuminate\Support\Facades\Log;

class CicloEventoController extends Controller {

    /**
     * AUTOR: INNOVASYSTEM ECUADOR S.A
     * DESCRIPCION: MÉTODO REALIZA CARGAR PANTALLA: CATEGORIAS
     * @return type
     */
    public function index() {
        try {
            Log::info('Ingreso Interfaz CICLO EVENTO, con usuario: ' . Auth::user()->usuario_username);
            $lstCiclosEventos = CicloEvento::buscarTodosCiclosEventos();
            $lstCiclo = Ciclo::buscarTodosCiclos();
            $lstEventos = Eventos::buscarTodosEventos();
            return view('configuracion.cicloevento', compact('lstCiclosEventos', 'lstCiclo', 'lstEventos'));
        } catch (\Exception $ex) {
            Log::error('CONTROLLER Ciclo Eventos/index' . $ex->getMessage());
            Session::flash('messageError', $ex->getMessage());
            return redirect('welcome');
        }
    }

    public function validarCicloEvento(Request $request) {
        try {
            $lstError = '';
            $array = array();
            $messages = [
                'ciclo_id.required' => 'El campo ciclo es requerido.',
                'ciclo_id.max' => 'El campo ciclo debe tener un tamaño de 11 caracteres.',
                'evento_id.required' => 'El campo evento es requerido.',
                'evento_id.unique' => 'El evento ya tiene un cliclo asignado.',
                'evento_id.max' => 'El campo evento debe tener un tamaño de 11 caracteres.',
                'total_premios.required' => 'El campo total premios es requerido.',
                'total_premios.max' => 'El campo total premios debe tener un tamaño de 11 caracteres.',
                'estado_id.required' => 'El campo estado es requerido.',
                'estado_id.max' => 'El campo estado debe tener un tamaño de 11 caracteres.',
            ];
            if (!empty($request->ciclo_evento_id)) {
                $validator = Validator::make($request->all(), [
                            'evento_id' => 'max:11|required|unique:ciclo_evento,evento_id,' . $request->ciclo_evento_id . ',ciclo_evento_id',
                            'ciclo_id' => 'max:11|required|',
                            'estado_id' => 'required|',
                            'total_premios' => 'max:11|required|',
                                ], $messages);
            } else {
                $validator = Validator::make($request->all(), [
                            'evento_id' => 'max:11|required|unique:ciclo_evento,evento_id',
                            'ciclo_id' => 'max:11|required|',
                            'estado_id' => 'required|',
                            'total_premios' => 'max:11|required|',
                                ], $messages);
            }
            if ($validator->fails()) {
                $lstError = $validator->errors();
            } else {
                if (!empty($request->ciclo_evento_id)) {
                    $fechaActual = date("Y-m-d");
                    $cCicloEvento = CicloEvento::with('ciclo')->where('ciclo_evento_id', $request->ciclo_evento_id)->first();
                    if (!empty($cCicloEvento) && !empty($cCicloEvento->ciclo)) {
                        if ($fechaActual >= $cCicloEvento->ciclo->ciclo_fecha_inicio && $fechaActual <= $cCicloEvento->ciclo->ciclo_fecha_fin) {
                            if ($cCicloEvento->evento_id != $request->evento_id) {
                                $msj = array("El Ciclo Evento ya se inició, y no se permite cambiar de evento.");
                                array_push($array, $msj);
                            }
                            if ($cCicloEvento->ciclo_id != $request->ciclo_id) {
                                $msj = array("El Ciclo Evento ya se inició, y no se permite cambiar de ciclo.");
                                array_push($array, $msj);
                            }
                            if ($request->estado_id == Estados::$estadoInactivo) {
                                $msj = array("El Ciclo Evento ya se inició, y no se permite inactivar.");
                                array_push($array, $msj);
                            }
                            if ($cCicloEvento->total_premios > $request->total_premios) {
                                $msj = array("El Ciclo Evento ya se inició, y no se permite que el total de premios sea menor a " . $cCicloEvento->total_premios . ".");
                                array_push($array, $msj);
                            }
                        }
                    }
                }
                if (count($array) > 0) {
                    $lstError = $array;
                }
            }
            return $lstError;
        } catch (\Exception $ex) {
            Log::error('CONTROLLER Ciclo Evento/validarCicloEvento' . $ex->getMessage());
            return array($ex->getMessage());
        }
    }

    public function guardarCicloEvento(Request $request) {
        try {
            $obj = new CicloEvento();
            $objC = new CicloEvento();
            \DB::transaction(function () use ($request, $obj, $objC) {
                $obj->ciclo_id = $request->ciclo_id;
                $obj->evento_id = $request->evento_id;
                $obj->total_premios = $request->total_premios;
                $obj->estado_id = $request->estado_id;
				$obj->turnos = $request->total_turnos;

                if ($request->j_puerta) {
                    $obj->juego_puerta = "S";
                } else {
                    $obj->juego_puerta = "N";
                }
                if ($request->j_carta) {
                    $obj->juego_carta = "S";
                } else {
                    $obj->juego_carta = "N";
                }


                $obj->save();
                $objC = CicloEvento::with('estado', 'ciclo', 'evento', 'logCicloEvento')->find($obj->ciclo_evento_id);
                $var = 'CICLO_EVENTO_ID: ' . $objC->ciclo_evento_id .
                        ' |CICLO: ' . $objC->ciclo_id . '-' . $objC->ciclo->ciclo_nombre .
                        ' |EVENTO: ' . $objC->evento_id . '-' . $objC->evento->evento_nombre .
                        ' |TOT. PREMIOS: ' . $objC->total_premios .
                        ' |ESTADO: ' . $objC->estado_id . '-' . $objC->estado->estado_nombre;
                $log = new LogCicloEvento();
                $log->registro_nuevo = $var;
                $log->id_tabla = $obj->ciclo_evento_id;
                $log->tipo_accion = 'I';
                $log->save();
                Session::flash('message', 'CICLO EVENTO: CICLO: ' . $objC->ciclo->ciclo_nombre . ' - EVENTO: ' . $objC->evento->evento_nombre . ' ha sido registrado con éxito.');
            });
            return redirect()->back();
        } catch (\Exception $ex) {
            \DB::rollback();
            Log::error('CONTROLLER Ciclo Evento /guardarCicloEvento' . $ex->getMessage());
            Session::flash('messageError', $ex->getMessage());
            return redirect()->back();
        }
    }

    public function buscarCicloEvento(Request $request) {
        try {
            if ($request->ciclo_evento_id != null) {
                $obj = CicloEvento::with('estado', 'ciclo', 'evento', 'logCicloEvento')->find($request->ciclo_evento_id);
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

    public function actualizarCicloEvento(Request $request) {
        try {
            if ($request->ciclo_evento_id != null) {
                $obj = CicloEvento::with('estado', 'ciclo', 'evento', 'logCicloEvento')->find($request->ciclo_evento_id);
                if (!empty($obj)) {
                    $var = 'CICLO_EVENTO_ID: ' . $obj->ciclo_evento_id .
                            ' |CICLO: ' . $obj->ciclo_id . '-' . $obj->ciclo->ciclo_nombre .
                            ' |EVENTO: ' . $obj->evento_id . '-' . $obj->evento->evento_nombre .
                            ' |TOT. PREMIOS: ' . $obj->total_premios .
                            ' |ESTADO: ' . $obj->estado_id . '-' . $obj->estado->estado_nombre;
                    \DB::transaction(function () use ($request, $obj, $var) {
                        $obj->ciclo_id = $request->ciclo_id;
                        $obj->evento_id = $request->evento_id;
                        $obj->estado_id = $request->estado_id;
                        $obj->total_premios = $request->total_premios;
						$obj->turnos = $request->total_turnos;


                        if ($request->j_puerta) {
                            $obj->juego_puerta = "S";
                        } else {
                            $obj->juego_puerta = "N";
                        }
                        if ($request->j_carta) {
                            $obj->juego_carta = "S";
                        } else {
                            $obj->juego_carta = "N";
                        }


                        $obj->save();
                        $objC = CicloEvento::with('estado', 'ciclo', 'evento', 'logCicloEvento')->find($obj->ciclo_evento_id);
                        $var2 = 'CICLO_EVENTO_ID: ' . $objC->ciclo_evento_id .
                                ' |CICLO: ' . $objC->ciclo_id . '-' . $objC->ciclo->ciclo_nombre .
                                ' |EVENTO: ' . $objC->evento_id . '-' . $objC->evento->evento_nombre .
                                ' |TOT. PREMIOS: ' . $objC->total_premios .
                                ' |ESTADO: ' . $objC->estado_id . '-' . $objC->estado->estado_nombre;

                        $log = new LogCicloEvento();
                        $log->registro_anterior = $var;
                        $log->registro_nuevo = $var2;
                        $log->id_tabla = $obj->ciclo_evento_id;
                        $log->tipo_accion = 'A';
                        $log->save();
                    });
                    Session::flash('message', 'CICLO EVENTO ha sido actualizado con éxito.');
                } else {
                    Session::flash('messageError', "No se ha encontrado el ciclo evento a actualizar.");
                }
            } else {
                Session::flash('messageError', "No se ha seleccionado ciclo evento.");
            }
            return redirect()->back();
        } catch (\Exception $ex) {
            \DB::rollback();
            Log::error('CONTROLLER Ciclo Evento/actualizarCicloEvento' . $ex->getMessage());
            Session::flash('messageError', $ex->getMessage());
            return redirect()->back();
        }
    }

}
