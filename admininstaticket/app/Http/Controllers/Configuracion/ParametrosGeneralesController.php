<?php

namespace Instaticket\Http\Controllers\Configuracion;

use Instaticket\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use Validator;
use Auth;
use Illuminate\Support\Facades\Log;
use Instaticket\Entidades\CicloEvento;
use Instaticket\Entidades\ParametroGeneral;

class ParametrosGeneralesController extends Controller {

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        try {
            Log::info('Ingreso Interfaz CICLO EVENTO, con usuario: ' . Auth::user()->usuario_username);
            //$lstCiclosEventos = CicloEvento::buscarTodosCiclosEventos();
            //$lstCiclo = Ciclo::buscarTodosCiclos();
            $lstParametros = ParametroGeneral::buscarTodosParametros();

            $recursos = "";
            $url = "";
            $evento = "";
            $impresora = "";

            foreach ($lstParametros as $parametro) {
                if ($parametro->parametro_general_nombre == ParametroGeneral::$rutaRecursos) {
                    $recursos = $parametro->parametro_general_valor;
                }
                if ($parametro->parametro_general_nombre == ParametroGeneral::$urlRecursos) {
                    $url = $parametro->parametro_general_valor;
                }
                if ($parametro->parametro_general_nombre == ParametroGeneral::$cicloEventoUsuario) {
                    $evento = $parametro->parametro_general_valor;
                }
                if ($parametro->parametro_general_nombre == ParametroGeneral::$impresoraActiva) {
                    $impresora = $parametro->parametro_general_valor;
                }
            }

            $lstCiclosEventos = CicloEvento::buscarTodosCiclosEventos();
            //dd($lstCiclosEventos);
            //$lstEventos = Eventos::buscarTodosEventos();
            return view('configuracion.parametrosgenerales', compact('lstCiclosEventos', 'url', 'recursos', 'evento', 'impresora'));
        } catch (\Exception $ex) {
            Log::error('CONTROLLER Parametros Generales/index' . $ex->getMessage());
            Session::flash('messageError', $ex->getMessage());
            return redirect('welcome');
        }
    }

    public function actualizarParametrosGenerales(Request $request) {
        try {

            \DB::transaction(function () use ($request) {

                $recursos = ParametroGeneral::buscarParametroxNombre(ParametroGeneral::$rutaRecursos);
                $recursos->parametro_general_valor = $request->ruta_recursos;
                $recursos->save();

                $url = ParametroGeneral::buscarParametroxNombre(ParametroGeneral::$urlRecursos);
                $url->parametro_general_valor = $request->url_recursos;
                $url->save();

                $cicloEnvento = ParametroGeneral::buscarParametroxNombre(ParametroGeneral::$cicloEventoUsuario);
                $cicloEnvento->parametro_general_valor = $request->evento_ciclo_id;
                $cicloEnvento->save();

                $impresora = ParametroGeneral::buscarParametroxNombre(ParametroGeneral::$impresoraActiva);
                $impresora->parametro_general_valor = $request->impresora;
                $impresora->save();
            });
            Session::flash('message', 'Parametros Generales ha sido actualizado con éxito.');

            return redirect()->back();
        } catch (\Exception $ex) {
            \DB::rollback();
            Log::error('CONTROLLER Parametros Generales/actualizarParametrosGenerales' . $ex->getMessage());
            Session::flash('messageError', $ex->getMessage());
            return redirect()->back();
        }
    }

}
