<?php
/*
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);*/

/**
 * funcion que permite acceder por medio de js a mis servicios
 */
function cors() {

    // Allow from any origin
    if (isset($_SERVER['HTTP_ORIGIN'])) {
        // Decide if the origin in $_SERVER['HTTP_ORIGIN'] is one
        // you want to allow, and if so:
        header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
        header('Access-Control-Allow-Credentials: true');
        header('Access-Control-Max-Age: 86400');    // cache for 1 day
    }

    // Access-Control headers are received during OPTIONS requests
    if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {

        if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))
            // may also be using PUT, PATCH, HEAD etc
            header("Access-Control-Allow-Methods: GET, POST, OPTIONS");         

        if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
            header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");

        exit(0);
    }
}

class Constantes {
    public static $ERROR_SIN_DATOS = 1;
    public static $ERROR_CONEXION = 2;
    public static $ERROR_DATOS_INCOMPLETOS = 3;
    public static $ERROR_GUARDAR = 4;
    public static $ERROR_CONTROLADO = 5;
    public static $ERROR_SIN_NOMBRE_METODO = 6;
    public static $ERROR_SIN_PARAMETROS = 7;
    public static $ERROR_ACTUALIZAR = 8;
    
    /** No se han encontrado datos */
    public static $E_MSJ_1 = "No se han encontrado datos ";
    /** Error de conexion */
    public static $E_MSJ_2 = "Error de conexion ";
    /** Error no controlado */
    public static $E_MSJ_3 = "Error no controlado ";
    /** Datos incompletos */
    public static $E_MSJ_4 = "Datos incompletos ";
    /** Error al guardar Datos */
    public static $E_MSJ_5 = "Error al guardar Datos ";
    /** Mensaje Personalizado */
    public static $E_MSJ_6 = "Error controlado, se envía el siguiente mensaje: ";
    /** No ha enviado el nombre del método */
    public static $E_MSJ_7 = "No ha enviado el nombre del método";
    /** Debe enviar los parámetros necesarios */
    public static $E_MSJ_8 = "Debe enviar los parámetros necesarios";
    
    /** Sesion caducada*/
    public static $SESION_CADUCADA = "Sesion caducada";
    
    public static $M_ERROR_ACTUALIZAR = "Ha ocurrido un error al actualizar los datos";
    /** Tiene que enviar las credenciales del usuario*/
    public static $E_MSJ_22 = "Tiene que enviar las credenciales del usuario";
    
    //Estado Premios
    public static $ESTADO_PENDIENTE_RETIRO = 3;
    public static $ESTADO_RETIRADO = 4;
    
    //Estado punto_usuarios
    public static $ESTADO_DEBITO_CANJE_AUTOMATICO = 5;
    public static $ESTADO_CREDITO_CANJE_AUTOMATICO = 6;
    public static $ESTADO_PREMIO_REDIMIDO = 8;
    public static $ESTADO_PREMIO_REDIMIDO_PROMOCION = 13;
    public static $ESTADO_SUBIR_NIVEL_REDIMIR = 14;
    
    public static $ESTADO_PENDIENTE_RECLAMAR_EVENTO = 15;
    public static $ESTADO_NO_LEIDO = 16;
    
    public static $ESTADO_ACREDITADO_EVENTO = 12;
    public static $ESTADO_DEVOLUCION_PUNTOS = 19;
    
    //Estado eventos
    public static $ESTADO_REALIZADO = 9;
    public static $ESTADO_NO_REALIZADO = 10;
    
    public static $EVENTO_TURNO_ADICIONAL = 1;
    public static $EVENTO_PUNTOS_ADICIONAL = 2;
    
    public static $PLANTILLA_REGISTRO = 1;
    public static $PLANTILLA_PREMIO = 2;
    public static $PLANTILLA_CAMBIO_PASSWORD = 3;
    
    public static $URL_REPOSITORIO_FOTO = '/util/';
    public static $URL_BASE = '/proyectosinnova/wsjuegosextra';
 
    public static $IMPRESORA_ACTIVA = 'IMPRESORA_ACTIVA';
}

