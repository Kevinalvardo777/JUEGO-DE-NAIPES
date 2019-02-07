//var urlServicios = "http://localhost:8080/wsInstaticket/servicios";

//var urlServicios = "http://192.168.0.100:8080/wsInstaticket/servicios.php";

var urlServicios = "/wsInstaticket/servicios.php";





//obtenerInfomacionPrincipal
var divCargando = '<div class="cssload-container"><div class="cssload-loading"><i></i><i></i><i></i><i></i></div></div>';
$(document).ready(function () {
    $('#login').submit(function () {
        iniciarSesion();
        return false;
    });

    $('#salir').on('click',function(){
       cerrarSesion();
    });


});

function iniciarSesion() {
    var username, password;
    if (document.getElementById("username")) {
        username = document.getElementById("username").value;
    }
    if (document.getElementById("password")) {
        password = document.getElementById("password").value;
    }
    if (!username || !password) {
        alert('Tiene que ingresar las credenciales');
    }
    var data = {"metodo": "iniciarSesion", "username": username, "password": password};

    var respuesta = realizarPeticion(data);
    if (respuesta.resultado === "ok") {
        sessionStorage.setItem("token", respuesta.lista.token);
        sessionStorage.setItem("sesion", new Date().getHours());
        window.location.href = "eventos.php";
    } else {
        alert(respuesta.texto);
    }
}
function validarSesion() {
    if (tiempoActivo()) {
        return true;
    }
    var token = sessionStorage.getItem("token");
    if (token) {
        var data = {"metodo": "cargarDatosUsuario", "token": token};
        var respuesta = realizarPeticion(data);
        if (respuesta.resultado === "ok") {
            return true;
        } else {
            alert("No ha iniciado sesión");
            window.location.href = "index.php";
            return false;
        }
    } else {
        alert("No ha iniciado sesión");
        window.location.href = "index.php";
        return false;
    }
    return true;
}
function tiempoActivo() {
    if (sessionStorage.getItem("sesion")) {
        var horaSesion = sessionStorage.getItem("sesion");
        var horaActual = new Date().getHours();
        return (horaActual - horaSesion) <= 8;
    }
    return false;
}
function realizarPeticion(datos) {
    $.ajax({
        url: urlServicios,
        type: 'POST',
        async: false,
        data: JSON.stringify(datos),
        contentType: "application/json;charset=utf-8",
        error: function (e, g, f) {
//            console.log(e);
        },
        beforeSend: function () {
            $.blockUI({
                message: divCargando
            });
        },
        success: function (d) {
//            console.log(d);
            procesarDatos(d);
        },
        complete: function () {
            setTimeout($.unblockUI);
        }
    }).done(function (x) {
        procesarDatos(x);
//        console.log(x);
    });

    return datosAjax;
    /*return JSON.parse(remote.responseText);*/
}
var datosAjax = undefined;
function procesarDatos(sParam) {
    datosAjax = sParam;
}
function obtenerEventos() {
    var token = sessionStorage.getItem("token");
    
    var data = {"metodo": "obtenerEventos", "token": token};
    var respuesta = realizarPeticion(data);
    console.log(respuesta);
    if (respuesta.resultado === "ok") {
        var eventosActuales = respuesta.lista;
        var divEventos = $("#divEventos");
        var divNoEvento = "<div class='col-md-12' style='border: 2px solid white'>";
        divNoEvento = divNoEvento + "<div  class='col-md-6 '><span class='icono-regalo glyphicon glyphicon-gift'></span></div>";
        divNoEvento = divNoEvento + "<div class='centrar'>";
        divNoEvento = divNoEvento + "<label style='background: transparent; font-size: 100%;width: 100%;display: inline-block; color: white'>";
        divNoEvento = divNoEvento + "No hay eventos en el sistema por el momento</label>";
        divNoEvento = divNoEvento + "</div>";
        divNoEvento = divNoEvento + "</div>";

        if (eventosActuales) {
            for (var i = 0; i < eventosActuales.length; i++) {
                var eventoAct=JSON.stringify(eventosActuales[i]);
                var div = "<div  onclick='guardarEvento("+eventoAct+")' class='col-md-3 col-sm-6' >";
                div = div + '<div class="example-1 card">';
                div = div + '<div class="wrapper" style="background:url(recurso_juego/' + eventosActuales[i].evento_url_imagen + ')">';
                div = div + '<div class="date">';
                div = div + '<span class="day">' + eventosActuales[i].evento_inicio + '</span>';
                div = div + '<span class="day">' + eventosActuales[i].evento_fin + '</span>';
                div = div + '</div>';
                div = div + '<div class="data">';
                div = div + '<div class="content">';
                div = div + '<center><h3 class="title">';
                div = div + eventosActuales[i].evento_nombre;
                div = div + '</h3></center>';
                div = div + '</div>';
                div = div + '<input type="checkbox" id="show-menu" />';
                div = div + '<ul class="menu-content">';
                div = div + '<li>';
                div = div + '<a href="#" class="fa fa-bookmark-o"></a>';
                div = div + '</li>';
                div = div + '<li><a href="#" class="fa fa-heart-o"><span>47</span></a></li>';
                div = div + '<li><a href="#" class="fa fa-comment-o"><span>8</span></a></li>';
                div = div + '</ul>';
                div = div + '</div>';
                div = div + '</div>';
                div = div + '</div>';
                div = div + '</div>';

                divEventos.append(div);

            }
        } else {
            divEventos.append(divNoEvento);
        }
    } else {
//        alert(respuesta.texto);
//        cerrarSesion();
    }
}


