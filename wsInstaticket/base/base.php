<?php

/* ini_set('display_errors', 1);
  ini_set('display_startup_errors', 1);
  error_reporting(E_ALL); */

/**
 * Clase que contiene todas las consultas hacia la base de datos, interactúa directamente con la clase de Servicios.php
 * 
 */
function obtenerUsuarioXCredenciales($username, $password) {
    $passwordHas = sha1($password);
    $consulta = "select * from usuario where usuario_username = ? and usuario_password = ? and tipo_usuario_id = 3 ";
    return realizarConsultaParametros($consulta, array($username, $passwordHas));
}

function obtenerPlantillaCorreoXNombre($nombre, $estado_id) {
    $consulta = "select * from plantilla_correos where nombre = ? and estado_id = ?";
    return realizarConsultaParametros($consulta, array($nombre, $estado_id));
}

function obtenerUsuarioXUsername($username) {
    $consulta = "select * from usuarios where username = ?";
    return realizarConsultaParametros($consulta, array($username));
}

function obtenerInformacionPersonalXUsuarioId($usuario_id) {
    $consulta = "select * from informacion_usuarios where usuario_id = ?";
    return realizarConsultaParametros($consulta, array($usuario_id));
}

function obtenerParametroGeneral($nombre) {
    $consulta = "select * from parametros where nombre = ?";
    return realizarConsultaParametros($consulta, array($nombre));
}

function actualizarTokenUsuario($usuario_id, $token) {
    $update = "update usuarios set remember_token = ? where usuario_id = ? ";
    realizarActualizacionParametros($update, array($token, $usuario_id));
}

function cambiarContrasenia($usuario_id, $token, $password) {
    $update = "update usuarios set remember_token = ?, password = ? where usuario_id = ? ";
    realizarActualizacionParametros($update, array($token, $password, $usuario_id));
}

function obtenerCantidadPremioUsuarioXUsuarioId($usuario_id) {
    $consulta = "SELECT SUM(IF(premio_usuarios.estado_id is not null,1,0)) v 
        FROM premio_usuarios where premio_usuarios.usuario_id = ?";
    return realizarConsultaParametros($consulta, array($usuario_id));
}

function obtenerCantidadTirajeUsuarioXUsuarioId($usuario_id) {
    $consulta = "SELECT SUM(IF(tiraje_usuario.estado_id is not null,1,0)) v 
        FROM tiraje_usuario where tiraje_usuario.usuario_id = ?";
    return realizarConsultaParametros($consulta, array($usuario_id));
}

function obtenerInformacionPersonalXCedula($cedula) {
    $consulta = "select * from informacion_usuarios where cedula = ?";
    return realizarConsultaParametros($consulta, array($cedula));
}

function obtenerPremioXId($premioId) {
    $consulta = "select * from premios where premio_id = ?";
    return realizarConsultaParametros($consulta, array($premioId));
}

function obtenerPremioGanadoXId($premioId) {
    $consulta = "select premios.nombre nombre, lado_dado.url_lado_dado color, premios.url_imagen fotoPremio from premios, lado_dado "
            . "where lado_dado.categoria_id = premios.categoria_id and premio_id =?";
    return realizarConsultaParametros($consulta, array($premioId));
}

function obtenerCategoriaXId($categoriaId) {
    $consulta = "select * from categorias where categoria_id = ?";
    return realizarConsultaParametros($consulta, array($categoriaId));
}

function obtenerParametroXNombre($nombre) {
    $consulta = "select * from parametro where nombre = ? and estado_id = 1";
    return realizarConsultaParametros($consulta, array($nombre));
}

function obtenerIncentivoPremiosXId($incentivo_premio_id) {
    $consulta = "select * incentivo_premios where incentivo_premio_id = ? and estado_id = 1";
    return realizarConsultaParametros($consulta, array($incentivo_premio_id));
}

function obtenerTodosPremiosWs() {
    $consultaPremios = "select premios.categoria_id as categoria, url_imagen fotoPremio, 
        premios.premio_id premio, premios.nombre, premios.descripcion from ciclos, premios, ciclo_premios
	where curdate() between fecha_inicio and fecha_fin 
        and ciclos.ciclo_id = ciclo_premios.ciclo_id
        and ciclo_premios.premio_id = premios.premio_id
        and ciclos.estado_id = 1
        and ciclo_premios.estado_id = 1
        and premios.estado_id = 1 
        and ciclo_premios.stock_disponible >= ciclo_premios.stock_virtual
        order by premios.categoria_id";
    return realizarConsulta($consultaPremios);
}

