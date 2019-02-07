var Technotip = {};

Technotip.Boot = function (game) {

};

Technotip.Boot.prototype = {
    preload: function () {
        this.load.image('preloadbar', 'js/game/recurso_juego/imagenes/bar.png');
        this.load.spritesheet('backgroundcarga', 'js/game/recurso_juego/imagenes/backgroundcarga.png');
    },
    create: function () {

        this.scale.scaleMode = Phaser.ScaleManager.SHOW_ALL;
        this.game.physics.startSystem(Phaser.Physics.ARCADE);
        this.stage.backgroundColor = "#0075bc";
//        this.game.add.plugin(PhaserInput.Plugin);//input
//        this.game.add.plugin(PhaserNineSlice.Plugin);//input
        
        this.state.start('Preloader');
    },
    update: function () {

    }
};