/**
 * Notifica si un valor está vacío
 * @param type $valor Valor a verificar
 * @return type
 */
function vacio($valor){
    return (!isset($valor) || trim($valor)==='');
}

/**
 * Muestra los mensajes de error
 * 1. No se han encontrado datos
 * 2. Error de conexion
 * 3. Error no controlado
 * 4. Datos incompletos
 * 5. Error al guardar Datos
 * 6. Mensaje Personalizado
 * @param type $codigo Codigo del error
 * @param type $mensaje Mensaje a mostrar
 */
function errorMensaje($codigo,$mensaje){
    $error = NULL;
    $mensajeTecnico = NULL;
    switch ($codigo) {
        case Constantes::$ERROR_SIN_DATOS:
            $error = Constantes::$E_MSJ_1;
            $mensajeTecnico = Constantes::$E_MSJ_1;
            break;
        case Constantes::$ERROR_CONEXION:
            $error = Constantes::$E_MSJ_2;
            $mensajeTecnico = Constantes::$E_MSJ_2.$mensaje;
            break;
        case Constantes::$ERROR_DATOS_INCOMPLETOS:
            $error = Constantes::$E_MSJ_4;
            $mensajeTecnico = Constantes::$E_MSJ_4.$mensaje;
            break;
        case Constantes::$ERROR_GUARDAR:
            $error = Constantes::$E_MSJ_5;
            $mensajeTecnico = Constantes::$E_MSJ_5.$mensaje;
            break;
        case Constantes::$ERROR_CONTROLADO:
            $error = $mensaje;
            $mensajeTecnico = Constantes::$E_MSJ_6.$mensaje;
            break;
        case Constantes::$ERROR_SIN_NOMBRE_METODO:
            $error = Constantes::$E_MSJ_7;
            $mensajeTecnico = Constantes::$E_MSJ_7.$mensaje;
            break;
        case Constantes::$ERROR_SIN_PARAMETROS:
            $error = Constantes::$E_MSJ_8;
            $mensajeTecnico = Constantes::$E_MSJ_8.$mensaje;
            break;
        case Constantes::$ERROR_ACTUALIZAR:
            $error = Constantes::$M_ERROR_ACTUALIZAR;
            $mensajeTecnico = Constantes::$M_ERROR_ACTUALIZAR.$mensaje;
            break;
        default:
            $error = Constantes::$E_MSJ_3;
            $mensajeTecnico = Constantes::$E_MSJ_3.$mensaje;
            break;
    }
    header('Content-Type: application/json');
    
    $respuesta = json_encode(
    array(
        "resultado" => "error",
        "numero" => $codigo,
        "lista" => [],
        "texto" =>str_to_utf8($error),
        "mensajeTecnico" => str_to_utf8($mensajeTecnico)
    ));
    echo $respuesta;
    die();
}

/**
 * Devuelve una respuesta estándar para que pueda ser leída por el cliente del servicio
 * Respuesta ok = Éxito. Resputa error = Falla en accion del servicio.
 * @param type $tipoRespuesta ok/error Tipo de respuesta, debe ser minúscula
 * @param type $numero Código de error o suceso
 * @param type $lista Arreglo de los datos que necesite en caso de ser exitosa la consulta del servicio
 * @param type $mensaje Mensaje a mostrar en caso de éxito/error
 */
function respuestaJson($tipoRespuesta,$numero,$lista,$mensaje){
    $respuesta = json_encode(
    array(
        "resultado" => $tipoRespuesta,
        "numero" => $numero,
        "lista" => $lista,
        "texto" =>str_to_utf8($mensaje),
        "mensajeTecnico" => ""
    ));
    echo $respuesta;
    die();
}

function validarCedulaStr($strCedula){
    try{
        validarCedula($strCedula);
    }catch(Exception $e){
        errorMensaje(Constantes::$ERROR_CONTROLADO, $e->getMessage());
    }
}

