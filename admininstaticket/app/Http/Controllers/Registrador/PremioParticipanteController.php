<?php

namespace Instaticket\Http\Controllers\Registrador;

use Instaticket\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use Validator;
use Auth;
use Illuminate\Support\Facades\Log;
use Instaticket\Entidades\PremioParticipante;
use Instaticket\Entidades\ParametroGeneral;
use Instaticket\Entidades\ParticipanteBoletoDetalle;
use Instaticket\Entidades\CicloEventoPremio;
use Instaticket\Entidades\CicloEvento;
use Instaticket\Entidades\IncrementoProbabilidadPremio;
use Instaticket\Entidades\Estados;
use Instaticket\Entidades\MigracionInformacionParticipante;
use Instaticket\Entidades\Eventos;

class PremioParticipanteController extends Controller {

    /**
     * AUTOR: INNOVASYSTEM ECUADOR S.A
     * DESCRIPCION: MÉTODO REALIZA CARGAR PANTALLA: REGISTRO PREMIO PARTICIPANTE
     * @return type
     */
    public function index() {
        try {
            Log::info('Ingreso Interfaz REGISTRO PREMIO PARTICIPANTE, con usuario: ' . Auth::user()->usuario_username);
            $objPGeneral = ParametroGeneral::buscarParametroxNombre(ParametroGeneral::$cicloEventoUsuario);
            if (empty($objPGeneral) || empty($objPGeneral->parametro_general_valor)) {
                Session::flash('messageError', "No se ha encontrado el parámetro: EVENTO_USUARIO seleccione un evento para configurar");
                return redirect('welcome');
            }
            return view('registrador.registropremioparticipante');
        } catch (\Exception $ex) {
            Log::error('CONTROLLER REGISTRO PREMIO PARTICIPANTE/index' . $ex->getMessage());
            Session::flash('messageError', $ex->getMessage());
            return redirect('welcome');
        }
    }

    public function buscarPremioParticipante(Request $request) {
        try {
            if ($request->scanner_input != null && $request->evento_id != null) {
                $obj = PremioParticipante::buscarPremioParticipantexCodigoPremio($request->scanner_input);
                $objUrlRecursos = ParametroGeneral::buscarParametroxNombre(ParametroGeneral::$urlRecursos);
                if (!empty($obj) || empty($objUrlRecursos) || empty($objUrlRecursos->parametro_general_valor)) {
                    $ruta_imagenes = $objUrlRecursos->parametro_general_valor;
                    return array($obj, $ruta_imagenes);
                }
            }
        } catch (\Exception $ex) {
            Log::error('CONTROLLER Registro Premio Participante/buscarCodigoBarra' . $ex->getMessage());
            Session::flash('messageError', $ex->getMessage());
            return redirect()->back();
        }
    }

    public function buscarParticipanteBoletoDetalle(Request $request) {
        try {
            if ($request->scanner_inputB) {
                $obj = ParticipanteBoletoDetalle::buscarDetalleParticipantexNumeroBoleto($request->scanner_inputB);
                if (!empty($obj)) {
                    return $obj;
                }
            }
        } catch (\Exception $ex) {
            Log::error('CONTROLLER Registro Premio Participante/buscarParticipanteBoletoDetalle' . $ex->getMessage());
            Session::flash('messageError', $ex->getMessage());
            return redirect()->back();
        }
    }

