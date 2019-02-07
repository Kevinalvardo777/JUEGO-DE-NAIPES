<?php



    include './util/util.php';
    include './base/base.php';
    include './base/utilbase.php';
    include './seguridad/acceso.php';
    include './PHPMailer/class.phpmailer.php';
    include './PHPMailer/class.smtp.php';
    include './seguridad/Auth.php';
    include './seguridad/JWT.php';
    include './seguridad/BeforeValidException.php';
    include './seguridad/ExpiredException.php';
    include './seguridad/SignatureInvalidException.php';

    

    /*
estructura json post a analizar
{
    "metodo": "nombreMetodoAEjecutar",
    "parametros": [{
            "nombre": "nombreParametro1",
            "valor": "valorParametro1"
    }, {
            "nombre": "nombreParametro2",
            "valor": "valorParametro2"
    }, {
            "nombre": "nombreParametron",
            "valor": "valorParametron"
    }]
}
*/
try{
    cors();
    $datos = json_decode(file_get_contents('php://input'));
    if(!isset($datos->metodo)){
        errorMensaje(Constantes::$ERROR_SIN_NOMBRE_METODO, "");
    }
    $metodo = $datos->metodo;
    header('Content-Type: application/json');
    if($metodo=="iniciarSesion") {
        $username = validarParametro("username","Tiene que ingresar el usuario",$datos,TRUE);
        $password = validarParametro("password","Tiene que ingresar el password",$datos,TRUE);
        $usuario = obtenerUsuarioXCredenciales($username,$password);
        if(!isset($usuario)){
            errorMensaje(Constantes::$ERROR_CONTROLADO, "Las credenciales ingresadas son incorrectas");
        } else if($usuario[0]['estado_id']==2){
            errorMensaje(Constantes::$ERROR_CONTROLADO, "El usuario no se encuentra activo, por favor comunicarse con Diario Extra para este inconveniente");
        }
        $token = crearToken($usuario);
        respuestaJson("ok", 1, $token, "Ha iniciado sesion correctamente");
        
    } else if($metodo=="obtenerEventos") {
        $token = validarParametro("token","Tiene que ingresar el token",$datos,TRUE);
        $usuario = validarSesion($token);
        if(!isset($usuario)){
            errorMensaje(Constantes::$ERROR_CONTROLADO, "Ha caducado su sesion, por favor inicie de nuevo");
        } else {
            $lstEventos = obtenerEvento();
            respuestaJson("ok", 1, $lstEventos, "Se obtuvieron los eventos correctamente");
        }
        
    } else if($metodo=="obtenerInformacionPrincipal") {
        $token = validarParametro("token","Tiene que ingresar el token",$datos,TRUE);
        $evento = validarParametro("evento","No hay evento seleccionado",$datos,TRUE);
        $cicloEvento = validarParametro("cicloEvento","No hay cicloEvento seleccionado",$datos,TRUE);
        $tipo_juego = validarParametro("tipoJuego","No hay tipo de juego seleccionado",$datos,TRUE);
        $usuario = validarSesion($token);
        if(!isset($usuario)){
            errorMensaje(Constantes::$ERROR_CONTROLADO, "Ha caducado su sesion, por favor inicie de nuevo");
        } else {
            $lstInformacionEvento = obtenerInformacionPrincipal($evento, $tipo_juego);
            $lstPremios = obtenerPremiosWs($cicloEvento);
            $lista = array("informacionEvento"=>$lstInformacionEvento,"premios"=>$lstPremios);
            respuestaJson("ok", 1, $lista, "Se obtuvieron datos principales correctamente");
        }
        
    } else if($metodo=="jugar") {
        $token = validarParametro("token","Tiene que ingresar el token",$datos,TRUE);
        $evento = validarParametro("evento","No hay evento seleccionado",$datos,TRUE);
        $cicloEvento = validarParametro("cicloEvento","No hay cicloEvento seleccionado",$datos,TRUE);
         $lista = NULL;
        /*$usuario = NULL;
        $usuario_id = NULL;
        $edicion = validarParametro("edicion","Tiene que ingresar la edicion del diario",$datos,TRUE);
        $fecha = validarParametro("fecha","Tiene que ingresar la fecha del diario",$datos,TRUE);
        $noDiario = validarParametro("noDiario","Tiene que ingresar el número de diario",$datos,TRUE);
        $palabra_clave = validarParametro("palabraClave","Tiene que ingresar la palabra clave del diario",$datos,TRUE);

        $ganador = NULL;
        $lista = NULL;
        if($token=="TOKEN_DEMO_JUEGO"){
            $usuario_id = 12;
            if($noDiario%2==0){
               $premios = obtenerPremiosWs();//Obtiene los premios para presentar
               $ganador = array_rand($premios, 1); 
               $lista = array("premios"=>$premios,"ganador"=>$ganador);
            }
        } else {
            $usuario = validarSesion($token);
            if(!isset($usuario)){
                errorMensaje(Constantes::$ERROR_CONTROLADO, "Ha caducado su sesion, por favor inicie de nuevo");
            } else {*/
         
//                $usuario_id = $usuario->usuarioId;
                $lista = obtenerGanadorProcedure($evento,$cicloEvento);
            /*}
        }*/
       
        respuestaJson("ok", 1, $lista, "Juego realizado");
    }else if($metodo=="impresora"){
        $valor= hasImpresora();
        
        respuestaJson("ok", 1,$valor[0]['parametro_general_valor'] , "Impresora");
    }else if($metodo=="terminarJuego"){
    
        $token = validarParametro("token","Tiene que ingresar el token",$datos,TRUE);
        /*$usuario = validarSesion($token);
        if(!isset($usuario)){
            errorMensaje(Constantes::$ERROR_CONTROLADO, "Ha caducado su sesion, por favor inicie de nuevo");
        }*/
        
        $usuario_id = NULL;
        if($token=="TOKEN_DEMO_JUEGO") {
            $usuario_id = 12;
        } else {
            $usuario = validarSesion($token);
            if(!isset($usuario)){
                errorMensaje(Constantes::$ERROR_CONTROLADO, "Ha caducado su sesion, por favor inicie de nuevo");
            } else {
                $usuario_id = $usuario->usuarioId;
            }
        }

        $premioUsuarios = obtenerPremioUsuarioPendienteXUsuarioId($usuario_id);
        if(isset($premioUsuarios)){
            actualizarEstadoPremioUsuario($premioUsuarios[0]['premio_usuario_id']);
            $premio = obtenerPremioGanadoXId($premioUsuarios[0]['premio_id']);
            $lista = array("premio"=>$premio[0]);
            respuestaJson("ok", 1, $lista, "Premios ganado");    
        } else {
            errorMensaje(Constantes::$ERROR_CONTROLADO, "No tiene premios disponibles para reclamar");
        }
    } else if($metodo=="premioReclamado"){
        respuestaJson("ok", 1, $lista, "Redireccionar a pagina principal");
    } else if($metodo=="validar"){
        $token = validarParametro("token","Tiene que ingresar el token",$datos,TRUE);
        $usuarios = validarSesion($token);
    } else {
        header('WWW-Authenticate: Basic realm="Servicios"');
        header('HTTP/1.0 401 No autorizado');
        die ("No tiene acceso a la url especificada");
    } 
}catch(ExpiredException $e1){
    errorMensaje(Constantes::$ERROR_CONTROLADO, $SESION_CADUCADA);
}catch(\Exception $e){
    header('WWW-Authenticate: Basic realm="Servicios"');
    header('HTTP/1.0 401 No autorizado');
    die ("No tiene acceso a la url especificada");
}

/**
 * Realiza la validación de un parametro enviado por request de JSON, se envia el id del parÃ¡metro a buscar
 * @param type $nombre
 * @param type $mensaje
 * @param type $parametros
 * @param type $obligatorio
 * @return type
 */
function validarParametro($nombre,$mensaje,$parametros,$obligatorio){
    $parametro = $parametros->$nombre;
    if(isset($parametro)&&!vacio($parametro)){
        return trim($parametro);
    } else{
        if($obligatorio){
            errorMensaje(Constantes::$ERROR_CONTROLADO, $mensaje);
        }
    }
}