function enviarMailM($emisor,$mensaje,$asunto,$destinatario,$nombre_destinatario,$cc){

    $cabeceras  = 'MIME-Version: 1.0' . "\r\n";
    $cabeceras .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

    // Cabeceras adicionales
    $cabeceras .= 'To: '.$nombre_destinatario.' <'.$destinatario.'>' . "\r\n";
    $cabeceras .= 'From: '.$asunto.' <'.$emisor.'>' . "\r\n";

    mail($destinatario, $asunto,$mensaje, $cabeceras);

} 

function enviarMail($emisor,$password,$mensaje,$asunto,$destinatario,$nombre_destinatario,$cc,$smtp,$puerto){
    $mail = new PHPMailer();
    $mail->CharSet = "UTF-8"; 
    $mail->SMTPSecure = 'tls';//tls 587, ssl 465
    $mail->Port = $puerto;
    $mail->IsSMTP();  // Especifica si se va a enviar como smtp
    $mail->Host = $smtp;  // smtp del servidor de correo
    $mail->SMTPAuth = true;     //  Especifica que el servidor SMTP autentica
    $mail->Username = $emisor;  // Usuario de servidor de correo de SMTP 
    $mail->Password = $password; // Clave de servidor de correo de SMTP 
    $mail->From = $emisor;
    $mail->FromName = "Premioton Diario Extra";
    $mail->AddAddress($destinatario, str_to_utf8($nombre_destinatario));
    
    $correosCC = explode(';', $cc);
    foreach($correosCC as $email){
       $mail->AddCC($email, "Premioton Diario Extra");
    }
    
    //$mail->addCC($cc, "Premioton Diario Extra");        
    $mail->IsHTML(true); 
    $mail->Subject = str_to_utf8($asunto);
    $mail->Body    = str_to_utf8($mensaje);
    if(!$mail->Send()){
       echo "Message could not be sent. <p>";
       echo "Mailer Error: " . $mail->ErrorInfo;
    }

}

function str_to_utf8($str) { 
    if (mb_detect_encoding($str, 'UTF-8') === false) { 
        $str = utf8_encode($str); 
    }
    return $str;
}

function convertirUsuarioSesion($consulta){
    if(count($consulta)==1){
        for($indice = 0; $indice < count($consulta); $indice++){
            $fila = array("usuarioId"=>$consulta[$indice]['usuario_id'],
                "username"=>$consulta[$indice]['username']);
            return array($fila);
        }
    }
    errorMensaje(Constantes::$ERROR_CONTROLADO, "No hay usuarios ingresados");
}

function convertirInformacionPersonal($consulta){
    if(count($consulta)==1){
        for($indice = 0; $indice < count($consulta); $indice++){
            $fila = array(
                "cedula"=>$consulta[$indice]['cedula'],
                "nombres"=>$consulta[$indice]['nombre'],
                "apellidos"=>$consulta[$indice]['apellido'],
                "telefonoCasa"=>$consulta[$indice]['telefono_casa'],
                "telefonoTrabajo"=>$consulta[$indice]['telefono_trabajo'],
                "celular"=>$consulta[$indice]['celular'],
                "direccionCasa"=>$consulta[$indice]['direccion_casa'],
                "direccionTrabajo"=>$consulta[$indice]['direccion_trabajo'],
                "referenciaCasa"=>$consulta[$indice]['referencia_casa'],
                "referenciaTrabajo"=>$consulta[$indice]['referencia_trabajo'],
                "urlFoto"=>siteURL().$consulta[$indice]['url_foto']
                    );
            return $fila;
        }
    }
    errorMensaje(Constantes::$ERROR_CONTROLADO, "No hay datos personales");
}

function convertirPremio($consulta){
    if(count($consulta)==1){
        for($indice = 0; $indice < count($consulta); $indice++){
            $fila = array(
                "premio"=>$consulta[$indice]['premio_id'],
                "nombre"=>$consulta[$indice]['nombre'],
                "descripcion"=>$consulta[$indice]['descripcion']
            );
            return $fila;
        }
    }
    errorMensaje(Constantes::$ERROR_CONTROLADO, "No hay datos personales");
}