    public function validarPremioParticipante(Request $request) {
        try {
            $lstError = '';
            $array = array();
            if (empty($request->premio_participante_id)) {
                $msj = array("No se ha encontrado el Premio Participante a guardar verifique el escaneo de juego.");
                array_push($array, $msj);
            }
            if (empty($request->participante_id)) {
                $msj = array("No ha elegido un participante a asignar el premio para guardar verifique el escaneo de juego.");
                array_push($array, $msj);
            }
            if (empty($request->participante_boleto_det_id)) {
                $msj = array("No ha elegido un numero de boleto a asignar el premio para guardar verifique el escaneo de juego.");
                array_push($array, $msj);
            }
            $objPremioParticipante = PremioParticipante::buscarPremioParticipantexCodigoPremio($request->juego_numero);
            if (empty($objPremioParticipante) || empty($objPremioParticipante->premio_participante_id)) {
                $msj = array("No se ha encontrado el premio participante para guardar verifique el escaneo de juego.");
                array_push($array, $msj);
            }
            if ($objPremioParticipante->premio_id != $request->premio_id) {
                $msj = array("No coincide el premio a entregar con el premio participante para guardar verifique el escaneo de juego.");
                array_push($array, $msj);
            }
            if (!empty($objPremioParticipante->participante_id)) {
                $msj = array("El premio ya a sido asignado a un ganador para guardar verifique el escaneo de juego.");
                array_push($array, $msj);
            }
            if (empty($objPremioParticipante->cicloEventoPremio)) {
                $msj = array("No se ha encontrado el ciclo evento al que pertenece el premio verifique el escaneo de juego.");
                array_push($array, $msj);
            }

            if (!empty($objPremioParticipante->inc_pro_pre_id)) {
                $objIncrementoProbabilidadPremio = IncrementoProbabilidadPremio::find($objPremioParticipante->inc_pro_pre_id);
                if (empty($objIncrementoProbabilidadPremio)) {
                    $msj = array("No se ha encontrado el incremento probabilidad al que pertenece el premio verifique el escaneo de juego.");
                    array_push($array, $msj);
                } else {
                    if ($objIncrementoProbabilidadPremio->inc_pro_pre_cantidad_disponible == 0) {
                        $msj = array("No existe disponible de premio a entregar por favor verifique el escaneo de juego.");
                        array_push($array, $msj);
                    }
                }
            } else {
                $objCicloEventoPremio = CicloEventoPremio::find($objPremioParticipante->ciclo_evento_premio_id);
                if (empty($objCicloEventoPremio)) {
                    $msj = array("No se ha encontrado el ciclo evento al que pertenece el premio verifique el escaneo de juego.");
                    array_push($array, $msj);
                } else {
                    if ($objCicloEventoPremio->ciclo_evento_premio_stock_disponible == 0) {
                        $msj = array("No existe disponible de premio a entregar por favor verifique el escaneo de juego.");
                        array_push($array, $msj);
                    }
                }
            }
            if (empty($request->evento_id) && empty($request->boleto_numero)) {
                $msj = array("No existen datos de evento y numero de boleto al que pertenece el premio verifique el escaneo de boleto.");
                array_push($array, $msj);
            } else {
                $objDetalleBoletoParticipante = ParticipanteBoletoDetalle::find($request->participante_boleto_det_id);
                if (empty($objDetalleBoletoParticipante)) {
                    $msj = array("No se ha encontrado el boleto al que pertenece el premio verifique el escaneo de boleto.");
                    array_push($array, $msj);
                } else {
                    $objMigracionInformacionParticipante = MigracionInformacionParticipante::buscarMigracionInformacionParticipantexNumeroBoletoyEvento($request->evento_id, $request->boleto_numero);
                    if (empty($objMigracionInformacionParticipante)) {
                        $msj = array("No se ha encontrado infromacion de migracion de datos al que pertenece el numero de boleto verifique el escaneo.");
                        array_push($array, $msj);
                    }
                }
            }
            if (count($array) > 0) {
                $lstError = $array;
            }
            return $lstError;
        } catch (\Exception $ex) {
            Log::error('CONTROLLER REGISTRO PREMIO PARTICIPANTE/validarPremioParticipante' . $ex->getMessage());
            return array($ex->getMessage());
        }
    }

