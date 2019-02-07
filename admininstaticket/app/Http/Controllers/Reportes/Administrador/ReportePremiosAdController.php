<?php
namespace Instaticket\Http\Controllers\Reportes\Administrador;
use Instaticket\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use Validator;
use Auth;
use Instaticket\Entidades\ParametroGeneral;
use Instaticket\Entidades\Premios;
use Instaticket\Entidades\CicloEventoPremio;
use Instaticket\Entidades\CicloEvento;
use Illuminate\Support\Facades\Log;
class ReportePremiosAdController extends Controller {
    /**
     * AUTOR: INNOVASYSTEM ECUADOR S.A
     * DESCRIPCION: MÉTODO REALIZA CARGAR PANTALLA: REPORTE PREMIOS ADMINISTRADOR
     * @return type
     */
    public function index() {
        try {
            Log::info('Ingreso Interfaz REPORTE PREMIOS ADMINISTRADOR, con usuario: ' . Auth::user()->usuario_username);
            $objPGeneral = ParametroGeneral::buscarParametroxNombre(ParametroGeneral::$cicloEventoUsuario);
            if (empty($objPGeneral) || empty($objPGeneral->parametro_general_valor)) {
                Session::flash('messageError', "No se ha encontrado el parámetro: EVENTO_USUARIO seleccione un evento para configurar");
                return redirect('welcome');
            }
            $lstCicloEvento = CicloEvento::buscarTodosCiclosEventosActivos();
            return view('reportes.administrador.reportePremiosAd', compact('lstCicloEvento'));
        } catch (\Exception $ex) {
            Log::error('CONTROLLER RrpPremiosAd/index: '.$ex->getMessage());
            Session::flash('messageError', $ex->getMessage());
            return redirect('home');
        }
    }
    public function buscarPremiosxCicloEvento(Request $request) {
        try {
            if ($request->ciclo_evento_id ) {
                $lstPremiosJuego = Premios::buscarFiltrosPremios($request->ciclo_evento_id);
                if (!empty($lstPremiosJuego)) {
                    $totalPremios = CicloEventoPremio::buscarTotalCicloEventoPremiosxCicloEvento($request->ciclo_evento_id);
                    if (!empty($totalPremios)) {
                        $totalDisponible = CicloEventoPremio::buscarDisponibleCicloEventoPremiosxCicloEvento($request->ciclo_evento_id);
                        if (!empty($totalDisponible)) {
                            $totalRetirado = CicloEventoPremio::buscarRetiradosCicloEventoPremiosxCicloEvento($request->ciclo_evento_id);
                            if (!empty($totalRetirado)) {
                                return array($lstPremiosJuego,$totalPremios->total_premios,$totalDisponible->total_disponible,$totalRetirado->total_retirado); 
                            }
                        }
                    }
                }
            }
        } catch (\Exception $ex) {
            Log::error('CONTROLLER RrpPremios/buscarFiltrosPremios: '.$ex->getMessage());
            Session::flash('messageError', $ex->getMessage());
            return redirect()->back();
        }
    }
}