function obtenerPremiosPosteriores() {
    $consultaPremiosPosteriores = "select premios.categoria_id as categoria, url_imagen fotoPremio, 
        premios.premio_id premio, premios.nombre, premios.descripcion from premios, ciclo_premios, 
        (select ciclo_id from ciclos where curdate() < ciclos.fecha_inicio and ciclos.estado_id = 1
        order by ciclos.ciclo_id asc limit 1) ciclo_posterior
        where ciclo_posterior.ciclo_id and ciclo_premios.ciclo_id
        and ciclo_premios.premio_id = premios.premio_id
        and ciclo_premios.estado_id = 1
        and premios.estado_id = 1 
        and ciclo_premios.stock_disponible >= ciclo_premios.stock_virtual
        order by premios.categoria_id";
    return realizarConsulta($consultaPremiosPosteriores);
}

function obtenerHistorialUsuario($usuario_id) {
    $consulta = "select * from (select DATE_FORMAT(tiraje_usuario.fecha_auditoria,'%W, %d de %M') dia, 
        DATE_FORMAT(tiraje_usuario.fecha_auditoria,'%H:%i') hora,
        tiraje_usuario.edicion, tiraje_usuario.ejemplar, 
        premios.nombre premio, premios.url_imagen fotoPremio, estados.nombre, tiraje_usuario.fecha_auditoria
        from premio_usuarios, premios, estados, tiraje_usuario
        where premio_usuarios.premio_id = premios.premio_id
        and premio_usuarios.estado_id = estados.estado_id
        and premio_usuarios.tiraje_usuario_id = tiraje_usuario.tiraje_usuario_id
        and premio_usuarios.usuario_id = ?
        union
        select DATE_FORMAT(tiraje_usuario.fecha_auditoria,'%W, %d de %M'), 
        DATE_FORMAT(tiraje_usuario.fecha_auditoria,'%H:%i'),
        tiraje_usuario.edicion, tiraje_usuario.ejemplar, 
        null, null, null, tiraje_usuario.fecha_auditoria
        from tiraje_usuario
        where tiraje_usuario.usuario_id = ?
        and not exists (select 1 from premio_usuarios where tiraje_usuario.tiraje_usuario_id = premio_usuarios.tiraje_usuario_id and premio_usuarios.usuario_id = ?)) v1
        order by fecha_auditoria desc";
    return realizarConsultaParametros($consulta, array($usuario_id, $usuario_id, $usuario_id));
}

function crearUsuario($username, $password, $nombres, $apellidos, $cedula, $direccionCasa, $urlFoto, $celular) {

    $usuario = obtenerUsuarioXUsername($username);
    $inforPersonal = obtenerInformacionPersonalXCedula($cedula);
    if (isset($usuario)) {
        errorMensaje(Constantes::$ERROR_CONTROLADO, "El usuario " . $username . " ya existe en nuestros registros");
    } else if (isset($inforPersonal)) {
        errorMensaje(Constantes::$ERROR_CONTROLADO, "El usuario con la cedula " . $cedula . " ya existe en nuestros registros");
    }

//Inserta Usuario
    $passwordSha1 = sha1($password);
    $insertUsuario = "insert into usuarios (`username`,`password`,`estado_id`,`tipo_usuario_id`) values (?,?,?,?)";
    realizarInsercionParametros($insertUsuario, array($username, $passwordSha1, 1, 1));

    $usuarioCreado = obtenerUsuarioXUsername($username);
//Inserta Informacion Personal
    $insertInformacionPersonal = "insert into informacion_usuarios "
            . "(`usuario_id`,`cedula`,`nombre`,`apellido`,`direccion_casa`,"
            . "`usuario_ingresa_id`,`estado_id`,`url_foto`,`celular`) values (?,?,?,?,?,?,?,?,?)";
    realizarInsercionParametros($insertInformacionPersonal, array($usuarioCreado[0]['usuario_id'], $cedula, $nombres, $apellidos, $direccionCasa, $usuarioCreado[0]['usuario_id'], 1, $urlFoto, $celular));

    $inforPersonalCreada = obtenerInformacionPersonalXCedula($cedula);

    $lista = array("usuario" => convertirUsuarioSesion($usuarioCreado), "informacionUsuarios" => $inforPersonalCreada);
    return $lista;
}