    public function guardarRegistroPremioParticipante(Request $request) {
        try {
            if (empty($request->premio_participante_id)) {
                Session::flash('messageError', "No se ha encontrado el Premio Participante a guardar verifique el escaneo de juego.");
                return redirect()->back();
            }
            $objPremioParticipante = PremioParticipante::buscarPremioParticipantexCodigoPremio($request->juego_numero);
            if (empty($objPremioParticipante) || empty($objPremioParticipante->premio_participante_id)) {
                Session::flash('messageError', "No se ha encontrado el premio participante para guardar verifique el escaneo de juego");
                return redirect()->back();
            }
            if (empty($objPremioParticipante->cicloEventoPremio)) {
                Session::flash('messageError', "No se ha encontrado el ciclo evento al que pertenece el premio verifique el escaneo de juego");
                return redirect()->back();
            }
            $objCicloEventoPremio = CicloEventoPremio::find($objPremioParticipante->ciclo_evento_premio_id);
            if (empty($objCicloEventoPremio)) {
                Session::flash('messageError', "No se ha encontrado el ciclo evento al que pertenece el premio verifique el escaneo de juego");
                return redirect()->back();
            }
            
            $evento= CicloEvento::find($objCicloEventoPremio->ciclo_evento_id);
            $verificarPremioEvento = MigracionInformacionParticipante::buscarMigracionInformacionParticipantexBoletoyEvento($evento->evento_id, $request->boleto_numero);
            if (empty($verificarPremioEvento)) {
                Session::flash('messageError', "El numero de boleto no corresponde al evento");
                return redirect()->back();
            }



            \DB::transaction(function () use ($request, $objPremioParticipante, $objCicloEventoPremio) {
                if (!empty($objPremioParticipante->inc_pro_pre_id)) {
                    $objIncrementoProbabilidadPremio = IncrementoProbabilidadPremio::find($objPremioParticipante->inc_pro_pre_id);
                    if (empty($objIncrementoProbabilidadPremio)) {
                        Session::flash('messageError', "No se ha encontrado el incremento probabilidad al que pertenece el premio verifique el escaneo de juego");
                        return redirect()->back();
                    } else {
                        if ($objIncrementoProbabilidadPremio->inc_pro_pre_cantidad_disponible == 0) {
                            Session::flash('messageError', "No existe disponible de premio a entregar por favor verifique el escaneo de juego");
                            return redirect()->back();
                        } else {
                            $objIncrementoProbabilidadPremio->inc_pro_pre_cantidad_disponible = $objIncrementoProbabilidadPremio->inc_pro_pre_cantidad_disponible - 1;
                            $objIncrementoProbabilidadPremio->save();
                        }
                    }
                } else {
                    $objCicloEventoPremio->ciclo_evento_premio_stock_disponible = $objCicloEventoPremio->ciclo_evento_premio_stock_disponible - 1;
                    $objCicloEventoPremio->save();
                }
                if (empty($request->evento_id) && empty($request->boleto_numero)) {
                    Session::flash('messageError', "No existen datos de evento y numero de boleto al que pertenece el premio verifique el escaneo de boleto.");
                    return redirect()->back();
                } else {
                    $objEvento = Eventos::find($request->evento_id);
                    if (empty($objEvento)) {
                        Session::flash('messageError', "No se ha encontrado el evento al que pertenece el premio verifique el escaneo de boleto.");
                        return redirect()->back();
                    } else {
                        $objEvento->evento_total_jugadas = $objEvento->evento_total_jugadas + 1;
                        $objEvento->save();
                        $objDetalleBoletoParticipante = ParticipanteBoletoDetalle::find($request->participante_boleto_det_id);
                        if (empty($objDetalleBoletoParticipante)) {
                            Session::flash('messageError', "No se ha encontrado el boleto al que pertenece el premio verifique el escaneo de boleto.");
                            return redirect()->back();
                        } else {
                            $objMigracionInformacionParticipante = MigracionInformacionParticipante::buscarMigracionInformacionParticipantexNumeroBoletoyEvento($request->evento_id, $request->boleto_numero);
                            if (empty($objMigracionInformacionParticipante)) {
                                Session::flash('messageError', "No se ha encontrado infromacion de migracion de datos al que pertenece el numero de boleto verifique el escaneo.");
                                return redirect()->back();
                            } else {
                                $objMigracionInformacionParticipante->mig_inf_par_juego = "S";
                                $objMigracionInformacionParticipante->save();
                            }
                        }
                    }
                }
                $fechaActual = date("Y-m-d H:i:s");
                $objPremioParticipante->participante_id = $request->participante_id;
                $objPremioParticipante->participante_boleto_det_id = $request->participante_boleto_det_id;
                $objPremioParticipante->estado_id = Estados::$estadoEntregado;
                $objPremioParticipante->fecha_entrega_premio = $fechaActual;
                $objPremioParticipante->save();
            });
            Session::flash('message', 'PREMIO PARTICIPANTE  ha sido registrado con éxito.');
            return redirect()->back();
        } catch (\Exception $ex) {
            \DB::rollback();
            Log::error('CONTROLLER Registro Premio Participante/guardarRegistroPremioParticipante' . $ex->getMessage());
            Session::flash('messageError', $ex->getMessage());
            return redirect()->back();
        }
    }

}
