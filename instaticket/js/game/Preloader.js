Technotip.Preloader = function (game) {
    this.ready = false;
};

Technotip.Preloader.prototype = {
    preload: function () {
        this.backgroundi = this.add.image(0, 0, "backgroundcarga");
        this.backgroundi.height = this.world.height;
        this.backgroundi.width = this.world.width;
//        this.backgroundi.animations.add('next', [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10]);
//        this.backgroundi.animations.play('next', 1, true);

//        var loadBar = this.game.cache.getImage('preloadbar');

        this.preloadBar = this.add.sprite(this.world.height / 3, 0, 'preloadbar');
        this.preloadBar.height = this.world.height / 2;
        this.preloadBar.width = this.world.width / 2;
        this.load.setPreloadSprite(this.preloadBar);
        var dataEvento = JSON.parse(sessionStorage.getItem("dataEvento"));
console.log(dataEvento);
        try {
            
            if(dataEvento!=null){
            for (var i = 0; i < dataEvento.length; i++) {
                if (video === dataEvento[i]['tipo_url_nombre']) {
                    this.load.video(dataEvento[i]['tipo_url_nombre'], dataEvento[i]['url']);
                } else
                if (puertas.PRIMERA_PUERTA === dataEvento[i]['tipo_url_nombre'] || puertas.SEGUNDA_PUERTA === dataEvento[i]['tipo_url_nombre'] || puertas.TERCERA_PUERTA === dataEvento[i]['tipo_url_nombre']) {
                    this.load.spritesheet(dataEvento[i]['tipo_url_nombre'], dataEvento[i]['url'], 348, 720, 10);
                } else
                if (audio === dataEvento[i]['tipo_url_nombre']) {
                    this.load.audio(dataEvento[i]['tipo_url_nombre'], dataEvento[i]['url']);
                } else {
                    this.load.image(dataEvento[i]['tipo_url_nombre'], dataEvento[i]['url']);
                }
            }
        }else{
            alert("No existe configuración del evento");
            window.location.href="eventos.php";
        }
        } catch (error) {
            console.log("Error de lista datos de entorno: "+error);
        }


        this.load.image('default', 'js/game/recurso_juego/imagenes/default.png');
        this.load.image('lose', 'js/game/recurso_juego/imagenes/lose.png');
        
        this.load.image('reflector', 'js/game/recurso_juego/imagenes/reflector.png');
        this.load.image('mouse', 'js/game/recurso_juego/imagenes/cursor.png');
        this.load.image('ganaste', 'js/game/recurso_juego/imagenes/ganaste.png');
        this.load.audio('songDoor', 'js/game/recurso_juego/sonido/door.wav');
		
		this.load.audio('songPerder', 'js/game/recurso_juego/sonido/perdedor.mp3');
		this.load.audio('songGanar', 'js/game/recurso_juego/sonido/ganador.mp3');
		
		this.load.image('particulaRed', 'js/game/recurso_juego/imagenes/particulas/red_p.png');
		this.load.image('particulaBlue', 'js/game/recurso_juego/imagenes/particulas/blue_p.png');
		this.load.image('particulaYellow', 'js/game/recurso_juego/imagenes/particulas/yellow_p.png');
		this.load.image('particulaGreen', 'js/game/recurso_juego/imagenes/particulas/green_p.png');
		
		this.load.image('personaje', 'js/game/recurso_juego/imagenes/personaje.png');
		
		this.load.image('wowomg', 'js/game/recurso_juego/imagenes/WowOmg.png');
		this.load.image('yeah', 'js/game/recurso_juego/imagenes/Yeah.png');
		
		this.load.image('fondoGanarBack', 'js/game/recurso_juego/imagenes/fondo_gana_1.png');
		this.load.image('fondoGanarFront', 'js/game/recurso_juego/imagenes/fondo_gana_2.png');
		
		this.load.image('fondoGanarPersonaje', 'js/game/recurso_juego/imagenes/personaje.png');

		
		
        this.load.onLoadComplete.add(this.loadComplete, this);


    },
    create: function () {
//        this.state.start('GameTitle');
    },
    loadComplete: function () {
        this.ready = true;
    },
    update: function () {
        if (this.ready === true)
        {
          this.state.start('GameTitle');
        }
    }
};
