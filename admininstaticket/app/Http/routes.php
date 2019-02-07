<?php

/*
  |--------------------------------------------------------------------------
  | Application Routes
  |--------------------------------------------------------------------------
  |
  | Here is where you can register all of the routes for an application.
  | It's a breeze. Simply tell Laravel the URIs it should respond to
  | and give it the controller to call when that URI is requested.
  |
 */

Route::auth();
Route::get('/', function () {
    return view('auth.login');
});

Route::group(['middleware' => ['verifyToken', 'auth']], function () {
    Route::get('/welcome', function () {
        return view('welcome');
    });
    Route::get('/', function () {
        return view('welcome');
    });

    Route::get('/home', 'HomeController@index');
    
    //MANTENIMIENTOS
    //PROBABILIDADES
    Route::resource('/probabilidades', 'Mantenimientos\ProbabilidadesController');
    Route::post('/validarProbabilidad', 'Mantenimientos\ProbabilidadesController@validarProbabilidad');
    Route::post('/guardarProbabilidad', 'Mantenimientos\ProbabilidadesController@guardarProbabilidad');
    Route::post('/actualizarProbabilidad', 'Mantenimientos\ProbabilidadesController@actualizarProbabilidad');
    Route::post('/buscarProbabilidad', 'Mantenimientos\ProbabilidadesController@buscarProbabilidad');
    
    //CICLO
    Route::resource('/ciclos', 'Mantenimientos\CicloController');
    Route::post('/validarCiclo', 'Mantenimientos\CicloController@validarCiclo');
    Route::post('/guardarCiclo', 'Mantenimientos\CicloController@guardarCiclo');
    Route::post('/actualizarCiclo', 'Mantenimientos\CicloController@actualizarCiclo');
    Route::post('/buscarCiclo', 'Mantenimientos\CicloController@buscarCiclo');
    
    //CATEGORIAS
    Route::resource('/categorias', 'Mantenimientos\CategoriasController');
    Route::post('/validarCategoria', 'Mantenimientos\CategoriasController@validarCategoria');
    Route::post('/guardarCategoria', 'Mantenimientos\CategoriasController@guardarCategoria');
    Route::post('/actualizarCategoria', 'Mantenimientos\CategoriasController@actualizarCategoria');
    Route::post('/buscarCategoria', 'Mantenimientos\CategoriasController@buscarCategoria');
    
    
    //EVENTOS
    Route::resource('/eventos', 'Mantenimientos\EventosController');
    Route::post('/validarEvento', 'Mantenimientos\EventosController@validarEvento');
    Route::post('/guardarEvento', 'Mantenimientos\EventosController@guardarEvento');
    Route::post('/actualizarEvento', 'Mantenimientos\EventosController@actualizarEvento');
    Route::post('/buscarEvento', 'Mantenimientos\EventosController@buscarEvento');
    
    
    //PREMIOS
    Route::resource('/premios', 'Mantenimientos\PremiosController');
    Route::post('/validarPremio', 'Mantenimientos\PremiosController@validarPremio');
    Route::post('/guardarPremio', 'Mantenimientos\PremiosController@guardarPremio');
    Route::post('/actualizarPremio', 'Mantenimientos\PremiosController@actualizarPremio');
    Route::post('/buscarPremio', 'Mantenimientos\PremiosController@buscarPremio');
    
    
    //CONFIGURACION
    //CICLO EVENTO
    Route::resource('/cicloevento', 'Configuracion\CicloEventoController');
    Route::post('/validarCicloEvento', 'Configuracion\CicloEventoController@validarCicloEvento');
    Route::post('/guardarCicloEvento', 'Configuracion\CicloEventoController@guardarCicloEvento');
    Route::post('/actualizarCicloEvento', 'Configuracion\CicloEventoController@actualizarCicloEvento');
    Route::post('/buscarCicloEvento', 'Configuracion\CicloEventoController@buscarCicloEvento');
    
    //CICLO EVENTO
    Route::resource('/parametrosgenerales', 'Configuracion\ParametrosGeneralesController');
    //Route::post('/validarCicloEvento', 'Configuracion\CicloEventoController@validarCicloEvento');
    //Route::post('/guardarCicloEvento', 'Configuracion\CicloEventoController@guardarCicloEvento');
    Route::post('/actualizarParametrosGenerales', 'Configuracion\ParametrosGeneralesController@actualizarParametrosGenerales');
    //Route::post('/buscarCicloEvento', 'Configuracion\CicloEventoController@buscarCicloEvento');
    
    
    //CICLO EVENTO PREMIO
    Route::resource('/cicloeventopremio', 'Configuracion\CicloEventoPremioController');
    Route::post('/validarCicloEventoPremio', 'Configuracion\CicloEventoPremioController@validarCicloEventoPremio');
    Route::post('/guardarCicloEventoPremio', 'Configuracion\CicloEventoPremioController@guardarCicloEventoPremio');
    Route::post('/actualizarCicloEventoPremio', 'Configuracion\CicloEventoPremioController@actualizarCicloEventoPremio');
    Route::post('/buscarCicloEventoPremio', 'Configuracion\CicloEventoPremioController@buscarCicloEventoPremio');
    
    
    //CONFIGURACION EVENTO
    Route::resource('/configevento', 'Configuracion\ConfiguracionEventoController');
    Route::post('/buscarConfiguracionEvento', 'Configuracion\ConfiguracionEventoController@buscarConfiguracionEvento');
    Route::post('/guardarConfiguracionEvento', 'Configuracion\ConfiguracionEventoController@guardarConfiguracionEvento');
    Route::post('/eliminarConfiguracionEvento', 'Configuracion\ConfiguracionEventoController@eliminarConfiguracionEvento');
    
     //CONFIGURACION EVENTO
    Route::resource('/incremento', 'Configuracion\IncrementoProbabilidadPremioController');
    Route::post('/validarIncrementoProbabilidadPremio', 'Configuracion\IncrementoProbabilidadPremioController@validarIncrementoProbabilidadPremio');
    Route::post('/guardarIncrementoProbabilidadPremio', 'Configuracion\IncrementoProbabilidadPremioController@guardarIncrementoProbabilidadPremio');
    Route::post('/buscarIncrementoProbabilidadPremio', 'Configuracion\IncrementoProbabilidadPremioController@buscarIncrementoProbabilidadPremio');
    Route::post('/actualizarIncrementoProbabilidadPremio', 'Configuracion\IncrementoProbabilidadPremioController@actualizarIncrementoProbabilidadPremio');
    
    //USUARIOS
    Route::resource('/usuarios', 'Configuracion\UsuariosController');
    Route::post('/validarUsuario', 'Configuracion\UsuariosController@validarUsuario');
    Route::post('/guardarUsuario', 'Configuracion\UsuariosController@guardarUsuario');
    Route::post('/actualizarUsuario', 'Configuracion\UsuariosController@actualizarUsuario');
    Route::post('/buscarUsuario', 'Configuracion\UsuariosController@buscarUsuario');
    
    
    //REGISTRADOR
    //REGISTRO PARTICIPANTE
    Route::resource('/registroparticipante', 'Registrador\RegistroParticipanteController');
    Route::post('/actualizarParametro', 'Registrador\RegistroParticipanteController@actualizarParametro');
    Route::post('/buscarCodigoBarra', 'Registrador\RegistroParticipanteController@buscarCodigoBarra');
    Route::post('/buscarParticipante', 'Registrador\RegistroParticipanteController@buscarParticipante');
    Route::post('/validarRegistroParticipante', 'Registrador\RegistroParticipanteController@validarRegistroParticipante');
    Route::post('/guardarRegistroParticipante', 'Registrador\RegistroParticipanteController@guardarRegistroParticipante');
    Route::post('/actualizarRegistroParticipante', 'Registrador\RegistroParticipanteController@actualizarRegistroParticipante');
    
    //REGISTRO PREMIO PARTICIPANTE
    Route::resource('/registropremioparticipante', 'Registrador\PremioParticipanteController');
    Route::post('/buscarPremioParticipante', 'Registrador\PremioParticipanteController@buscarPremioParticipante');
    Route::post('/buscarParticipanteBoletoDetalle', 'Registrador\PremioParticipanteController@buscarParticipanteBoletoDetalle');
    Route::post('/validarPremioParticipante', 'Registrador\PremioParticipanteController@validarPremioParticipante');
    Route::post('/guardarRegistroPremioParticipante', 'Registrador\PremioParticipanteController@guardarRegistroPremioParticipante');
    
    
    //REPORTES
    //REGISTRADOR - PREMIOS 
    Route::resource('/registradorrptpremios', 'Reportes\Registrador\ReportePremiosController');
    
    //REGISTRADOR - GANADORES 
    Route::resource('/registradorrptganadorjuego', 'Reportes\Registrador\ReporteGanadoresController');
    
    //ADMINISTRADOR - PREMIOS 
    Route::resource('/registradorrptpremiosad', 'Reportes\Administrador\ReportePremiosAdController');
    Route::post('/buscarPremiosxCicloEvento', 'Reportes\Administrador\ReportePremiosAdController@buscarPremiosxCicloEvento');
    
    //ADMINISTRADOR - GANADORES 
    Route::resource('/registradorrptganadoresad', 'Reportes\Administrador\ReporteGanadoresAdController');
    Route::post('/buscarGanadoresxCicloEvento', 'Reportes\Administrador\ReporteGanadoresAdController@buscarGanadoresxCicloEvento');
    
});
