<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title></title>
        <!--<link href='http://fonts.googleapis.com/css?family=Luckiest+Guy' rel='stylesheet' type='text/css'>

       <link rel='stylesheet prefetch' href='http://fonts.googleapis.com/css?family=Luckiest+Guy'>-->
        <link href="css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
        <style>
            *{
                margin: 0;
                padding: 0;
                /*box-sizing: border-box;*/
            }
            *:focus {
                outline: none;
            }
            canvas{
                margin: auto;
            }
            /*            #cintaPremios{
                            position: absolute;
                            width: 100wv;
                        }*/
            .premios{
                width: 50px;
                height: 50px;
                margin-left: 30px;
                margin-right: 30px;
            }
            #cintaPremios{
                position: absolute;
                width: 100vw;
                overflow: hidden;
                white-space: nowrap;
            }
            .bannerindex {
                background: #0075bc;
                width: 100%;
                height: 10vh;
                position: relative; 
            }
            .backgroundindex {
                width: 100%;
                height: 90vh;
                position: relative;
            }
            .premioImgs{
                margin: 1.5%;
                width: 8%;
            }
        </style>
        <script src="js/mi.js" type="text/javascript"></script>
        <script src="js/jquery-3.2.1.min.js" type="text/javascript"></script>
        <script src="plugin/jquery.blockUI.js" type="text/javascript"></script>
        <script src="js/bootstrap.min.js" type="text/javascript"></script>
        <script src="js/main.js" type="text/javascript"></script>
        <script src="js/phaser.min.js" type="text/javascript"></script>
        <script src="js/game/Boot.js" type="text/javascript"></script>
        <script src="js/game/Preloader.js" type="text/javascript"></script>
        <script src="js/game/GameTitle.js" type="text/javascript"></script>
        <script src="js/game/Game.js" type="text/javascript"></script>
        <script src="js/phaserModal.js" type="text/javascript"></script>
        <script src="js/phaserInput.js" type="text/javascript"></script>
        <script src="js/phaserNice.js" type="text/javascript"></script>

        <script type="text/javascript">
            const puertas = {"PRIMERA_PUERTA": "PRIMERA_PUERTA", "SEGUNDA_PUERTA": "SEGUNDA_PUERTA", "TERCERA_PUERTA": "TERCERA_PUERTA"};
            //const baseUrl = "http://192.168.100.8:8080";
            const video = "VIDEO_PRINCIPAL";
            const audio = "SONIDO_JUEGO";
            $(document).ready(function () {
                var game = new Phaser.Game("100%", "100%", Phaser.CANVAS, 'phaser-example', );
                game.state.add('Boot', Technotip.Boot);
                game.state.add('Preloader', Technotip.Preloader);
                game.state.add('GameTitle', Technotip.GameTitle);
                game.state.add('Game', Technotip.Game);

                game.state.start('Boot');

                var dataPremios = JSON.parse(sessionStorage.getItem("dataPremios"));
                console.log(dataPremios);
                var limit = dataPremios.length - 1;
                var imgtemp = 0;
                var imgtempbottom = 0;
                setInterval(function () {
                    $('#premioImg').attr('src', dataPremios[imgtemp].fotoPremio);
                    if (imgtemp == limit) {
                        imgtemp = 0;
                    } else {
                        imgtemp++;
                    }
                }, 3000);
                premios();
                setInterval(function () {
                    premios();
                }, 3000);
                function premios() {
                    var premiosBottom = "";
                    for (var i = 0; i < 6; i++) {
                        premiosBottom = premiosBottom + '<img class="premioImgs" src="' + dataPremios[imgtempbottom].fotoPremio + '" style="background: url(img/circulosmall.png);background-repeat: no-repeat;background-size: 100% 100%;"alt=""/>';
                        if (i == 2) {
                            premiosBottom = premiosBottom + '<div style="display: contents;"><img class="premioImgs" src="img/LOGO DEL JUEGO PEQUEÑO.png" style="width: 23vh;" alt=""/></div>';
                        }
                        imgtempbottom++;
                        if (imgtempbottom > limit) {
                            imgtempbottom = 0;
                        }
                    }
                    if (imgtempbottom >= limit) {
                        imgtempbottom = 0;
                    } else {
                        imgtempbottom++;
                    }
                    // $('#premiosBottom').html(premiosBottom).fadeIn(1000).fadeOut(2000);
                    $('#premiosBottom').html(premiosBottom);
                }
            });

        </script>
    </head>
    <body style="background: #0075bc;overflow: hidden">
        <div class="fondoBackground">
            <div class="bannerindex">
                <div class="col-xs-4 col-xs-offset-4">
                    <center><a href="eventos.php"><img src="img/LOGO INSTATICKET.png" style="height: 10vh" alt=""/></a></center>
                </div>
            </div>
            <div class="backgroundindex">

                <div id="imgtop"   style="position: relative;top:-10vh">

                    <div  style="position: absolute;right: 0;width: 15vw;">
                        <center><img src="js/game/recurso_juego/imagenes/oremios.png" width="65%" alt=""/>
                        <img id="premioImg" src="img/premiosP.png" width="75%" style="background: url(img/circulosmall.png);background-repeat: no-repeat;background-size: 100% 100%;position: relative;top: -4vw"alt=""/></center>
                    </div>
                </div>


                <div id="phaser-example" style="height: 75vh;"></div>

                <div id="imgbottom" class="hide"  style="position: relative;bottom: 0;">
                    <div  style="position: absolute;bottom: 0;width: 100vw;">
                        <center id="premiosBottom">

                        </center>
                    </div>
                </div>
            </div>
        </div>
        <!--        <div id="maindiv">
                    <br>
                    <div id="div1">
                    </div>
                </div>-->
        <!--            <marquee id="cintaPremios" behavior="scroll" direction="left">
                        <div id="premioslist" style="overflow: hidden;">
                            
                        </div>
                
                    </marquee>-->


        <div style="background: #0075bc;font-family:'Luckiest Guy',cursive;visibility:hidden;opacity:0;position:fixed;">&nbsp;</div>


    </body>
</html>