function editarUsuario($nombres, $apellidos, $cedula, $direccionCasa, $urlFoto, $usuarioToken, $celular) {
    $usuario = obtenerUsuarioXUsername($usuarioToken->username);
    $infoUsuarios = obtenerInformacionPersonalXCedula($cedula);

    $updateInfoUsuarios = "update informacion_usuarios set ";
    $coma = "";
    $arrayParametros = array();
    if ($nombres != $infoUsuarios[0]['nombre']) {
        $updateInfoUsuarios .= $coma . "nombre = ?";
        array_push($arrayParametros, $nombres);
        $coma = ",";
    }
    if ($apellidos != $infoUsuarios[0]['apellido']) {
        $updateInfoUsuarios .= $coma . "apellido = ?";
        array_push($arrayParametros, $apellidos);
        $coma = ",";
    }
    if ($cedula != $infoUsuarios[0]['cedula']) {
        $updateInfoUsuarios .= $coma . "cedula = ?";
        array_push($arrayParametros, $cedula);
        $coma = ",";
    }
    if ($direccionCasa != $infoUsuarios[0]['direccion_casa']) {
        $updateInfoUsuarios .= $coma . "direccion_casa = ?";
        array_push($arrayParametros, $direccionCasa);
        $coma = ",";
    }
    if ($celular != $infoUsuarios[0]['celular']) {
        $updateInfoUsuarios .= $coma . "celular = ?";
        array_push($arrayParametros, $celular);
        $coma = ",";
    }

    if (strlen($urlFoto) > 0 && $urlFoto != $infoUsuarios[0]['url_foto']) {
        $updateInfoUsuarios .= $coma . "url_foto = ?";
        array_push($arrayParametros, $urlFoto);
        $coma = ",";
    }

    if (strlen($coma) > 0) {
//Se actualiza por lo menos un valor de la información personal
        $updateInfoUsuarios .= " where informacion_usuario_id = ?";
        array_push($arrayParametros, $infoUsuarios[0]['informacion_usuario_id']);
        realizarActualizacionParametros($updateInfoUsuarios, $arrayParametros);
    }

    $usuarioAct = obtenerUsuarioXUsername($usuario[0]['username']);
    $inforPersonalAct = obtenerInformacionPersonalXCedula($cedula);
    $lista = array("usuario" => convertirUsuarioSesion($usuarioAct), "informacionUsuarios" => $inforPersonalAct);
    return $lista;
}

function actualizarDatosCanje($usuarioId, $nombres, $celular, $direccionCasa, $referencia, $telefono) {

    $infoUsuarios = obtenerInformacionPersonalXUsuarioId($usuarioId);

//Editar el usuario
    $updateInfoUsuarios = "update informacion_usuarios set ";
    $coma = "";
    $arrayParametros = array();

    if ($nombres != $infoUsuarios[0]['nombre']) {
        $updateInfoUsuarios .= $coma . "nombre = ?";
        array_push($arrayParametros, $nombres);
        $coma = ",";
    }
    if ($celular != $infoUsuarios[0]['celular']) {
        $updateInfoUsuarios .= $coma . "celular = ?";
        array_push($arrayParametros, $celular);
        $coma = ",";
    }
    if ($direccionCasa != $infoUsuarios[0]['direccion_casa']) {
        $updateInfoUsuarios .= $coma . "direccion_casa = ?";
        array_push($arrayParametros, $direccionCasa);
        $coma = ",";
    }
    if ($referencia != $infoUsuarios[0]['referencia_casa']) {
        $updateInfoUsuarios .= $coma . "referencia_casa = ?";
        array_push($arrayParametros, $referencia);
        $coma = ",";
    }

    if ($telefono != $infoUsuarios[0]['telefono_casa']) {
        $updateInfoUsuarios .= $coma . "telefono_casa = ?";
        array_push($arrayParametros, $telefono);
        $coma = ",";
    }

    if (strlen($coma) > 0) {
//Se actualiza por lo menos un valor de la información personal
        $updateInfoUsuarios .= " where informacion_usuario_id = ?";
        array_push($arrayParametros, $infoUsuarios[0]['informacion_usuario_id']);
        realizarActualizacionParametros($updateInfoUsuarios, $arrayParametros);
    }

    $inforPersonalAct = obtenerInformacionPersonalXUsuarioId($usuarioId);
    $lista = array("informacionUsuarios" => $inforPersonalAct);
    return $lista;
}

