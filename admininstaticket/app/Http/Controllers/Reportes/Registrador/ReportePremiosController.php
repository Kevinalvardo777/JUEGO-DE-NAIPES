<?php
namespace Instaticket\Http\Controllers\Reportes\Registrador;
use Instaticket\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use Validator;
use Auth;
use Instaticket\Entidades\ParametroGeneral;
use Instaticket\Entidades\Premios;
use Instaticket\Entidades\CicloEventoPremio;
use Illuminate\Support\Facades\Log;
class ReportePremiosController extends Controller {
    /**
     * AUTOR: INNOVASYSTEM ECUADOR S.A
     * DESCRIPCION: MÉTODO REALIZA CARGAR PANTALLA: REPORTE PREMIOS
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
            $lstPremiosJuego = Premios::buscarFiltrosPremios($objPGeneral->parametro_general_valor);
            $totalPremios = CicloEventoPremio::buscarTotalCicloEventoPremiosxCicloEvento($objPGeneral->parametro_general_valor);
            $totalDisponible = CicloEventoPremio::buscarDisponibleCicloEventoPremiosxCicloEvento($objPGeneral->parametro_general_valor);
            $totalRetirado = CicloEventoPremio::buscarRetiradosCicloEventoPremiosxCicloEvento($objPGeneral->parametro_general_valor);
            return view('reportes.registrador.reportePremios', compact('lstPremiosJuego','totalPremios','totalDisponible','totalRetirado'));
        } catch (\Exception $ex) {
            Log::error('CONTROLLER RrpPremios/index: '.$ex->getMessage());
            Session::flash('messageError', $ex->getMessage());
            return redirect('home');
        }
    }
}
