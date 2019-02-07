<?php
namespace Instaticket\Http\Controllers\Registrador;
use Instaticket\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use Validator;
use Auth;
use Illuminate\Support\Facades\Log;
use Instaticket\Entidades\Participante;
use Instaticket\Entidades\MigracionInformacionParticipante;
use Instaticket\Entidades\ParticipanteBoletoDetalle;
use Instaticket\Entidades\ParametroGeneral;
use Instaticket\Entidades\LogParticipante;
use Instaticket\Entidades\Eventos;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;
use Mike42\Escpos\Printer;
use Mike42\Escpos\EscposImage;
class RegistroParticipanteController extends Controller
{
     /**
     * AUTOR: INNOVASYSTEM ECUADOR S.A
     * DESCRIPCION: MÉTODO REALIZA CARGAR PANTALLA: REGISTRO PARTICIPANTE
     * @return type
     */
    public function index() {
        try {
            Log::info('Ingreso Interfaz REGISTRO PARTICIPANTE, con usuario: ' . Auth::user()->usuario_username);
            $objPGeneral = ParametroGeneral::buscarParametroxNombre(ParametroGeneral::$cicloEventoUsuario);
            if (empty($objPGeneral) || empty($objPGeneral->parametro_general_valor)) {
                Session::flash('messageError', "No se ha encontrado el parámetro: EVENTO_USUARIO seleccione un evento para configurar");
                return redirect('welcome');
            }
            return view('registrador.registroparticipante');
        } catch (\Exception $ex) {
            Log::error('CONTROLLER REGISTRO PARTICIPANTE/index' . $ex->getMessage());
            Session::flash('messageError', $ex->getMessage());
            return redirect('welcome');
        }
    }
    public function buscarCodigoBarra(Request $request) {
        try {
            $existe=false;
            if ($request->scanner_input != null && $request->evento_id != null ) {
                $obj = MigracionInformacionParticipante::buscarMigracionInformacionParticipantexBoletoyEvento($request->evento_id, $request->scanner_input);
                if (!empty($obj)) {
                    $objDP = ParticipanteBoletoDetalle::buscarDetalleParticipantexNumeroBoleto($obj->mig_inf_par_numero_boleto);
                     if (!empty($objDP)) {
                            return array(1,$obj,$objDP);
                     }
                     else{
                            return array(2,$obj);
                     }
                }
            }
         } catch (\Exception $ex) {
            Log::error('CONTROLLER Registro Participante/buscarCodigoBarra' . $ex->getMessage());
            Session::flash('messageError', $ex->getMessage());
            return redirect()->back();
        }
    }
    public function buscarParticipante(Request $request) {
        try {
            $existe=false;
            if ($request->participante_cedula ) {
                $obj = Participante::buscarParticipantexCedula($request->participante_cedula);
                if (!empty($obj)) {
                    return array(3,$obj); 
                }
                else{
                    return array(4); 
                }
            }
         } catch (\Exception $ex) {
            Log::error('CONTROLLER Registro Participante/buscarParticipante' . $ex->getMessage());
            Session::flash('messageError', $ex->getMessage());
            return redirect()->back();
        }
    }
    public function validarRegistroParticipante(Request $request) {
        try {
            $lstError = '';
            $messages = [
                'participante_cedula.required' => 'El campo cédula es requerido.',
                'participante_cedula.max' => 'El campo cédula debe tener un tamaño de 13 caracteres.',
                'participante_email.required' => 'El campo email es requerido.',
                'participante_email.max' => 'El campo email debe tener un tamaño de 100 caracteres.',
                'participante_celular.required' => 'El campo celular es requerido.',
                'participante_celular.max' => 'El campo celular debe tener un tamaño de 10 caracteres.',
            ];
                $validator = Validator::make($request->all(), [
                            'participante_cedula' => 'max:13|required|',
                            'participante_email' => 'max:100|required|',
                            'participante_celular' => 'max:150|required|',
                                ], $messages);
            
            if ($validator->fails()) {
                $lstError = $validator->errors();
            } 
            return $lstError;
        } catch (\Exception $ex) {
            Log::error('CONTROLLER Registro Participante/validarRegistroParticipante' . $ex->getMessage());
            return array($ex->getMessage());
        }
    }
    public function guardarRegistroParticipante(Request $request) {
        try {
                $obj = new Participante();
                \DB::transaction(function () use ($request, $obj) {
                    $obj->participante_cedula = $request->participante_cedula;
                    if( empty($request->participante_nombre) ){
                        $obj->participante_nombre = $request->participante_nombre;
                    }
                    else{
                        $obj->participante_nombre = strtoupper($request->participante_nombre);
                    }
                    $obj->participante_email = $request->participante_email;
                    $obj->participante_telefono = $request->participante_telefono;
                    $obj->participante_celular = $request->participante_celular;
                    $obj->save();
                    $objC = Participante::find($obj->participiante_id);
                    $objDP = new ParticipanteBoletoDetalle();
                    $objDP->participante_id = $objC->participiante_id;
                    $objDP->participante_boleto_det_numero_boleto = $request->boleto_numero;
                    $objDP->save();
                    $objMP = MigracionInformacionParticipante::find($request->mig_inf_par_id);
                    $objMP->mig_inf_par_actualizo = "S";
                    $objMP->save();
                    $var = 'PARTICIPANTE_ID: ' . $objC->participiante_id .
                            ' |NOMBRE: ' . $objC->participante_nombre .
                            ' |CÉDULA: ' . $objC->participante_cedula .
                            ' |EMAIL: ' . $objC->participante_email .
                            ' |TELÉFONO: ' . $objC->participante_telefono .
                            ' |CELULAR: ' . $objC->participante_celular.
                            ' |BOLETO: ' . $request->boleto_numero ;
                    $log = new LogParticipante();
                    $log->registro_nuevo = $var;
                    $log->id_tabla = $objC->participiante_id;
                    $log->tipo_accion = 'I';
                    $log->save();
                    $fechaActual = date("Y-m-d");
                    $obImpresora = ParametroGeneral::buscarParametroxNombre(ParametroGeneral::$impresoraActiva);
                    if (!empty($obImpresora) && !empty($obImpresora->parametro_general_valor) && $obImpresora->parametro_general_valor == "SI") {
                        $objEvento = Eventos::find($request->evento_id);
                        if(!empty($objEvento)){
                            $connector = new WindowsPrintConnector("LR2000");
                            $printer = new Printer($connector);
                            $img = EscposImage::load("img/logo.png",FALSE);
                            try {
                                $printer->setJustification(Printer::JUSTIFY_CENTER);
                                $printer->text("------------------------------------------\n");
                                $printer -> bitImage($img, Printer::IMG_DOUBLE_WIDTH);
                                $printer->setTextSize(1,1);
                                $printer->text("------------------------------------------\n");
                                $printer->setTextSize(2, 2);
                                $printer->text($objEvento->evento_nombre."\n");
                                $printer->setTextSize(1, 1);
                                $printer->text("PUERTA MILLONARIA\n");
                                $printer->text($fechaActual."\n");
                                $printer->text("------------------------------------------\n");
                                $printer->setJustification(Printer::JUSTIFY_LEFT);
                                $printer->text("CEDULA: ".$objC->participante_cedula."\n");
                                $printer->text("NOMBRE: ".$objC->participante_nombre."\n");
                                $printer->text("ID: ".$objC->participiante_id ."\n");
                                $printer->feed(1);
                                $printer->setJustification(Printer::JUSTIFY_CENTER);
                                $printer->barcode( $objC->participiante_id , Printer::BARCODE_CODE39);
                                $printer->feed(10);
                                $printer->cut();
                                $printer->close();
                            } 
                            catch (Exception $e) {
                                $printer -> close();
                                Session::flash('messageError', $e -> getMessage());
                                return redirect('welcome');
                            }
                        }
                    } 
                    Session::flash('message', 'PARTICIPANTE  ha sido registrado con éxito. Puedes pasar a jugar');
                    return redirect()->back();
                });
				return redirect()->back();
        } catch (\Exception $ex) {
            \DB::rollback();
            Log::error('CONTROLLER Registro Participante/guardarRegistroParticipante' . $ex->getMessage());
            Session::flash('messageError', $ex->getMessage());
            return redirect()->back();
        }
		return redirect()->back();
    }
     public function actualizarRegistroParticipante(Request $request) {
        try {
            if ($request->boleto_numero != null) {
                $obj = Participante::find($request->participiante_id);
                if (!empty($obj)) {
                    $var = 'PARTICIPANTE_ID: ' . $obj->participiante_id .
                            ' |NOMBRE: ' . $obj->participante_nombre .
                            ' |CÉDULA: ' . $obj->participante_cedula .
                            ' |EMAIL: ' . $obj->participante_email .
                            ' |TELÉFONO: ' . $obj->participante_telefono .
                            ' |CELULAR: ' . $obj->participante_celular;
                    \DB::transaction(function () use ($request, $obj, $var) {  
                        $obj->participante_cedula = $request->participante_cedula;
                        if( empty($request->participante_nombre) ){
                            $obj->participante_nombre = $request->participante_nombre;
                        }
                        else{
                            $obj->participante_nombre = strtoupper($request->participante_nombre);
                        }
                        $obj->participante_email = $request->participante_email;
                        $obj->participante_telefono = $request->participante_telefono;
                        $obj->participante_celular = $request->participante_celular;
                        $obj->save();
                        $objC = Participante::find($obj->participiante_id);
                        $objDP = new ParticipanteBoletoDetalle();
                        $objDP->participante_id = $objC->participiante_id;
                        $objDP->participante_boleto_det_numero_boleto = $request->boleto_numero;
                        $objDP->save();
                        $objMP = MigracionInformacionParticipante::find($request->mig_inf_par_id);
                        $objMP->mig_inf_par_actualizo = "S";
                        $objMP->save();
                        $var2 = 'PARTICIPANTE_ID: ' . $objC->participiante_id .
                                ' |NOMBRE: ' . $objC->participante_nombre .
                                ' |CÉDULA: ' . $objC->participante_cedula .
                                ' |EMAIL: ' . $objC->participante_email .
                                ' |TELÉFONO: ' . $objC->participante_telefono .
                                ' |CELULAR: ' . $objC->participante_celular.
                                ' |BOLETO: ' . $request->boleto_numero ;

                        $log = new LogParticipante();
                        $log->registro_anterior = $var;
                        $log->registro_nuevo = $var2;
                        $log->id_tabla = $objC->participiante_id;
                        $log->tipo_accion = 'A';
                        $log->save();
                        $fechaActual = date("Y-m-d");
                        $obImpresora = ParametroGeneral::buscarParametroxNombre(ParametroGeneral::$impresoraActiva);
                        if (!empty($obImpresora) && !empty($obImpresora->parametro_general_valor) && $obImpresora->parametro_general_valor == "SI") {
                            $objEvento = Eventos::find($request->evento_id);
                            if(!empty($objEvento)){
                                $connector = new WindowsPrintConnector("LR2000");
                                $printer = new Printer($connector);
                                $img = EscposImage::load("img/logo.png",FALSE);
                                try {
                                    $printer->setJustification(Printer::JUSTIFY_CENTER);
                                    $printer->text("------------------------------------------\n");
                                    $printer -> bitImage($img, Printer::IMG_DOUBLE_WIDTH);
                                    $printer->setTextSize(1,1);
                                    $printer->text("------------------------------------------\n");
                                    $printer->setTextSize(2, 2);
                                    $printer->text($objEvento->evento_nombre."\n");
                                    $printer->setTextSize(1, 1);
                                    $printer->text("JUEGA Y GANA CON INSTATICKET\n");
                                    $printer->text($fechaActual."\n");
                                    $printer->text("------------------------------------------\n");
                                    $printer->setJustification(Printer::JUSTIFY_LEFT);
                                    $printer->text("CEDULA: ".$objC->participante_cedula."\n");
                                    $printer->text("NOMBRE: ".$objC->participante_nombre."\n");
                                    $printer->text("TURNO: ".$objC->participiante_id ."\n");
                                    $printer->feed(1);
                                    $printer->setJustification(Printer::JUSTIFY_CENTER);
                                    $printer->barcode( $objC->participiante_id , Printer::BARCODE_CODE39);
                                    $printer->feed(10);
                                    $printer->cut();
                                    $printer->close();
                                } 
                                catch (Exception $e) {
                                    $printer -> close();
                                    Session::flash('messageError', $e -> getMessage());
                                    return redirect('welcome');
                                }
                            }
                        } 
                    });
                    Session::flash('message', 'Participante ha sido actualizado con éxito.');
                } else {
                    Session::flash('messageError', "No se ha encontrado datos de participante.");
                }
            } else {
                Session::flash('messageError', "No se ha escaneado un boleto.");
            }
            return redirect()->back();
        } catch (\Exception $ex) {
            \DB::rollback();
            Log::error('CONTROLLER RegistroParticipante/actualizarRegistroParticipante' . $ex->getMessage());
            Session::flash('messageError', $ex->getMessage());
            return redirect()->back();
        }
    }
    public function actualizarParametro(Request $request) {
        try {
            $obj = ParametroGeneral::buscarParametroxNombre(ParametroGeneral::$eventoUsuario);            
            \DB::transaction(function () use ($request, $obj) {
                $obj->parametro_general_valor = $request->evento_id;
                $obj->parametro_general_descripcion = "Evento configurado desde el administrador del usuario registrador" . Auth::user()->usuario_username;
                $obj->save();
            });
            Session::flash('message', 'PARAMETRO  ha sido actualizado con éxito.');
            return redirect('registroparticipante');
        } catch (\Exception $ex) {
            \DB::rollback();
            Log::error('CONTROLLER Categorias/guardarCategoria' . $ex->getMessage());
            Session::flash('messageError', $ex->getMessage());
            return redirect()->back();
        }
    }
}