function obtenerLadoDadoWs() {
    $consulta = "select color, hexadecimal from lado_dado where estado_id = 1";
    return realizarConsulta($consulta);
}

function obtenerCicloPremioXPremioIdYFecha($premio_id, $fecha_publicacion) {
    $consulta = "SELECT * FROM ec_com_extra_juegos.ciclo_premios, ciclos 
        where ciclos.ciclo_id = ciclo_premios.ciclo_id
        and ? between ciclos.fecha_inicio and ciclos.fecha_fin
        and ciclos.estado_id = 1
        and ciclo_premios.estado_id = 1
        and ciclo_premios.premio_id = ? ";
    return realizarConsultaParametros($consulta, array($fecha_publicacion, $premio_id));
}

function insertarTirajeUsuario($tiraje_diario_id, $usuario_id, $edicion, $ejemplar, $fecha_publicacion, $estado_id) {
    $insertTirajeUsuario = "insert into tiraje_usuario (`tiraje_diario_id`,`usuario_id`,`edicion`,`ejemplar`,`fecha_publicacion`,`estado_id`) values (?,?,?,?,?,?)";
    $paramInsertTirajeUsuario = array($tiraje_diario_id, $usuario_id, $edicion, $ejemplar, $fecha_publicacion, $estado_id);
    realizarInsercionParametros($insertTirajeUsuario, $paramInsertTirajeUsuario);
}

function obtenerGanadorProcedure($evento, $cicloEvento) {

    $lstGanador = realizarConsultaParametros("call obtener_ganador(?,?)", array($evento, $cicloEvento));
    if (isset($lstGanador)) {
        // if (count($lstGanador) == 1) {
        if (count($lstGanador) < 1) {
            errorMensaje(Constantes::$ERROR_CONTROLADO, $lstGanador[0]['msj']);
        } else {
            $premios = array();
            $ganador = $lstGanador[0]['V_ID_PREMIOS_C'];
            $idRegistoGanador = $lstGanador[0]['ID_V_GANADOR'];
            $premiosanador = array();
//            if (isset($ganador)){
//                $ganador = $ganador -1;
//            }
            for ($indice = 0; $indice < count($lstGanador); $indice++) {
                if ($ganador == $lstGanador[$indice]['premio']) {
                    $fila = array(
                        "categoria" => $lstGanador[$indice]['categoria'],
                        "fotoPremio" => $lstGanador[$indice]['fotoPremio'],
                        "premio" => $lstGanador[$indice]['premio'],
                        "nombre" => $lstGanador[$indice]['nombre'],
                        "descripcion" => $lstGanador[$indice]['descripcion']
                    );
                    $premiosanador[0] = $fila;
                } else {
                    $fila = array(
                        "categoria" => $lstGanador[$indice]['categoria'],
                        "fotoPremio" => $lstGanador[$indice]['fotoPremio'],
                        "premio" => $lstGanador[$indice]['premio'],
                        "nombre" => $lstGanador[$indice]['nombre'],
                        "descripcion" => $lstGanador[$indice]['descripcion']
                    );
                    $premios[$indice] = $fila;
                }
//array_push($fila, $premios);
            }
            $codigo = null;
            if (count($premiosanador)) {
                do {
                    $query = "select * from premio_participante where codigo_premio=?";
                    $codigo = generarCodigo(6);
                    $lstGanador = realizarConsultaParametros($query, array($codigo));
                } while (count($lstGanador) > 0);
                $update = " update premio_participante set codigo_premio = ? where premio_participante_id=?";
                realizarActualizacionParametros($update, array($codigo, $idRegistoGanador));
            }
           // $imgCodigo ="";
            if ($codigo != null) {
                $imgCodigo = bar($codigo);
            } else {
                $imgCodigo = bar('000000');
            }

            $lista = array("premios" => $premios, "ganador" => $ganador, "premioGanador" => $premiosanador, "codigoPremio" => $codigo, "imgCode" => $imgCodigo);
            return $lista;
        }
    }
}

