Technotip.GameTitle = function (game) {
    this.ready = false;
//    this.user;
};

Technotip.GameTitle.prototype = {
    preload: function () {



        this.video = this.add.video('VIDEO_PRINCIPAL');

        
        this.videoScale = Math.min(this.world.width / this.video.width, this.world.height / this.video.height);
        this.videorender = this.video.addToWorld(this.world.centerX, this.world.centerY, 0.5, 0.5, this.videoScale, this.videoScale);



//        var userBg = this.game.add.nineSlice(this.world.width / 3, (this.world.height / 3.5), 'input', null, this.world.width / 3, 120);
//        this.user = this.game.add.inputField(this.world.width / 3, (this.world.height / 3.5), {
//            font: '54px Arial',
//            fill: '#ffffff',
//            fillAlpha: 0,
//            fontWeight: 'bold',
//            forceCase: PhaserInput.ForceCase.upper,
//            width: (this.game.width / 3) - (this.game.width / 3) * 0.12,
//            max: 20,
//            padding: 16,
//            borderWidth: 1,
//            borderColor: '#ffffff',
//            borderRadius: 6,
//            placeHolder: 'CODIGO',
//            textAlign: 'center',
//            zoom: true
//        });//input




        this.button = this.add.button(this.world.width-(this.world.width / 3.5),
                this.world.height -(this.world.height / 8), 'BOTON_PRINCIPAL',
                this.loadComplete, this);
        this.button.height = this.world.height / 9;
        this.button.width = this.world.width / 5;
        this.button.input.useHandCursor = true;
this.video.play(true);

    },
    loadComplete: function () {
        obtenerResultadoJuego();
        var dataJuego = JSON.parse(sessionStorage.getItem("dataJuego"));
        console.log(dataJuego);
        if (dataJuego) {
            this.ready = true;
        }else{
            alert("No se obtuvieron datos de juego");
        }
        
    },
    create: function () {
        this.cursor = this.game.add.sprite(this.world.width / 90, this.world.width / 90, 'mouse');
        this.cursor.height = this.world.width / 55;
        this.cursor.width = this.world.width / 55;
        //  Enable Arcade Physics for the sprite
        this.game.physics.enable(this.cursor, Phaser.Physics.ARCADE);

        //  Tell it we don't want physics to manage the rotation
//        this.cursor.body.allowRotation = false;
    },
    update: function () {
        this.game.physics.arcade.moveToPointer(this.cursor, 160, this.game.input.activePointer, 700);
//        if (this.button.input.pointerOver())
//        {
//            this.button.alpha = 1;
//        } else
//        {
//            this.button.alpha = 0.45;
//        }
        if (this.ready === true)
        {
            this.ready = false;
            this.video.destroy();
            $('#imgtop').addClass('hide');
             $('#imgbottom').removeClass('hide');
//            this.state.destroy();
            this.state.start('Game');
//            this.state.start('Game', true, true);
        }
    }
};
