<?php

/**
 * Proceso que solicita peticion de los servicios
 */
function validarCredenciales(){
    $valid_passwords = array ("comandato" => "rulet42016");
    $valid_users = array_keys($valid_passwords);
    $user = $_SERVER['PHP_AUTH_USER'];
    $pass = $_SERVER['PHP_AUTH_PW'];
    $validated = (in_array($user, $valid_users)) && ($pass == $valid_passwords[$user]);

    if (!$validated) {
      header('WWW-Authenticate: Basic realm="My Realm"');
      header('HTTP/1.0 401 Unauthorized');
      die ("No tiene acceso a la url especificada");
    }
}