function obtenerGanador($premios, $usuario_id, $fecha_publicacion, $edicion, $ejemplar, $palabra_clave) {
    $consultaProbabilidad = "SELECT * FROM ec_com_extra_juegos.probabilidades 
        where ( (hora_inicio is null and hora_fin is null) or (curtime()between hora_inicio and hora_fin)) and estado_id = 1";
    $tiraje = obtenerTirajeDiarioXFechaYEdicion($fecha_publicacion, $edicion, $palabra_clave);
    if (!isset($tiraje)) {
        errorMensaje(Constantes::$ERROR_CONTROLADO, "El número de diario en la fecha ingresada no está impreso");
    }
    $tirajeUsuarioCreado = NULL;
    $tirajeUsuario = obtenerTirajeUsuario($fecha_publicacion, $edicion, $ejemplar, $usuario_id, $tiraje);
    if (!isset($tirajeUsuario)) {
        insertarTirajeUsuario($tiraje[0]['tiraje_diario_id'], $usuario_id, $edicion, $ejemplar, $fecha_publicacion, 1);
        $tirajeUsuarioCreado = obtenerTirajeUsuario($fecha_publicacion, $edicion, $ejemplar, $usuario_id, $tiraje);
    } else {
        errorMensaje(Constantes::$ERROR_CONTROLADO, "Ya has participado con este número de diario");
    }

    $probabilidad = realizarConsulta($consultaProbabilidad);
    if (isset($probabilidad)) {
        $prob = $probabilidad[0]['probabilidad'];
        if (obtenerProbabilidad($prob)) {
            if (!isset($premios)) {
                errorMensaje(Constantes::$ERROR_CONTROLADO, "No hay premios disponibles");
            }
            $lstProb = array();
            for ($index = 0; $index < count($premios); $index++) {
                $categoriaId = ($premios[$index]['categoria']);
                $categoria = obtenerCategoriaXId($categoriaId);
                if (isset($premios[$index]['inc'])) {
                    $incentivo = obtenerIncentivoPremiosXId($lstProb[$premios[$index]['inc']]);
                    $lstProb[$premios[$index]['premio']] = $incentivo[0]['porcentaje'];
                } else {
                    $lstProb[$premios[$index]['premio']] = $categoria[0]['probabilidad'];
                }
            }
            $idGanador = obtenerProbabilidadLista($lstProb);
            $premioGanador = obtenerPremioXId($idGanador);
            if (count($premioGanador) > 0) {
                $cicloPremio = obtenerCicloPremioXPremioIdYFecha($idGanador, $fecha_publicacion);
                insertarPremioUsuario($tirajeUsuarioCreado[0]['tiraje_usuario_id'], $usuario_id, $idGanador, 7, $cicloPremio[0]['ciclo_premio_id']);
                $premioUsuario = obtenerPremioUsuarioPendiente($tirajeUsuarioCreado[0]['tiraje_usuario_id']);
                if (isset($premios[$index]['inc'])) {
                    actualizarStockIcentivoPremioId($cicloPremio[0]['stock_virtual'] - 1, $cicloPremio[0]['ciclo_premio_id']);
                } else {
                    actualizarStockVirtualCicloPremioXCicloPremioId($cicloPremio[0]['stock_virtual'] + 1, $cicloPremio[0]['ciclo_premio_id']);
                }

                return convertirPremioWs($premioGanador, $premioUsuario[0]['premio_usuario_id']);
            } else {
                return NULL;
            }
        }
    }
}

function insertarPremioUsuario($tiraje_usuario_id, $usuario_id, $idGanador, $estado_id, $ciclo_premio_id) {
    $insertPremioUsuario = "insert into premio_usuarios (`tiraje_usuario_id`,`usuario_id`,`premio_id`,`estado_id`,`ciclo_premio_id`) values (?,?,?,?,?)";
    realizarInsercionParametros($insertPremioUsuario, array($tiraje_usuario_id, $usuario_id, $idGanador, $estado_id, $ciclo_premio_id));
}

function obtenerPremioUsuarioPendiente($tirajeUsuarioId) {
    $consultaPremioUsuario = "select * from premio_usuarios where estado_id = 7 and tiraje_usuario_id = ?";
    return realizarConsultaParametros($consultaPremioUsuario, array($tirajeUsuarioId));
}

function obtenerPremioUsuarioPendienteXUsuarioId($usuarioId) {
    $consultaPremioUsuario = "select * from premio_usuarios where estado_id = 7 and usuario_id = ?";
    return realizarConsultaParametros($consultaPremioUsuario, array($usuarioId));
}

