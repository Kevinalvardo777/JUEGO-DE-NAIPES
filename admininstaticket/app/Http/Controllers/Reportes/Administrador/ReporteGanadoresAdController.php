<?php
namespace Instaticket\Http\Controllers\Reportes\Administrador;
use Instaticket\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use Validator;
use Auth;
use Instaticket\Entidades\ParametroGeneral;
use Instaticket\Entidades\PremioParticipante;
use Instaticket\Entidades\CicloEvento;
use Illuminate\Support\Facades\Log;
class ReporteGanadoresAdController extends Controller {
    /**
     * AUTOR: INNOVASYSTEM ECUADOR S.A
     * DESCRIPCION: MÉTODO REALIZA CARGAR PANTALLA: REPORTE GANADORES PREMIOS
     * @return type
     */
    public function index() {
        try {
            Log::info('Ingreso Interfaz REPORTE GANADORES PREMIOS, con usuario: ' . Auth::user()->usuario_username);
            $objPGeneral = ParametroGeneral::buscarParametroxNombre(ParametroGeneral::$cicloEventoUsuario);
            if (empty($objPGeneral) || empty($objPGeneral->parametro_general_valor)) {
                Session::flash('messageError', "No se ha encontrado el parámetro: EVENTO_USUARIO seleccione un evento para configurar");
                return redirect('welcome');
            }
            $lstCicloEvento = CicloEvento::buscarTodosCiclosEventosActivos();
            return view('reportes.administrador.reporteGanadoresAd', compact('lstCicloEvento'));
        } catch (\Exception $ex) {
            Log::error('CONTROLLER ReporteGanadoresController/index: '.$ex->getMessage());
            Session::flash('messageError', $ex->getMessage());
            return redirect('home');
        }
    }
    public function buscarGanadoresxCicloEvento(Request $request) {
        try {
            if ($request->ciclo_evento_id ) {
                $lstGanadoresJuego = PremioParticipante::buscarPremioParticipantexCicloEvento($request->ciclo_evento_id);
                if (!empty($lstGanadoresJuego)) {
                                return $lstGanadoresJuego; 
                }
            }
        } catch (\Exception $ex) {
            Log::error('CONTROLLER Reporte Ganadores Premios/buscarGanadoresxCicloEvento: '.$ex->getMessage());
            Session::flash('messageError', $ex->getMessage());
            return redirect()->back();
        }
    }
}
