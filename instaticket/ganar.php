<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
        <link href="css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
        <link href="css/eventos.css" rel="stylesheet" type="text/css"/>

        <script src="js/jquery-3.2.1.min.js" type="text/javascript"></script>
        <script src="js/bootstrap.min.js" type="text/javascript"></script>
        <script src="plugin/jquery.blockUI.js" type="text/javascript"></script>
        <script src="js/main.js" type="text/javascript"></script>
        <script>
            if (validarSesion()) {
                $(document).ready(function () {
                    var fechaActual = new Date().toJSON().slice(0, 10);
                    var dataJuego = JSON.parse(sessionStorage.getItem("dataJuego"));
                    var nombreEvento = sessionStorage.getItem("nombreEvento");
                    var premioNombre = dataJuego.premioGanador[0].nombre;
                    $('#codigo').text(dataJuego.codigoPremio);
                    $('#imgCodigo').attr("src", dataJuego.imgCode);
                    $('#premioImg').attr("src", dataJuego.premioGanador[0].fotoPremio);
                    $('#nombrePremioGanado').text(premioNombre);

//                    if (sessionStorage.getItem("codePrint") == dataJuego.codigoPremio) {
//                        $('#Imprimir').addClass("hide");
//                        $('#volver').removeClass("hide");
//                    } else {
//                        sessionStorage.setItem("codePrint", "");
//                    }
                    hasPrint();
                    $('#Imprimir').click(function () {
                        var data = {"opcion": "imprimirCodigo", "codigo": dataJuego.codigoPremio, 'nombreEvento': nombreEvento, 'premioNombre': premioNombre, 'fechaActual': fechaActual};

                        $.ajax({
                            url: "ImprimirTicket.php",
                            type: 'POST',
                            data: data,
                            success: function (d) {
                                $('#Imprimir').addClass("hide");
                                $('#volver').removeClass("hide");
                                sessionStorage.setItem("codePrint", dataJuego.codigoPremio);
                                console.log(d);
                                //alert(d['data']['mensaje']);
                            },
                            error: function (d) {
                                console.log("error");
                                sessionStorage.setItem("codePrint", dataJuego.codigoPremio);
                            }
                        });
                    });

                    function hasPrint() {
                        var data = {"metodo": "impresora"};

                        $.ajax({
                            url: "/wsInstaticket/servicios.php",
                            type: 'POST',
                            data: JSON.stringify(data),
                            contentType: "application/json;charset=utf-8",
                            success: function (d) {
                                if (d.lista == "NO") {
                                    $('#Imprimir').addClass("hide");
                                    $('#volver').removeClass("hide");
                                }
                                console.log(d);
                                //alert(d['data']['mensaje']);
                            },
                            error: function (d) {
                                console.log("error");
                            }
                        });
                    }
                });
            }
        </script>
    </head>
    <body>
        <div class="fondoBackground">
            <div class="bannerindex">
                <div class="col-xs-4 col-xs-offset-4">
                    <center><a href="eventos.php"><img src="img/LOGO INSTATICKET.png" style="height: 10vh" alt=""/></a></center>
                </div>
            </div>
            <div class="backgroundindex" style="overflow: auto">
                <div class="container-fluid">
                    <br><br><br><br>
                    <div class="row">
                        <div class="col-xs-4" style="position: absolute;z-index: 10;transform: rotateZ(-2deg);">
                            <center>  <img style="position: relative;top: -30px;height: 154px;" width="91%" src="img/FELICITACIONES.png" alt=""/></center>
                        </div>


                        <div class="col-xs-4 col-xs-offset-1">
                            <br><br>
                            <div class="col-xs-12" style="margin-top: 15px">
                                <center><img id="premioImg" src="" width="70%" style="background: url(img/circulolarge.png);background-repeat: no-repeat;background-size: 100% 100%;" alt=""/></center>
                            </div>

                            <!--<div class="col-xs-12"><img src="img/BASE.png" width="100%" height="55px" alt=""/></div>-->
                            <div class="col-xs-12" style="background: black;color: white;margin-top: 5%">
                                <center><h6 style="font-size: 150%">HAS GANADO</h6>
                                    <h4 id="nombrePremioGanado" style="font-size: 200%">Nombre Premio</h4></center>
                            </div>
                            <br>
                        </div>
                        <div class="col-xs-4 col-xs-offset-1">
                            <div class="panel" style="border: none;border-radius: 15px;">
                                <div class="panel-heading" style="border-radius: 15px 15px 0 0;background: black;color: white;font-weight: bold;">
                                    <center><h3 style="font-size: 200%">CÓDIGO DE PREMIO</h3></center> 
                                </div>
                                <div class="panel-body">
                                    <br>
                                    <div class="col-xs-8 col-xs-offset-2" style="margin-bottom: 25px">
                                        <img id="imgCodigo" style="width: 100%;height: 25vh" src="">

                                    </div>

                                    <center><h1 id="codigo" style="font-weight: 1000;font-size: 400%"></h1></center>
                                    <br>
                                </div>
                            </div>
                            <div class="col-xs-12">
                                <button id="Imprimir" style="width: 100%;border-radius: 50px;background: black;border:none;height: 50px;color: white;font-size: 25px" >
                                    <center>
                                        <img src="img/FLECHA CONTINUAR.png" alt=""/>
                                        IMPRIMIR
                                    </center>
                                </button>
                                <a id="volver" href="pantalla.html" class="btn hide" style="width: 100%;border-radius: 50px;background: black;border:none;height: 50px;color: white;font-size: 200%" >
                                    <center>
                                        <img src="img/FLECHA CONTINUAR.png" alt=""/>
                                        VOLVER A JUGAR
                                    </center>
                                </a>
                            </div>
                        </div>
                    </div>
                    <br>
                </div>
            </div>
        </div>


    </body>
</html>
