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
                    obtenerJuegos();
                });
            }
        </script>
    </head>
    <body style="overflow: hidden">
        <div class="fondoBackground">
            <div class="bannerindex">
                <div class="col-xs-4 col-xs-offset-4">
                    <center><img src="img/LOGO INSTATICKET.png" style="height: 10vh" alt=""/></center>
                </div>
                
            </div>
            <div class="backgroundindex">
                <div class="col-xs-5" style="position: absolute">
                    <img style="position: relative;top:-10vh" class="img-responsive" src="img/LOGO DEL JUEGO PEQUEÑO.png" alt=""/>
                </div>
            </div>
        </div>



        <div class="container-fluid" style="position: relative;overflow: auto;">
            <div style="height: 9vh">
                <div class="col-xs-12" style="text-align: end;margin-top:24px;">
                    <button id="salir" class="btn btn-warning">Salir</button>
                </div>
            </div>
            <div class="row" >
                <center><h1> <label class="label">EVENTOS DEL MES</label></h1></center>
                <div class="col-md-12 col-sm-12" id="divEventos"  style="max-height: 80vh;overflow: auto;">
                </div>
            </div>

        </div>

    </body>
</html>
