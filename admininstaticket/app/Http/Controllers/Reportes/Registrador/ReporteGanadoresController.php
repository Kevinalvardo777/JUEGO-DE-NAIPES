<?php
namespace Instaticket\Http\Controllers\Reportes\Registrador;
use Instaticket\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use Validator;
use Auth;
use Instaticket\Entidades\ParametroGeneral;
use Instaticket\Entidades\PremioParticipante;
use Illuminate\Support\Facades\Log;
class ReporteGanadoresController extends Controller {
    /**
     * AUTOR: INNOVASYSTEM ECUADOR S.A
     * DESCRIPCION: MÉTODO REALIZA CARGAR PANTALLA: REPORTE GANADORES PREMIOS
     * @return type
     */
    public function index() {
        try {
            Log::info('Ingreso Interfaz REPORTE PREMIOS, con usuario: ' . Auth::user()->usuario_username);
            $objPGeneral = ParametroGeneral::buscarParametroxNombre(ParametroGeneral::$cicloEventoUsuario);
            if (empty($objPGeneral) || empty($objPGeneral->parametro_general_valor)) {
                Session::flash('messageError', "No se ha encontrado el parámetro: EVENTO_USUARIO seleccione un evento para configurar");
                return redirect('welcome');
            }
            $lstGanadoresJuego = PremioParticipante::buscarPremioParticipantexCicloEvento($objPGeneral->parametro_general_valor);
            return view('reportes.registrador.reporteGanadores', compact('lstGanadoresJuego'));
        } catch (\Exception $ex) {
            Log::error('CONTROLLER ReporteGanadoresController/index: '.$ex->getMessage());
            Session::flash('messageError', $ex->getMessage());
            return redirect('home');
        }
    }
}
