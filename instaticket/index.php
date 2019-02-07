<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title></title>
        <link href="css/login.css" rel="stylesheet" type="text/css"/>
        <link href="css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
        <link href="css/cargando.css" rel="stylesheet" type="text/css"/>
        <script src="js/jquery-3.2.1.min.js" type="text/javascript"></script>
        <script src="js/bootstrap.min.js" type="text/javascript"></script>
        <script src="plugin/bootbox.min.js" type="text/javascript"></script>
        <script src="plugin/jquery.blockUI.js" type="text/javascript"></script>
        <script src="js/main.js" type="text/javascript"></script>

    </head>
    <body>
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

        <div class="content" style="position: relative;">
            <div class="wrapper">
                <form id="login" class="form-signin" method="POST">
                    <div class="col-xs-4 col-xs-offset-4"><center><img class="img-responsive" src="img/ICO INICIO DE SESION.png" alt=""/></center></div>
                    <h2 class="form-signin-heading">Bienvenidos</h2>
                    <input type="text" id="username" class="form-control" name="username" placeholder="Email Address" required="" autofocus="" />
                    <br>
                    <input type="password" id="password" class="form-control" name="password" placeholder="Password" required=""/> 
                    <br>
                    <button class="form-control btn-block" type="submit" style="background: black;color: white;border: none;font-family: monospace;">INGRESAR</button>   
                </form>
            </div>
        </div>



    </body>
</html>
