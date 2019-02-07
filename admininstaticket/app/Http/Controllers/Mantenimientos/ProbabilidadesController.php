<?php
namespace Instaticket\Http\Controllers\Mantenimientos;
use Instaticket\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use Validator;
use Auth;
use Instaticket\Entidades\Probabilidades;
use Instaticket\Entidades\LogProbabilidades;
use Instaticket\Entidades\Estados;
use Illuminate\Support\Facades\Log;
class ProbabilidadesController extends Controller {
    /**
     * AUTOR: INNOVASYSTEM ECUADOR S.A
     * DESCRIPCION: MÉTODO REALIZA CARGAR PANTALLA: PROBABILIDAD
     * @return type
     */
    public function index() {
        try {
            Log::info('Ingreso Interfaz PROBABILIDAD, con usuario: ' . Auth::user()->usuario_username);
            $lstProbabilidades = Probabilidades::buscarTodasProbabilidades();
            return view('mantenimientos.probabilidad', compact('lstProbabilidades'));
        } catch (\Exception $ex) {
            Log::error('CONTROLLER Probabilidad/index' . $ex->getMessage());
            Session::flash('messageError', $ex->getMessage());
            return redirect('welcome');
        }
    }
    public function validarProbabilidad(Request $request) {
        try {
            $lstError = '';
            $array = array();
            $messages = [
                'probabilidad_porcentaje.required' => 'El campo probabilidad es requerido.',
                'probabilidad_porcentaje.max' => 'El campo probabilidad debe tener un tamaño de 11 caracteres.',
                'probabilidad_porcentaje.unique' => 'Probabilidad ' . $request->probabilidad_porcentaje . ' ya se encuentra registrado.',
                'estado_id.required' => 'El campo estado es requerido.',
                'estado_id.max' => 'El campo estado debe tener un tamaño de 11 caracteres.',
            ];
            if (!empty($request->probabilidad_id)) {
                $validator = Validator::make($request->all(), [
                            'probabilidad_porcentaje' => 'required|unique:probabilidades,probabilidad_porcentaje,' . $request->probabilidad_id . ',probabilidad_id',
                            'estado_id' => 'required|',
                                ], $messages);
            } else {
                $validator = Validator::make($request->all(), [
                            'probabilidad_porcentaje' => 'required|unique:probabilidades,probabilidad_porcentaje',
                            'estado_id' => 'required|',
                                ], $messages);
            }
            if ($validator->fails()) {
                $lstError = $validator->errors();
            } else {
                if ($request->estado_id == Estados::$estadoInactivo) {
                    $pActivo = Probabilidades::where('estado_id', Estados::$estadoActivo)->count();
                    if ($pActivo <= 1) {
                        $msj = array("No se permite inactivar, debe existir una probabilidad activa.");
                        array_push($array, $msj);
                    }
                }
                if (($request->probabilidad_porcentaje < 1) || ($request->probabilidad_porcentaje > 100)) {
                    $msj = array("El número de probabilidad debe ser entre 1% y 100%.");
                    array_push($array, $msj);
                }
                if (!empty($request->probabilidad_hora_inicio) && empty($request->probabilidad_hora_fin)) {
                    $msj = array("La hora fin es requerida.");
                    array_push($array, $msj);
                }
                if (empty($request->probabilidad_hora_inicio) && !empty($request->probabilidad_hora_fin)) {
                    $msj = array("La hora inicio es requerida.");
                    array_push($array, $msj);
                }
                if (!empty($request->probabilidad_hora_inicio) && !empty($request->probabilidad_hora_fin)) {
                    $hora1 = strtotime($request->probabilidad_hora_inicio);
                    $hora2 = strtotime($request->probabilidad_hora_fin);
                    if ($hora2 <= $hora1) {
                        $msj = array("La hora inicio debe ser menor a la hora fin.");
                        array_push($array, $msj);
                    }
                }
                if (count($array) > 0) {
                    $lstError = $array;
                }
            }
            return $lstError;
        } catch (\Exception $ex) {
            Log::error('CONTROLLER Probabilidad/validarProbabilidad' . $ex->getMessage());
            return array($ex->getMessage());
        }
    }
    public function guardarProbabilidad(Request $request) {
        try {
            $obj = new Probabilidades();
            \DB::transaction(function () use ($request, $obj) {
                $obj->probabilidad_porcentaje = $request->probabilidad_porcentaje;
                if (!empty($request->probabilidad_hora_inicio))
                    $obj->probabilidad_hora_inicio = $request->probabilidad_hora_inicio;
                if (!empty($request->probabilidad_hora_fin))
                    $obj->probabilidad_hora_fin = $request->probabilidad_hora_fin;
                $obj->estado_id = $request->estado_id;
                $obj->save();
                $objC = Probabilidades::with('estado')->find($obj->probabilidad_id);
                $var = 'PROBABILIDAD_ID: ' . $obj->probabilidad_id .
                        ' |PROBABILIDAD: ' . $obj->probabilidad_porcentaje .
                        ' |HORA_INICIO: ' . $obj->probabilidad_hora_inicio .
                        ' |HORA_FIN: ' . $obj->probabilidad_hora_fin .
                        ' |ESTADO: ' . $obj->estado_id . '-' . $objC->estado->estado_nombre;
                $log = new LogProbabilidades();
                $log->registro_nuevo = $var;
                $log->id_tabla = $obj->probabilidad_id;
                $log->tipo_accion = 'I';
                $log->save();
            });
            Session::flash('message', 'PROBABILIDAD: ' . $obj->probabilidad_porcentaje . ' ha sido registrado con éxito.');
            return redirect()->back();
        } catch (\Exception $ex) {
            \DB::rollback();
            Log::error('CONTROLLER Probabilidad/guardarProbabilidad' . $ex->getMessage());
            Session::flash('messageError', $ex->getMessage());
            return redirect()->back();
        }
    }
    public function buscarProbabilidad(Request $request) {
        try {
            if ($request->probabilidad_id != null) {
                $obj = Probabilidades::find($request->probabilidad_id);
                if (!empty($obj)) {
                    return $obj;
                }
            }
        } catch (\Exception $ex) {
            Log::error('CONTROLLER Probabilidad/buscarProbabilidad' . $ex->getMessage());
            Session::flash('messageError', $ex->getMessage());
            return redirect()->back();
        }
    }
    public function actualizarProbabilidad(Request $request) {
        try {
            if ($request->probabilidad_id != null) {
                $obj = Probabilidades::with('estado')->find($request->probabilidad_id);
                if (!empty($obj)) {
                    $var = 'PROBABILIDAD_ID: ' . $obj->probabilidad_id .
                            ' |PROBABILIDAD: ' . $obj->probabilidad_porcentaje .
                            ' |HORA_INICIO: ' . $obj->probabilidad_hora_inicio .
                            ' |HORA_FIN: ' . $obj->probabilidad_hora_fin .
                            ' |ESTADO: ' . $obj->estado_id . '-' . $obj->estado->estado_nombre;
                    \DB::transaction(function () use ($request, $obj, $var) {
                        $obj->probabilidad_porcentaje = $request->probabilidad_porcentaje;
                        if (!empty($request->probabilidad_hora_inicio))
                            $obj->probabilidad_hora_inicio = $request->probabilidad_hora_inicio;
                        if (!empty($request->probabilidad_hora_fin))
                            $obj->probabilidad_hora_fin = $request->probabilidad_hora_fin;
                        $obj->estado_id = $request->estado_id;
                        $obj->save();
                        $objC = Probabilidades::with('estado')->find($obj->probabilidad_id);
                        $var2 = 'PROBABILIDAD_ID: ' . $obj->probabilidad_id .
                                ' |PROBABILIDAD: ' . $obj->probabilidad_porcentaje .
                                ' |HORA_INICIO: ' . $obj->probabilidad_hora_inicio .
                                ' |HORA_FIN: ' . $obj->probabilidad_hora_fin .
                                ' |ESTADO: ' . $obj->estado_id . '-' . $objC->estado->estado_nombre;
                        $log = new LogProbabilidades();
                        $log->registro_anterior = $var;
                        $log->registro_nuevo = $var2;
                        $log->id_tabla = $obj->probabilidad_id;
                        $log->tipo_accion = 'A';
                        $log->save();
                    });
                    Session::flash('message', 'PROBABILIDAD: ' . $request->probabilidad_porcentaje . ' ha sido actualizada con éxito.');
                } else {
                    Session::flash('messageError', "No se ha encontrado la probabilidad a actualizar.");
                }
            } else {
                Session::flash('messageError', "No se ha seleccionado probabilidad.");
            }
            return redirect()->back();
        } catch (\Exception $ex) {
            \DB::rollback();
            Log::error('CONTROLLER Probabilidad/actualizarProbabilidad' . $ex->getMessage());
            Session::flash('messageError', $ex->getMessage());
            return redirect()->back();
        }
    }
}
