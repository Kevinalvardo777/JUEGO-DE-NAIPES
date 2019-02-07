<?php
namespace Instaticket\Http\Controllers\Configuracion;
use Instaticket\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use Validator;
use Auth;
use Instaticket\Entidades\Usuario;
use Instaticket\Entidades\TipoUsuarios;
use Instaticket\Entidades\ParametroGeneral;
use Instaticket\Entidades\LogUsuarios;
use Illuminate\Support\Facades\Log;
class UsuariosController extends Controller {
    /**
     * AUTOR: INNOVASYSTEM ECUADOR S.A
     * DESCRIPCION: MÉTODO REALIZA CARGAR PANTALLA: USUARIOS
     * @return type
     */
    public function index() {
        try {
            Log::info('Ingreso Interfaz Usuarios, con usuario: ' . Auth::user()->usuario_username);
            $objPGeneral = ParametroGeneral::buscarParametroxNombre(ParametroGeneral::$tipoUsuarioAdmin);
            if (empty($objPGeneral) || empty($objPGeneral->parametro_general_valor)) {
                Session::flash('messageError', "No se ha encontrado el parámetro: TIPO_USUARIO_ADMIN");
                return redirect('welcome');
            }
            $lstUsuarios = Usuario::with('tipoUsuario', 'estado','logUsuario')->get();
            $lstTipoUsuarios = TipoUsuarios::buscarTodosTipoUsuarios();
            return view('configuracion.usuario', compact('lstUsuarios','lstTipoUsuarios'));
        } catch (\Exception $ex) {
            Log::error('CONTROLLER Usuarios/index'. $ex->getMessage());
            Session::flash('messageError', $ex->getMessage());
            return redirect('welcome');
        }
    }
    public function validarUsuario(Request $request) {
        try {
            $lstError = '';
            $array = array();
            $messages = [
                'usuario_username.required' => 'El campo username es requerido.',
                'usuario_username.max' => 'El campo username debe tener un tamaño de 45 caracteres.',
                'usuario_username.unique' => 'Username ' . $request->usuario_username . ' ya se encuentra registrado.',
                'usuario_password.required' => 'La contraseña es requerido.',
                'usuario_password.max' => 'La contraseña debe tener un tamaño de 100 caracteres.',
                'usuario_nombre.required' => 'El campo nombre es requerido.',
                'usuario_nombre.max' => 'El campo nombre debe tener un tamaño de 150 caracteres.',
                'usuario_apellido.required' => 'El campo apellido es requerido.',
                'usuario_apellido.max' => 'El campo apellido debe tener un tamaño de 150 caracteres.',
                'usuario_telefono.required' => 'El campo teléfono es requerido.',
                'usuario_telefono.max' => 'El campo teléfono debe tener un tamaño de 13 caracteres.',
                'usuario_email.required' => 'El campo email es requerido.',
                'usuario_email.max' => 'El campo email debe tener un tamaño de 40 caracteres.',
                'estado_id.required' => 'El campo estado es requerido.',
                'estado_id.max' => 'El campo estado debe tener un tamaño de 11 caracteres.',
                'tipo_usuario_id.required' => 'El campo tipo es requerido.',
                'tipo_usuario_id.max' => 'El campo tipo debe tener un tamaño de 11 caracteres.',
            ];
            if (!empty($request->usuario_id)) {
                $validator = Validator::make($request->all(), [
                            'usuario_username' => 'max:45|required|unique:usuario,usuario_username,' . $request->usuario_id . ',usuario_id',
                            'usuario_password' => 'max:100|required',
                            'usuario_nombre' => 'max:150|required',
                            'usuario_apellido' => 'max:150|required',
                            'usuario_telefono' => 'max:150|required',
                            'usuario_email' => 'max:150|required',
                            'estado_id' => 'required|',
                            'tipo_usuario_id' => 'required|',
                                ], $messages);
            } else {
                $validator = Validator::make($request->all(), [
                            'usuario_username' => 'max:45|required|unique:usuario,usuario_username',
                            'usuario_password' => 'max:100|required',
                            'usuario_nombre' => 'max:150|required',
                            'usuario_apellido' => 'max:150|required',
                            'usuario_telefono' => 'max:150|required',
                            'usuario_email' => 'max:150|required',
                            'estado_id' => 'required|',
                            'tipo_usuario_id' => 'required|',
                                ], $messages);
            }
            if ($validator->fails()) {
                $lstError = $validator->errors();
            }
            else{
                if ($request->usuario_password != $request->usuario_password2) {
                        $msj = array("La contraseña debe ser igual al confirmar contraseña");
                        array_push($array, $msj);
                    }
                    if (count($array) > 0) {
                        $lstError = $array;
                    }
            }
            return $lstError;
        } catch (\Exception $ex) {
            Log::error('CONTROLLER Usuarios/validarUsuario'. $ex->getMessage());
            return array($ex->getMessage());
        }
    }
    public function guardarUsuario(Request $request) {
        try {
            $obj = new Usuario();
            \DB::transaction(function () use ($request, $obj) {
                $obj->usuario_username = $request->usuario_username;
                if( $request->tipo_usuario_id == TipoUsuarios::$tipoUsuarioTelevisor ){
                    $obj->usuario_password = sha1($request->usuario_password);
                }
                else{
                    $obj->usuario_password = \Hash::make($request->usuario_password);
                }
                $obj->usuario_nombre = strtoupper($request->usuario_nombre);
                $obj->usuario_apellido = strtoupper($request->usuario_apellido);
                $obj->usuario_telefono = $request->usuario_telefono;
                $obj->usuario_email = $request->usuario_email;
                $obj->tipo_usuario_id = $request->tipo_usuario_id;
                $obj->estado_id = $request->estado_id;
                $obj->save();
                $objC = Usuario::with('estado', 'tipoUsuario')->find($obj->usuario_id);
                $var = 'USUARIO_ID: ' . $objC->usuario_id .
                        ' |USERNAME: ' . $objC->usuario_username .
                        ' |PASSWORD: ' . $request->usuario_password .
                        ' |NOMBRE: ' . $objC->usuario_nombre .
                        ' |APELLIDO: ' . $objC->usuario_apellido .
                        ' |TELÉFONO: ' . $objC->usuario_telefono .
                        ' |EMAIL: ' . $objC->usuario_email .
                        ' |TIPO USUARIO: ' . $objC->tipo_usuario_id . '-' . $objC->tipoUsuario->tipo_usuario_nombre .
                        ' |ESTADO: ' . $objC->estado_id . '-' . $objC->estado->estado_nombre;

                $log = new LogUsuarios();
                $log->registro_nuevo = $var;
                $log->id_tabla = $obj->usuario_id;
                $log->tipo_accion = 'I';
                $log->save();
            });
            Session::flash('message', 'USUARIO: ' . $request->usuario_username . ' ha sido registrado con éxito.');
            return redirect()->back();
        } catch (\Exception $ex) {
            \DB::rollback();
            Log::error('CONTROLLER Usuarios/guardarUsuario'. $ex->getMessage());
            Session::flash('messageError', $ex->getMessage());
            return redirect()->back();
        }
    }
    public function buscarUsuario(Request $request) {
        try {
            if ($request->usuario_id != null) {
                $obj = Usuario::find($request->usuario_id);  
                if (!empty($obj)) {
                    return $obj;
                }
            }
        } catch (\Exception $ex) {
            Log::error('CONTROLLER Usuarios/buscarUsuario'. $ex->getMessage());
            Session::flash('messageError', $ex->getMessage());
            return redirect()->back();
        }
    }
    public function actualizarUsuario(Request $request) {
        try {
            if ($request->usuario_id != null) {
                $obj = Usuario::with('estado','tipoUsuario','logUsuario')->find($request->usuario_id);
                if (!empty($obj)) {
                    $var = 'USUARIO_ID: ' . $obj->usuario_id .
                        ' |USERNAME: ' . $obj->usuario_username .
                        ' |PASSWORD: ' . $request->usuario_password .
                        ' |NOMBRE: ' . $obj->usuario_nombre .
                        ' |APELLIDO: ' . $obj->usuario_apellido .
                        ' |TELÉFONO: ' . $obj->usuario_telefono .
                        ' |EMAIL: ' . $obj->usuario_email .
                        ' |TIPO USUARIO: ' . $obj->tipo_usuario_id . '-' . $obj->tipoUsuario->tipo_usuario_nombre .
                        ' |ESTADO: ' . $obj->estado_id . '-' . $obj->estado->estado_nombre;    
                    \DB::transaction(function () use ($request, $obj, $var) {
                            $obj->usuario_username = $request->usuario_username;
                            if( $request->tipo_usuario_id == TipoUsuarios::$tipoUsuarioTelevisor ){
                                $obj->usuario_password = sha1($request->usuario_password);
                            }
                            else{
                                $obj->usuario_password = \Hash::make($request->usuario_password);
                            }
                            $obj->usuario_nombre = strtoupper($request->usuario_nombre);
                            $obj->usuario_apellido = strtoupper($request->usuario_apellido);
                            $obj->usuario_telefono = $request->usuario_telefono;
                            $obj->usuario_email = $request->usuario_email;
                            $obj->tipo_usuario_id = $request->tipo_usuario_id;
                            $obj->estado_id = $request->estado_id;
                            $obj->save(); 
                            $objC = Usuario::with('estado', 'tipoUsuario' , 'logUsuario')->find($obj->usuario_id);
                            $var2 = 'USUARIO_ID: ' . $objC->usuario_id .
                                    ' |USERNAME: ' . $objC->usuario_username .
                                    ' |PASSWORD: ' . $request->usuario_password .
                                    ' |NOMBRE: ' . $objC->usuario_nombre .
                                    ' |APELLIDO: ' . $objC->usuario_apellido .
                                    ' |TELÉFONO: ' . $objC->usuario_telefono .
                                    ' |EMAIL: ' . $objC->usuario_email .
                                    ' |TIPO USUARIO: ' . $objC->tipo_usuario_id . '-' . $objC->tipoUsuario->tipo_usuario_nombre .
                                    ' |ESTADO: ' . $objC->estado_id . '-' . $objC->estado->estado_nombre;
                            $log = new LogUsuarios();
                            $log->registro_anterior = $var;
                            $log->registro_nuevo = $var2;
                            $log->id_tabla = $obj->usuario_id;
                            $log->tipo_accion = 'A';
                            $log->save();
                    });
                    Session::flash('message', 'USUARIO: ' . $request->usuario_username . ' ha sido actualizado con éxito.');
                } else {
                    Session::flash('messageError', "No se ha encontrado el usuario a actualizar.");
                }
            } else {
                Session::flash('messageError', "No se ha seleccionado usuario.");
            }
            return redirect()->back();
        } catch (\Exception $ex) {
            \DB::rollback();
            Log::error('CONTROLLER Usuarios/actualizarUsuario'. $ex->getMessage());
            Session::flash('messageError', $ex->getMessage());
            return redirect()->back();
        }
    }
}