function convertirPremioWs($consulta,$referencia){
    if(count($consulta)==1){
        for($indice = 0; $indice < count($consulta); $indice++){
            $fila = array(
                "categoria"=>$consulta[$indice]['categoria_id'],
                "premio"=>$consulta[$indice]['premio_id'],
                "nombre"=>$consulta[$indice]['nombre'],
                "descripcion"=>$consulta[$indice]['descripcion'],
                "referencia"=>$referencia
            );
            return $fila;
        }
    }
    errorMensaje(Constantes::$ERROR_CONTROLADO, "No hay datos personales");
}

function crearToken($consulta){
    if(count($consulta)==1){
        for($indice = 0; $indice < count($consulta); $indice++){
            $fila = array("usuarioId"=>$consulta[$indice]['usuario_id'],
                "username"=>$consulta[$indice]['usuario_username']);
            $token = Auth::SignIn($fila);
            return array("token"=>$token);
        }
    }
    errorMensaje(Constantes::$ERROR_CONTROLADO, "No hay usuarios ingresados");
}


function validarSesion($token){
    return Auth::GetData($token);
}

function siteURL(){
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
    $domainName = "";//$_SERVER['HTTP_HOST'].'/';
    return $protocol.$domainName;
}

function validarCedula($cedula) {
    // fuerzo parametro de entrada a string
    $cedula = (string) $cedula; // validaciones
    try {
        validarInicial($cedula, '10');
        validarCodigoProvincia(substr($cedula, 0, 2));
        validarTercerDigito($cedula[2], 'cedula');
        algoritmoModulo10(substr($cedula, 0, 9), $cedula[9]);
    } catch (\Exception $e) {
        setError($e->getMessage());
        return false;
    }
    return true;
}

function validarInicial($numero, $caracteres) {
    if (empty($numero)) {
        throw new \Exception('Valor no puede estar vacio');
    }

    if (!ctype_digit($numero)) {
        throw new \Exception('Valor ingresado solo puede tener dígitos');
    }

    if (strlen($numero) != $caracteres) {
        throw new \Exception('Valor ingresado debe tener ' . $caracteres . ' caracteres');
    }

    return true;
}

function validarCodigoProvincia($numero) {
    if ($numero < 0 OR $numero > 24) {
        throw new \Exception('Codigo de Provincia (dos primeros dígitos) no deben ser mayor a 24 ni menores a 0');
    }

    return true;
}

function validarTercerDigito($numero, $tipo) {
    switch ($tipo) {
        case 'cedula':
            case 'ruc_natural':
                if ($numero < 0 OR $numero > 5) {
                    throw new \Exception('Tercer dígito debe ser mayor o igual a 0 y menor a 6 para cédulas y RUC de persona natural');
                }
                break;
            case 'ruc_privada':
                if ($numero != 9) {
                    throw new \Exception('Tercer dígito debe ser igual a 9 para sociedades privadas');
                }
                break;
            case 'ruc_publica':
                if ($numero != 6) {
                    throw new \Exception('Tercer dígito debe ser igual a 6 para sociedades públicas');
                }
                break;
        default:
            throw new \Exception('Tipo de Identificación no existe.');
            break;
    }
    return true;
}

function algoritmoModulo10($digitosIniciales, $digitoVerificador) {
    $arrayCoeficientes = array(2, 1, 2, 1, 2, 1, 2, 1, 2);

    $digitoVerificador = (int) $digitoVerificador;
    $digitosIniciales = str_split($digitosIniciales);

    $total = 0;
    foreach ($digitosIniciales as $key => $value) {

        $valorPosicion = ( (int) $value * $arrayCoeficientes[$key] );

        if ($valorPosicion >= 10) {
            $valorPosicion = str_split($valorPosicion);
            $valorPosicion = array_sum($valorPosicion);
            $valorPosicion = (int) $valorPosicion;
        }

        $total = $total + $valorPosicion;
    }

    $residuo = $total % 10;

    if ($residuo == 0) {
        $resultado = 0;
    } else {
        $resultado = 10 - $residuo;
    }

    if ($resultado != $digitoVerificador) {
        throw new \Exception('Dígitos iniciales no validan contra Dígito Idenficador');
    }

    return true;
}

function setError($newError) {
    errorMensaje(Constantes::$ERROR_CONTROLADO, $newError);
}

function generateRandomString($length = 10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}