function guardarEvento(evento){
     sessionStorage.setItem("eventoAlmacenado",JSON.stringify(evento));   
     window.location.href = "eventosEscogerjuego.php";
}

function obtenerJuegos() {
    //var token = sessionStorage.getItem("token");
    //var data = {"metodo": "obtenerEventos", "token": token};
    //var respuesta = realizarPeticion(data);
    //console.log(respuesta);
    var evento = JSON.parse(sessionStorage.getItem("eventoAlmacenado"));
	//console.log(evento.turnos);
    if (evento.juego_carta === "S") {
        //var eventosActuales = respuesta.lista;
        var divEventos = $("#divEventos");
/*
        var divNoEvento = "<div class='col-md-12' style='border: 2px solid white'>";
        divNoEvento = divNoEvento + "<div  class='col-md-6 '><span class='icono-regalo glyphicon glyphicon-gift'></span></div>";
        divNoEvento = divNoEvento + "<div class='centrar'>";
        divNoEvento = divNoEvento + "<label style='background: transparent; font-size: 100%;width: 100%;display: inline-block; color: white'>";
        divNoEvento = divNoEvento + "No hay eventos en el sistema por el momento</label>";
        divNoEvento = divNoEvento + "</div>";
        divNoEvento = divNoEvento + "</div>";*/

        //if (eventosActuales) {
          //  for (var i = 0; i < 2; i++) {
                var div = '<div  onclick="obtenerDataJuego(' + evento.ciclo_evento_id + ',' + evento.evento_id + ',\''+evento.evento_nombre+'\''+ ',\'2\')" class="col-md-3 col-sm-6" >';
                div = div + '<div class="example-1 card">';
                div = div + '<div class="wrapper" style="background:url(recurso_juego/' + evento.evento_url_imagen + ')">';
                div = div + '<div class="data">';
                div = div + '<div class="content">';
                div = div + '<center><h3 class="title">';
                div = div + "JUEGO CARTAS";
                div = div + '</h3></center>';
                div = div + '</div>';
                div = div + '<input type="checkbox" id="show-menu" />';
                div = div + '<ul class="menu-content">';
                div = div + '<li>';
                div = div + '<a href="#" class="fa fa-bookmark-o"></a>';
                div = div + '</li>';
                div = div + '<li><a href="#" class="fa fa-heart-o"><span>47</span></a></li>';
                div = div + '<li><a href="#" class="fa fa-comment-o"><span>8</span></a></li>';
                div = div + '</ul>';
                div = div + '</div>';
                div = div + '</div>';
                div = div + '</div>';
                div = div + '</div>';

                divEventos.append(div);

          //  }
        /*} else {
            divEventos.append(divNoEvento);
        }*/
    } 
    
        if (evento.juego_puerta === "S" ) {
        //var eventosActuales = respuesta.lista;
        var divEventos = $("#divEventos");
                var div = '<div  onclick="obtenerDataJuego(' + evento.ciclo_evento_id + ',' + evento.evento_id + ',\''+evento.evento_nombre+'\''+ ',\'1\')" class="col-md-3 col-sm-6" >';
                div = div + '<div class="example-1 card">';
                div = div + '<div class="wrapper" style="background:url(recurso_juego/' + evento.evento_url_imagen + ')">';
                div = div + '<div class="data">';
                div = div + '<div class="content">';
                div = div + '<center><h3 class="title">';
                div = div + "JUEGO PUERTA";
                div = div + '</h3></center>';
                div = div + '</div>';
                div = div + '<input type="checkbox" id="show-menu" />';
                div = div + '<ul class="menu-content">';
                div = div + '<li>';
                div = div + '<a href="#" class="fa fa-bookmark-o"></a>';
                div = div + '</li>';
                div = div + '<li><a href="#" class="fa fa-heart-o"><span>47</span></a></li>';
                div = div + '<li><a href="#" class="fa fa-comment-o"><span>8</span></a></li>';
                div = div + '</ul>';
                div = div + '</div>';
                div = div + '</div>';
                div = div + '</div>';
                div = div + '</div>';

                divEventos.append(div);
    } 
    //else {
//        alert(respuesta.texto);
//        cerrarSesion();
   // }
}

