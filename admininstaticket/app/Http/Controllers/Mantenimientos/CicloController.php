<?php
namespace Instaticket\Http\Controllers\Mantenimientos;
use Instaticket\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use Validator;
use Auth;
use Instaticket\Entidades\Ciclo;
use Instaticket\Entidades\LogCiclo;
use Illuminate\Support\Facades\Log;
class CicloController extends Controller {
    /**
     * AUTOR: INNOVASYSTEM ECUADOR S.A
     * DESCRIPCION: MÉTODO REALIZA CARGAR PANTALLA: CICLO
     * @return type
     */
    public function index() {
        try {
            Log::info('Ingreso Interfaz CICLO, con usuario: ' . Auth::user()->usuario_username);
            $lstCiclo = Ciclo::buscarTodosCiclos();
            return view('mantenimientos.ciclo', compact('lstCiclo'));
        } catch (\Exception $ex) {
            Log::error('CONTROLLER Ciclo/index' . $ex->getMessage());
            Session::flash('messageError', $ex->getMessage());
            return redirect('welcome');
        }
    }
    public function validarCiclo(Request $request) {
        try {
            $lstError = '';
            $array = array();
            $messages = [
                'ciclo_nombre.required' => 'El campo nombre es requerido.',
                'ciclo_nombre.max' => 'El campo nombre debe tener un tamaño de 45 caracteres.',
                'ciclo_nombre.unique' => 'Nombre ' . $request->ciclo_nombre . ' ya se encuentra registrado.',
                'ciclo_descripcion.required' => 'El campo descripción es requerido.',
                'ciclo_descripcion.max' => 'El campo descripción debe tener un tamaño de 250 caracteres.',
            ];
            if (!empty($request->ciclo_id)) {
                $validator = Validator::make($request->all(), [
                            'ciclo_nombre' => 'max:45|required|unique:ciclo,ciclo_nombre,' . $request->ciclo_id . ',ciclo_id',
                            'ciclo_descripcion' => 'max:250|required|',
                            'ciclo_fecha_inicio' => 'required|',
                            'ciclo_fecha_fin' => 'required|',
                                ], $messages);
                if ($validator->fails()) {
                    $lstError = $validator->errors();
                } else {
                    $cicloM = Ciclo::find($request->ciclo_id);
                    $fechaActual = date("Y-m-d");

                    if ($fechaActual >= $cicloM->ciclo_fecha_inicio || $cicloM->ciclo_fecha_fin <= $fechaActual) {
                        $msj = array("El ciclo ya se inició, y no se permite realizar cambios.");
                        array_push($array, $msj);
                    } else {
                        if ($request->ciclo_fecha_inicio <= $fechaActual) {
                            $msj = array("La fecha inicio no puede ser menor o igual a la Actual");
                            array_push($array, $msj);
                        }
                        if ($request->ciclo_fecha_fin <= $request->ciclo_fecha_inicio) {
                            $msj = array("La fecha fin no puede ser menor o igual a la Inicio");
                            array_push($array, $msj);
                        }
                        $ciclo1 = Ciclo::where('ciclo_fecha_inicio', $request->ciclo_fecha_inicio)->where('ciclo_fecha_fin', $request->ciclo_fecha_fin)->where('ciclo_id', '!=', $request->ciclo_id)->get();
                        if (count($ciclo1) > 0) {
                            $msj = array("La fecha inicio o fin se encuentra entre un ciclo ya registrado.");
                            array_push($array, $msj);
                        }
                    }
                    if (count($array) > 0) {
                        $lstError = $array;
                    }
                }
            } else {
                $validator = Validator::make($request->all(), [
                            'ciclo_nombre' => 'max:45|required|unique:ciclo,ciclo_nombre',
                            'ciclo_descripcion' => 'max:250|required|',
                            'ciclo_fecha_inicio' => 'required|',
                            'ciclo_fecha_fin' => 'required|',
                                ], $messages);
                if ($validator->fails()) {
                    $lstError = $validator->errors();
                } else {
                    $fechaActual = date("Y-m-d");
                    if ($request->ciclo_fecha_inicio < $fechaActual) {
                        $msj = array("La fecha inicio no puede ser menor ");
                        array_push($array, $msj);
                    }
                    if ($request->ciclo_fecha_fin <= $request->ciclo_fecha_inicio) {
                        $msj = array("La fecha fin no puede ser menor o igual a la Inicio");
                        array_push($array, $msj);
                    }
                    $ciclo1 = Ciclo::where('ciclo_fecha_inicio', $request->ciclo_fecha_inicio)->where('ciclo_fecha_fin', $request->ciclo_fecha_fin)->get();
                    if (count($ciclo1) > 0) {
                        $msj = array("La fecha inicio y fin se encuentra en un ciclo ya registrado.");
                        array_push($array, $msj);
                    }
                    if (count($array) > 0) {
                        $lstError = $array;
                    }
                }
            }
            return $lstError;
        } catch (\Exception $ex) {
            Log::error('CONTROLLER Ciclo/index' . $ex->getMessage());
            return array($ex->getMessage());
        }
    }
    public function guardarCiclo(Request $request) {
        try {
            $obj = new Ciclo();
            \DB::transaction(function () use ($request, $obj) {
                $obj->ciclo_nombre = strtoupper($request->ciclo_nombre);
                $obj->ciclo_descripcion = $request->ciclo_descripcion;
                $obj->ciclo_fecha_inicio = $request->ciclo_fecha_inicio;
                $obj->ciclo_fecha_fin = $request->ciclo_fecha_fin;
                $obj->save();
                $objC = Ciclo::find($obj->ciclo_id);
                $var = 'CICLO_ID: ' . $objC->ciclo_id .
                        ' |NOMBRE: ' . strtoupper($objC->ciclo_nombre) .
                        ' |DESCRIPCIÓN: ' . $objC->ciclo_descripcion .
                        ' |FECHA_INICIO: ' . $objC->ciclo_fecha_inicio .
                        ' |FECHA_FIN: ' . $objC->ciclo_fecha_fin;

                $log = new LogCiclo();
                $log->registro_nuevo = $var;
                $log->id_tabla = $obj->ciclo_id;
                $log->tipo_accion = 'I';
                $log->save();
            });
            Session::flash('message', 'CICLO: ' . strtoupper($obj->ciclo_nombre) . ' ha sido registrado con éxito.');
            return redirect()->back();
        } catch (\Exception $ex) {
            \DB::rollback();
            Log::error('CONTROLLER Ciclo/index' . $ex->getMessage());
            Session::flash('messageError', $ex->getMessage());
            return redirect()->back();
        }
    }
    public function buscarCiclo(Request $request) {
        try {
            if ($request->ciclo_id != null) {
                $obj = Ciclo::find($request->ciclo_id);
                if (!empty($obj)) {
                    return $obj;
                }
            }
        } catch (\Exception $ex) {
            Log::error('CONTROLLER Ciclo/index' . $ex->getMessage());
            Session::flash('messageError', $ex->getMessage());
            return redirect()->back();
        }
    }
    public function actualizarCiclo(Request $request) {
        try {
            if ($request->ciclo_id != null) {
                $obj = Ciclo::find($request->ciclo_id);
                if (!empty($obj)) {
                    $var = 'CICLO_ID: ' . $obj->ciclo_id .
                            ' |NOMBRE: ' . strtoupper($obj->ciclo_nombre) .
                            ' |DESCRIPCIÓN: ' . $obj->ciclo_descripcion .
                            ' |FECHA_INICIO: ' . $obj->ciclo_fecha_inicio .
                            ' |FECHA_FIN: ' . $obj->ciclo_fecha_fin;

                    \DB::transaction(function () use ($request, $obj, $var) {
                        $obj->ciclo_nombre = strtoupper($request->ciclo_nombre);
                        $obj->ciclo_descripcion = $request->ciclo_descripcion;
                        $obj->ciclo_fecha_inicio = $request->ciclo_fecha_inicio;
                        $obj->ciclo_fecha_fin = $request->ciclo_fecha_fin;
                       $obj->save();
                        $objC = Ciclo::find($obj->ciclo_id);
                        $var2 = 'CICLO_ID: ' . $objC->ciclo_id .
                                ' |NOMBRE: ' . strtoupper($objC->ciclo_nombre) .
                                ' |DESCRIPCIÓN: ' . $objC->ciclo_descripcion .
                                ' |FECHA_INICIO: ' . $objC->ciclo_fecha_inicio .
                                ' |FECHA_FIN: ' . $objC->ciclo_fecha_fin;

                        $log = new LogCiclo();
                        $log->registro_anterior = $var;
                        $log->registro_nuevo = $var2;
                        $log->id_tabla = $obj->ciclo_id;
                        $log->tipo_accion = 'A';
                        $log->save();
                    });
                    Session::flash('message', 'CICLO: ' . strtoupper($request->ciclo_nombre) . ' ha sido actualizada con éxito.');
                } else {
                    Session::flash('messageError', "No se ha encontrado el ciclo a actualizar.");
                }
            } else {
                Session::flash('messageError', "No se ha seleccionado el ciclo.");
            }
            return redirect()->back();
        } catch (\Exception $ex) {
            \DB::rollback();
            Log::error('CONTROLLER Ciclo/actualizarCiclo' . $ex->getMessage());
            Session::flash('messageError', $ex->getMessage());
            return redirect()->back();
        }
    }
}