function obtenerPremioUsuarioXId($premio_usuario_id) {
    $consultaPremioUsuario = "select * from premio_usuarios where premio_usuario_id = ?";
    return realizarConsultaParametros($consultaPremioUsuario, array($premio_usuario_id));
}

function actualizarEstadoPremioUsuario($premio_usuario_id) {
    $update = "update premio_usuarios set estado_id = 3 where premio_usuario_id = ? and estado_id = 7";
    realizarActualizacionParametros($update, array($premio_usuario_id));
}

function actualizarStockVirtualCicloPremioXCicloPremioId($nuevoStock, $ciclo_premio_id) {
    $update = "update ciclo_premios set stock_virtual = ? where ciclo_premio_id = ?";
    realizarActualizacionParametros($update, array($nuevoStock, $ciclo_premio_id));
}

function actualizarStockIcentivoPremioId($nuevoStock, $ciclo_premio_id) {
    $update = "update incentivo_premios set cantidad = ? where ciclo_premio_id = ?";
    realizarActualizacionParametros($update, array($nuevoStock, $ciclo_premio_id));
}

function obtenerPublicidadXUbicacionId($ubicacionId) {
    $consulta = "select * from publicidad where ubicacion_publicidad_id = ? and estado_id = 1";
    return realizarConsultaParametros($consulta, array($ubicacionId));
}

function obtenerTirajeUsuario($fecha_publicacion, $edicion, $ejemplar, $usuario_id, $tiraje) {
    if (isset($tiraje)) {
        if ($ejemplar <= $tiraje[0]['total_tiraje'] && $ejemplar > 0) {
            $consultaTirajeUsuario = "select * from tiraje_usuario "
                    . " where tiraje_diario_id = ? and edicion = ? and ejemplar = ? and usuario_id = ? and fecha_publicacion = ? and estado_id = 1";
            $tirajeUsuario = realizarConsultaParametros($consultaTirajeUsuario, array($tiraje[0]['tiraje_diario_id'], $edicion, $ejemplar, $usuario_id, $fecha_publicacion));
            return $tirajeUsuario;
        } else {
            errorMensaje(Constantes::$ERROR_CONTROLADO, "El número de diario no está dentro del tiraje impreso");
        }
    } else {
        errorMensaje(Constantes::$ERROR_CONTROLADO, "El tiraje ingresado no existe en nuestros registros");
    }
}

function obtenerTirajeDiarioXFechaYEdicion($fecha, $edicion, $palabra_clave) {
    list($anio, $mes, $dia) = split('[/.-]', $fecha);
    $consultaTiraje = "SELECT * FROM tiraje_diario "
            . " where dia = ? and mes = ? and anio = ? and edicion = ? and palabra_clave = ? and estado_id = 1";
    return realizarConsultaParametros($consultaTiraje, array($dia, $mes, $anio, $edicion, $palabra_clave));
}

function obtenerTirajeDiarioXId($tiraje_diario_id) {
    $consulta = "select * from tiraje_diario where tiraje_diario_id =?";
    return realizarConsultaParametros($consulta, array($tiraje_diario_id));
}

function actualizarStockPremios($premio_id, $stock) {
    $update = "UPDATE `ec_com_comandato`.`premios` SET";
    $update .= "`stock` = ?";
    $update .= " WHERE `premio_id` = ?";
    realizarActualizacionParametros($update, array($stock, $premio_id));
}

function insertarNivelUsuario($usuario_id, $nivel_id) {
    $insertNivel = "insert into nivel_usuarios (`usuario_id`,`nivel_id`) values (?,?)";
    realizarInsercionParametros($insertNivel, array($usuario_id, $nivel_id));
}