function obtenerDataJuego(cicloEventoId,eventoId, nombreEvento, tipo) {
    console.log(cicloEventoId+"----"+eventoId+"----"+nombreEvento);
    var token = sessionStorage.getItem("token");
    var data = {"metodo": "obtenerInformacionPrincipal", "token": token, "evento": eventoId, "cicloEvento": cicloEventoId, "tipoJuego": tipo};
    var respuesta = realizarPeticion(data);
    console.log(respuesta);
    if (respuesta.resultado === "ok") {
        sessionStorage.setItem("eventoId", eventoId);
        sessionStorage.setItem("cicloEventoId", cicloEventoId);
        sessionStorage.setItem("nombreEvento", nombreEvento);
        sessionStorage.setItem("dataEvento", JSON.stringify(respuesta.lista.informacionEvento));
        sessionStorage.setItem("dataPremios", JSON.stringify(respuesta.lista.premios));
        if(tipo==1){
        window.location.href = "game.php";
    }else{
        
     window.location.href = "cartas.html";
    }
//console.log(JSON.parse(sessionStorage.getItem("dataPrincipal")));
    } else {
//        alert(respuesta.texto);
//        cerrarSesion();
    }
}

function obtenerResultadoJuego() {
//    console.log(sessionStorage.getItem("eventoId"));
    var token = sessionStorage.getItem("token");
    var idEvento = sessionStorage.getItem("eventoId");
    var cicloEventoId = sessionStorage.getItem("cicloEventoId");
    if (token !== null || idEvento !== null) {
        var data = {"metodo": "jugar", "token": token, "evento": idEvento, "cicloEvento": cicloEventoId};
        var respuesta = realizarPeticion(data);
//        console.log(respuesta);
        if (respuesta.resultado === "ok") {
            sessionStorage.removeItem("dataJuego");
            sessionStorage.setItem("dataJuego", JSON.stringify(respuesta.lista));
        } else {
//        alert(respuesta.texto);
//        cerrarSesion();
        }
    } else {
//        alert(respuesta.texto);
//        cerrarSesion();
    }
}


function cerrarSesion() {
    sessionStorage.removeItem("datosPersonales");
    sessionStorage.removeItem("token");
    sessionStorage.removeItem("sesion");
    window.location.href = "index.php";
}