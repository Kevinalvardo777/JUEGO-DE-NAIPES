<?php
error_reporting(E_ALL);
ini_set('display_errors', false);
/**Contiene todos los métodos generales de la base de datos */

/**
 * Realiza conexion con la base de datos y selecciona el esquema de conexion,
 * En caso de éxito devuelve la conexion con la base de datos
 * @return type
 */
$db_connection  = null;
function obtenerConexion(){
    try {
        $db_host = "localhost";
        $db_user = "admin";
        $db_password = "admin";
        $db_esquema = "instaticket_puertaganadora";
        $db_connection = mysql_connect($db_host, $db_user, $db_password) or die (mysql_errno());
	mysql_select_db($db_esquema);
        return $db_connection;
    } catch (PDOException $e) {
        errorMensaje(Constantes::$ERROR_CONEXION, $e->getMessage());
    } catch (Exception $ex){
        errorMensaje(999, $ex->getMessage());
    }
}

function obtenerConexionPdo(){
      $pdo = new PDO('mysql:host=localhost;dbname=instaticket_puertaganadora', 'root', 'root',
    //$pdo = new PDO('mysql:host=localhost;dbname=instaticket_puertaganadora', 'admin', 'admin',
            array(PDO::MYSQL_ATTR_INIT_COMMAND=> "SET lc_time_names='es_ES',NAMES utf8"));
    return $pdo;
}

/**
 * Realiza consulta a la base de datos según la consulta enviada por parámetro
 * @param type $consulta Sentencia SQL que representa la consulta a realizar
 * @return type Consulta
 */
function realizarConsulta($consulta){
    try {
   /* Bind parameters. Types: s = string, i = integer, d = double,  b = blob */
    $stmt = obtenerConexionPdo()->query($consulta);
    $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $rows = array();
    foreach ($resultado as $row) {
        $rows[] = $row;
    }
    if(count($rows)>0){
        return $rows;
    }
  } catch (PDOException $e) {
    errorMensaje(Constantes::$ERROR_CONEXION, $e->getMessage());
  } catch (Exception $ex){
    errorMensaje(999, $ex->getMessage());
  }
}

/**
 * Realiza insert en la base de datos según el insert enviado por parámetro
 * También se envían los parámetros a insertar. Se usa la librería PDO
 * @param type $consulta Sentencia SQL que representa a un insert que se realiza en la base de datos
 * @param type $parametros Lista de parámetros de la sentencia
 */
function realizarConsultaParametros($consulta,$parametros){
  try {
   /* Bind parameters. Types: s = string, i = integer, d = double,  b = blob */
    $stmt = obtenerConexionPdo()->prepare($consulta);
    $stmt->execute($parametros);
    $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $rows = array();
    foreach ($resultado as $row) {
        $rows[] = $row;
    }
    if(count($rows)>0){
        return $rows;
    }
  } catch (PDOException $e) {
    errorMensaje(Constantes::$ERROR_CONEXION, $e->getMessage());
  } catch (Exception $ex){
    errorMensaje(999, $ex->getMessage());
  }
}

/**
 * Realiza insert en la base de datos según el insert enviado por parámetro
 * @param type $insert Sentencia SQL que representa a un insert que se realiza en la base de datos
 */
function realizarInsercion($insert){
  try {
    obtenerConexionPdo()->query($insert);
  } catch (PDOException $e) {
    errorMensaje(Constantes::$ERROR_CONEXION, $e->getMessage());
  } catch (Exception $ex){
    errorMensaje(999, $ex->getMessage());
  }
}



/**
 * Realiza insert en la base de datos según el insert enviado por parámetro
 * También se envían los parámetros a insertar. Se usa la librería PDO
 * @param type $insert Sentencia SQL que representa a un insert que se realiza en la base de datos
 * @param type $parametros Lista de parámetros de la sentencia
 */
function realizarInsercionParametros($insert,$parametros){
  try {
   /* Bind parameters. Types: s = string, i = integer, d = double,  b = blob */
    $stmt = obtenerConexionPdo()->prepare($insert);
    $stmt->execute($parametros);
    //$a_data = $stmt->fetchAll(PDO::FETCH_ASSOC);
  } catch (PDOException $e) {
    errorMensaje(Constantes::$ERROR_CONEXION, $e->getMessage());
  } catch (Exception $ex){
    errorMensaje(999, $ex->getMessage());
  }
}

/**
 * Realiza update en la base de datos según el update enviado por parámetro
 * También se envían los parámetros a actualizar. Se usa la librería PDO
 * @param type $update Sentencia SQL que representa a un update que se realiza en la base de datos
 * @param type $parametros Lista de parámetros de la sentencia
 */
function realizarActualizacionParametros($update,$parametros){
  try {
   /* Bind parameters. Types: s = string, i = integer, d = double,  b = blob */
    $stmt = obtenerConexionPdo()->prepare($update);
    $stmt->execute($parametros);
    //$a_data = $stmt->fetchAll(PDO::FETCH_ASSOC);
  } catch (PDOException $e) {
    errorMensaje(Constantes::$ERROR_CONEXION, $e->getMessage());
  } catch (Exception $ex){
    errorMensaje(999, $ex->getMessage());
  }
}

/**
 * Realiza actualización en la base de datos con la sentencia otorgada
 * @param type $update Sentencia update a realizar
 */
function realizarActualizacion($update){
    try {
    if(obtenerConexionPdo()->prepare($update) === TRUE){
    } else{
        errorMensaje(Constantes::$ERROR_ACTUALIZAR, $update);
    }
    
  } catch (PDOException $e) {
    errorMensaje(Constantes::$ERROR_CONEXION, $e->getMessage());
  } catch (Exception $ex){
    errorMensaje(999, $ex->getMessage());
  }
}

function validarNombreColumna(){
    
}