function enviarCorreoRegistro($usuario_id) {
    $plantilla = obtenerPlantillaXId(Constantes::$PLANTILLA_REGISTRO);
    if (!isset($plantilla)) {
        errorMensaje(Constantes::$ERROR_CONTROLADO, "No existe plantilla para enviar email");
    }
    $datos = obtenerDatosPersonales($usuario_id);
    if (strpos($plantilla[0]['valor'], "##NOMBRES##") == FALSE) {
        errorMensaje(Constantes::$ERROR_CONTROLADO, "No tiene todos los parámetros necesarios para enviar mail");
    }
    $textoMail = str_replace("##NOMBRES##", $datos[0]['nombre'] . ' ' . $datos[0]['apellido'], $plantilla[0]['valor']);
    $email_emisor = obtenerParametroGeneral("EMAIL_EMISOR");
    $password_emisor = obtenerParametroGeneral("EMAIL_EMISOR_PASSWORD");
    $email_cc = obtenerParametroGeneral("EMAIL_CC");
    $email_smtp = obtenerParametroGeneral("EMAIL_SMTP");
    if (!isset($email_cc)) {
        $email_cc = $email_emisor;
    }
    if (!isset($email_emisor) || vacio($email_emisor[0]['valor'])) {
        errorMensaje(Constantes::$ERROR_DATOS_INCOMPLETOS, "No está parametrizado el email emisor");
    }
    enviarMail($email_emisor[0]['valor'], $password_emisor[0]['valor'], $textoMail, $plantilla[0]['asunto'], $datos[0]['correo_personal'], $datos[0]['nombre'] . $datos[0]['apellido'], $email_cc[0]['valor'], $email_smtp[0]['valor']);
}

/* INICIO Instaticket */

function obtenerEvento() {
    $consulta = "select evento.evento_id, evento.evento_nombre, ciclo.ciclo_id, ciclo.ciclo_fecha_inicio evento_inicio, ciclo.ciclo_fecha_fin evento_fin,evento.evento_url_imagen,ciclo_evento.ciclo_evento_id,ciclo_evento.juego_carta,ciclo_evento.juego_puerta,ciclo_evento.turnos
                from evento, ciclo, ciclo_evento
                where evento.evento_id = ciclo_evento.evento_id and ciclo.ciclo_id = ciclo_evento.ciclo_id
                and curdate() between ciclo.ciclo_fecha_inicio and ciclo.ciclo_fecha_fin";
    return realizarConsulta($consulta);
}

function obtenerInformacionPrincipal($eventoId, $tipo_juego) {
    $consulta = "select configuracion_evento.configuracion_evento_id, configuracion_evento.evento_id, 
		tipo_url.tipo_url_id, tipo_url.tipo_url_nombre,
                concat( 'recurso_juego/','',configuracion_evento.configuracion_evento_url ) as url
                from configuracion_evento, tipo_url 
                where configuracion_evento.tipo_url_id = tipo_url.tipo_url_id
                and configuracion_evento.evento_id = ? and configuracion_evento.tipo_juego_id=?";
    return realizarConsultaParametros($consulta, array($eventoId, $tipo_juego));
}

function obtenerPremiosWs($eventoId) {
//    $lstPremios = realizarConsulta("call instaticket_puertaganadora.obtener_premios()");
//    return $lstPremios;
    $consulta = "call obtener_premios(?)";
    return realizarConsultaParametros($consulta, array($eventoId));
}

function hasImpresora() {
    $consulta = "SELECT parametro_general_valor FROM parametro_general where parametro_general_nombre='" . Constantes::$IMPRESORA_ACTIVA . "';";
    return realizarConsulta($consulta);
}

/* FIN Instaticket */

function obtenerProbabilidad($probability) {
    $test = mt_rand(1, 100);
    return $test <= $probability;
}

function obtenerProbabilidadLista(array $lista) {
    $left = 0;
    foreach ($lista as $num => $right) {
        $lista[$num] = $left + $right;
        $left = $lista[$num];
    }
    $test = mt_rand(1, 100);
    $left = 1;
    foreach ($lista as $num => $right) {
        if ($test >= $left && $test <= $right) {
            return $num;
        }
        $left = $right;
    }
    return null;
}

function generarCodigo($longitud) {
    $key = '';
    $pattern = '1234567890';
    $max = strlen($pattern) - 1;
    for ($i = 0; $i < $longitud; $i++)
        $key .= $pattern{mt_rand(0, $max)};
    return $key;
}

function bar($codigo) {
    include('../wsInstaticket/barcodeGenerator/src/BarcodeGenerator.php');
    include('../wsInstaticket/barcodeGenerator/src/BarcodeGeneratorPNG.php');

    $generator = new Picqer\Barcode\BarcodeGeneratorPNG();
    //  return 'data:image/png;base64,' . base64_encode($generator->getBarcode($codigo, $generator::TYPE_CODE_128));
    return 'data:image/png;base64,' . base64_encode($generator->getBarcode($codigo, $generator::TYPE_CODE_39));
}
