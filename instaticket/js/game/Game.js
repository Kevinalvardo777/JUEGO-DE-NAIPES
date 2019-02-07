
var count = 0;
var text = "";
var lugar = 0;
var ltemp = 0;
Technotip.reg = {};

Technotip.Game = function (game) {
//    this.error;
    this.opcion = 0;
    this.endgame = false;
    this.play = false;
    this.ejecution = true;
    this.win = false;
    this.doorSelect = null;
    this.openDoor = false;
    this.objectDoor = [];
    this.doorani = null;
    this.signo = null;
    this.regalo;
    this.ganador = false;


    this.front_emitter;
    this.mid_emitter;
    this.back_emitter;
    this.mid_emitter_g;


    this.tween;
//    localStorage.setItem("opcion", 0);
};

Technotip.Game.prototype = {
    preload: function () {

        var dataJuego = JSON.parse(sessionStorage.getItem("dataJuego"));
        var dataPremios = JSON.parse(sessionStorage.getItem("dataPremios"));
        var sizeRamdon = dataPremios.length;
        var iR = ram(sizeRamdon);
        var iR2;


        text = "Has Perdido";

        if (dataJuego) {
            if (dataJuego.ganador > 0 && dataJuego.premioGanador) {
                this.ganador = true;
                text = "Has Ganado";
                var img = dataJuego.premioGanador[0].fotoPremio;
                if (sizeRamdon == 1) {
                    this.load.image("relleno", img);
                } else {
                    do
                        iR2 = ram(sizeRamdon);
                    while (dataPremios[iR2 - 1]['fotoPremio'] === img);

                    this.load.image("relleno", dataPremios[iR2 - 1]['fotoPremio']);
                }
                this.load.image("ganador", img);
                this.load.image("perder", 'js/game/recurso_juego/imagenes/lose.png');
                // sessionStorage.setItem("codigoPremio",dataJuego.codigoPremio);
            } else {
                if (sizeRamdon == 1) {
                    this.load.image("relleno", dataPremios[0]['fotoPremio']);
                    this.load.image("perder", dataPremios[0]['fotoPremio']);
                } else {

                    do
                        iR2 = ram(sizeRamdon);
                    while (iR === iR2);

                    this.load.image("relleno", dataPremios[iR2 - 1]['fotoPremio']);
                    this.load.image("perder", dataPremios[iR - 1]['fotoPremio']);
                }
                this.ganador = false;

            }
        } else {
            this.ganador = false;
        }
    },
    create: function () {
//        this.error = this.cache.getFrame('error');

        this.background = this.add.image(0, 0, "FONDO_JUEGO");
        this.background.height = this.world.height;
        this.background.width = this.world.width;



        this.backgroundBack = this.add.image(0, 0, "fondoGanarBack");
        this.backgroundBack.height = this.world.height;
        this.backgroundBack.width = this.world.width;
        this.backgroundBack.alpha = 0;

        this.backgroundFront = this.add.image(0, 0, "fondoGanarFront");
        this.backgroundFront.height = this.world.height;
        this.backgroundFront.width = this.world.width;
        this.backgroundFront.alpha = 0;

        this.personaje = this.add.image(this.world.width / 1.7, (this.world.height / 1.5) / 6, "personaje");
        this.personaje.height = this.world.height / 1.5;
        this.personaje.width = this.world.width / 4.75;
        this.personaje.angle = 15;
        this.personaje.alpha = 0;

        this.wow = this.add.image(this.world.width / 4.5, (this.world.height / 1.5) / 10, "wowomg");
        this.wow.height = this.world.height / 3;
        this.wow.width = this.world.width / 3;
        this.wow.alpha = 0;

        this.yeah = this.add.image(this.world.width / 1.4, (this.world.height / 1.5) / 1.5, "yeah");
        this.yeah.height = this.world.height / 3;
        this.yeah.width = this.world.width / 6;
        this.yeah.alpha = 0;

//        this.background.inputEnabled = true;
//        this.background.input.useHandCursor = false;

        this.plataformas = this.add.group();//declara como un grupo de puertas
//        this.plataformas.enableBody = true;//crea cuerpo para fisica
        this.plataforma = this.plataformas.create((this.world.width - (this.world.width / 6) * 4) / 2, this.world.height - (this.world.height / 3), 'BASE_JUEGO');
        this.plataforma.width = (this.world.width / 6) * 4;
        this.plataforma.height = this.world.height / 10;
//        this.plataforma.body.immovable = true;


        this.plataformasInvisibles = this.add.group();//declara como un grupo de puertas
        this.plataformasInvisibles.enableBody = true;//crea cuerpo para fisica
        this.plataformasInvisible = this.plataformasInvisibles.create((this.world.width - (this.world.width / 6) * 4) / 2, this.world.height - (this.world.height / 3.5), 'default');
        this.plataformasInvisible.width = (this.world.width / 6) * 4;
        this.plataformasInvisible.height = this.world.height / 10;
        this.plataformasInvisible.body.immovable = true;


        this.gift1 = this.add.sprite(0, 0, 'default');
        this.ganaste1 = this.add.sprite(0, 0, 'ganaste');
        this.ganaste1.alpha = 0;
        this.door1 = this.add.sprite(this.world.width / 6 * 0.75 + (this.world.width / 16), (this.world.height / 5.5) + 0 * 3,
                'PRIMERA_PUERTA');
        this.door1.height = this.world.height / 2;
        this.door1.width = this.world.width / 8;
        this.game.physics.enable(this.door1, Phaser.Physics.ARCADE);
        this.door1.body.bounce.y = 0.4;
        this.door1.body.gravity.y = 500;
        this.ani1 = this.door1.animations.add('open', [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10], 8);

        this.door1.inputEnabled = true;
        this.door1.input.useHandCursor = true;
        this.door1.events.onInputDown.add(this.tint, this);

        this.ganaste1.width = (this.world.width / 3.2);
        this.ganaste1.height = (this.world.width / 8) - (this.world.width / 6) * 0.2;
        this.ganaste1.alignIn(this.door1, Phaser.TOP_CENTER, 0, 20);

        this.gift1.width = (this.world.width / 8) - (this.world.width / 6) * 0.2;
        this.gift1.height = (this.world.width / 8) - (this.world.width / 6) * 0.2;
        this.gift1.alignIn(this.door1, Phaser.CENTER, 0, -10);

        this.objectDoor.push({door: this.door1, gift: this.gift1, signo: this.ganaste1, accion: 0, key: "number", ani: this.ani1});

        this.gift2 = this.add.sprite(0, 0, 'default');
        this.ganaste2 = this.add.sprite(0, 0, 'ganaste');
        this.ganaste2.alpha = 0;
        this.door2 = this.add.sprite(this.world.width / 6 * 2.25 + (this.world.width / 16), (this.world.height / 5.5) + 1 * 3,
                'SEGUNDA_PUERTA');
        this.door2.height = this.world.height / 2;
        this.door2.width = this.world.width / 8;
        this.game.physics.enable(this.door2, Phaser.Physics.ARCADE);
        this.door2.body.bounce.y = 0.4;
        this.door2.body.gravity.y = 500;
        this.ani2 = this.door2.animations.add('open', [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10], 8);
        this.door2.inputEnabled = true;
        this.door2.input.useHandCursor = true;
        this.door2.events.onInputDown.add(this.tint, this);

        this.ganaste2.width = (this.world.width / 3.2);
        this.ganaste2.height = (this.world.width / 8) - (this.world.width / 6) * 0.2;
        this.ganaste2.alignIn(this.door2, Phaser.TOP_CENTER, 0, 20);

        this.gift2.width = (this.world.width / 8) - (this.world.width / 6) * 0.2;
        this.gift2.height = (this.world.width / 8) - (this.world.width / 6) * 0.2;
        this.gift2.alignIn(this.door2, Phaser.CENTER, 0, -10);

        this.objectDoor.push({door: this.door2, gift: this.gift2, signo: this.ganaste2, accion: 0, key: "number", ani: this.ani2});

        this.gift3 = this.add.sprite(0, 0, 'default');
        this.ganaste3 = this.add.sprite(0, 0, 'ganaste');
        this.ganaste3.alpha = 0;
        this.door3 = this.add.sprite(this.world.width / 6 * 3.75 + (this.world.width / 16), (this.world.height / 5.5) + 2 * 3,
                'TERCERA_PUERTA');
        this.door3.height = this.world.height / 2;
        this.door3.width = this.world.width / 8;
        this.game.physics.enable(this.door3, Phaser.Physics.ARCADE);
        this.door3.body.bounce.y = 0.4;
        this.door3.body.gravity.y = 500;
        this.ani3 = this.door3.animations.add('open', [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10], 8);
        this.door3.inputEnabled = true;
        this.door3.input.useHandCursor = true;
        this.door3.events.onInputDown.add(this.tint, this);

        this.ganaste3.width = (this.world.width / 3.2);
        this.ganaste3.height = (this.world.width / 8) - (this.world.width / 6) * 0.2;
        this.ganaste3.alignIn(this.door3, Phaser.TOP_CENTER, 0, 20);

        this.gift3.width = (this.world.width / 8) - (this.world.width / 6) * 0.2;
        this.gift3.height = (this.world.width / 8) - (this.world.width / 6) * 0.2;
        this.gift3.alignIn(this.door3, Phaser.CENTER, 0, -10);

        this.objectDoor.push({door: this.door3, gift: this.gift3, signo: this.ganaste3, accion: 0, key: "number", ani: this.ani3});

        //this.reflector = this.add.sprite(this.world.width / 6 * 2.25 + (this.world.width / 16), (this.world.height / 6) + 0 * 3, 'reflector');
        this.reflector = this.add.sprite(-300, (this.world.height / 6) + 0 * 3, 'reflector');
        this.reflector.height = this.world.height / 2.2;
        this.reflector.width = this.world.width / 8;

        this.btnSelect = this.add.image((this.world.width - (this.world.width / 5.5)) / 2, this.plataforma.height / 1.5, "BOTON_JUEGO");
        this.btnSelect.height = (this.world.height / 5.5) / 2.5;
        this.btnSelect.width = this.world.width / 5.5;
        this.btnSelect.inputEnabled = true;
        this.btnSelect.input.useHandCursor = true;
        this.btnSelect.events.onInputDown.add(this.opennig, this);


        this.cursor = this.game.add.sprite(this.world.width / 90, this.world.width / 90, 'mouse');
        this.cursor.height = this.world.width / 55;
        this.cursor.width = this.world.width / 55;
        //  Enable Arcade Physics for the sprite
        this.game.physics.enable(this.cursor, Phaser.Physics.ARCADE);




        this.back_emitter = this.game.add.emitter(this.world.centerX, -32, 600);
        this.back_emitter.makeParticles('particulaRed');
        this.back_emitter.maxParticleScale = 1.3;
        this.back_emitter.minParticleScale = 1.7;
        this.back_emitter.setYSpeed(800, 1000);
        this.back_emitter.gravity = 0;
        this.back_emitter.width = this.world.width * 1.5;
        this.back_emitter.minRotation = 0;
        this.back_emitter.maxRotation = 40;

        this.mid_emitter = this.game.add.emitter(this.world.centerX, -32, 250);
        this.mid_emitter.makeParticles('particulaBlue');
        this.mid_emitter.maxParticleScale = 1.6;
        this.mid_emitter.minParticleScale = 1.8;
        this.mid_emitter.setYSpeed(800, 1000);
        this.mid_emitter.gravity = 0;
        this.mid_emitter.width = this.world.width * 1.5;
        this.mid_emitter.minRotation = 0;
        this.mid_emitter.maxRotation = 40;

        this.mid_emitter_g = this.game.add.emitter(this.world.centerX, -32, 450);
        this.mid_emitter_g.makeParticles('particulaGreen');
        this.mid_emitter_g.maxParticleScale = 1.4;
        this.mid_emitter_g.minParticleScale = 2;
        this.mid_emitter_g.setYSpeed(800, 1000);
        this.mid_emitter_g.gravity = 0;
        this.mid_emitter_g.width = this.world.width * 1.5;
        this.mid_emitter_g.minRotation = 0;
        this.mid_emitter_g.maxRotation = 40;

        this.front_emitter = this.game.add.emitter(this.world.centerX, -32, 50);
        this.front_emitter.makeParticles('particulaYellow');
        this.front_emitter.maxParticleScale = 1.3;
        this.front_emitter.minParticleScale = 1.9;
        this.front_emitter.setYSpeed(800, 1000);
        this.front_emitter.gravity = 0;
        this.front_emitter.width = this.world.width * 1.5;
        this.front_emitter.minRotation = 0;
        this.front_emitter.maxRotation = 40;


        //	this.changeWindDirection();







        this.timer = this.time.create(false);
        this.songOpen = this.add.audio('songDoor');

        this.songPerder = this.add.audio('songPerder');
        this.songGanar = this.add.audio('songGanar');

        this.music = this.add.audio('SONIDO_JUEGO');
        this.music.loopFull(0.8);
    },
    update: function () {
        this.game.physics.arcade.moveToPointer(this.cursor, 50, this.game.input.activePointer, 700);


        if (this.opcion !== null && this.opcion !== 0) {
            if (this.opcion == 1) {
                if (count > 320) {
                    this.resetVariable();
                    window.location.href = "ganar.php";
                    //this.game.state.start("GameTitle", true, false);
                } else {
                    count++;
                }
            }
            if (this.opcion == 2) {
                if (count > 320) {
                    this.resetVariable();
                    window.location.href = "perder.php";
                    // this.game.state.start("WinGame", true, false);
                } else {
                    count++;
                }
            }
        }
        this.physics.arcade.collide(this.door1, this.plataformasInvisibles);
        this.physics.arcade.collide(this.door2, this.plataformasInvisibles);
        this.physics.arcade.collide(this.door3, this.plataformasInvisibles);
        if (this.door1 == this.doorSelect && this.play) {
            this.game.tweens.remove(this.tween);
            this.tween = this.add.tween(this.reflector).to({x: this.world.width / 6 * 0.75 + (this.world.width / 16), y: (this.world.height / 6) + 0 * 3, key: 2}, 500, Phaser.Easing.Linear.None, true);
            this.tween.onComplete.addOnce(this.tween2, this);
            this.play = false;
        }
        if (this.door2 == this.doorSelect && this.play) {
            this.game.tweens.remove(this.tween);
            this.tween = this.add.tween(this.reflector).to({x: this.world.width / 6 * 2.25 + (this.world.width / 16), y: (this.world.height / 6) + 0 * 3, key: 2}, 500, Phaser.Easing.Linear.None, true);
            this.tween.onComplete.addOnce(this.tween2, this);
            this.play = false;
        }
        if (this.door3 == this.doorSelect && this.play) {
            this.game.tweens.remove(this.tween);
            this.tween = this.add.tween(this.reflector).to({x: this.world.width / 6 * 3.75 + (this.world.width / 16), y: (this.world.height / 6) + 0 * 3, key: 2}, 500, Phaser.Easing.Linear.None, true);

            this.tween.onComplete.addOnce(this.tween2, this);


            this.play = false;
        }




        if (this.doorSelect !== null && this.endgame && this.opcion !== 2 && this.opcion !== 1) {
            if (this.openDoor === true) {
                lugar = Math.floor(Math.random() * (2 - 1)) + 1;
                for (var i = 0; i < this.objectDoor.length; i++) {
                    if (this.doorSelect === this.objectDoor[i]['door']) {
                        if (this.ganador) {
                            this.changeTexture(this.objectDoor[i]['gift'], this.make.sprite(0, 0, 'ganador'));
                            this.songGanar.play();
                            this.back_emitter.start(false, 16000, 5);
                            this.mid_emitter.start(false, 16000, 50);
                            this.mid_emitter_g.start(false, 16000, 5);
                            this.front_emitter.start(false, 16000, 5);

                            this.tweenE = this.add.tween(this.backgroundBack).to({alpha: 1}, 1500, "Linear", true, 2000);
                            this.tweenE = this.add.tween(this.personaje).to({alpha: 1}, 1500, "Linear", true, 2000);
                            this.tweenE = this.add.tween(this.backgroundFront).to({alpha: 1}, 1500, "Linear", true, 2000);

                            this.tweenE = this.add.tween(this.wow).to({alpha: 1}, 1500, "Linear", true, 2000);
                            this.tweenE = this.add.tween(this.yeah).to({alpha: 1}, 1500, "Linear", true, 2000);




                        } else {
                            this.changeTexture(this.objectDoor[i]['gift'], this.make.sprite(0, 0, 'lose'));
                            this.songPerder.play();
                        }
                        this.doorani = this.objectDoor[i]['ani'];
                        this.regalo = this.objectDoor[i]['gift'];
                        this.signo = this.objectDoor[i]['signo'];
                    } else {
                        ltemp++;
                        if ((lugar) == ltemp) {
                            this.changeTexture(this.objectDoor[i]['gift'], this.make.sprite(0, 0, 'perder'));
                        } else {
                            this.changeTexture(this.objectDoor[i]['gift'], this.make.sprite(0, 0, 'relleno'));
                        }
                    }

                    if (this.objectDoor[i]['accion'] === 0) {
                        this.songOpen.play();
                        this.objectDoor[i]['door'].animations.play('open');
                        this.objectDoor[i]['accion'] = 1;
                    }

                }
                this.openDoor = false;
            }
            if (this.doorani !== null) {
                if (this.doorani.isFinished && this.ejecution) {
                    this.openWindow(this.regalo, this.doorani, this.signo);
                    this.ejecution = false;
                    this.doorani = null;
                }
            }
        }



    },
    tint: function (sprite) {
        if (this.endgame === false) {
            this.doorSelect = sprite;
            this.play = true;
        }
    },
    opennig: function (door) {
        if (this.doorSelect !== null) {
            this.openDoor = true;
            this.endgame = true;
        }
    },
    changeTexture: function (sprite, overlap) {
        var bmd = this.make.bitmapData(800, 800);
//        bmd.draw('number');
        if (overlap) {
            bmd.draw(overlap);
        }
        sprite.setTexture(bmd.texture);

    },
    openWindow: function (sprite, door, signo) {
//        if(this.endgame === false){

        if (this.ganador) {
            this.tweenitem = this.add.tween(sprite).to({height: (this.world.width / 8) * 1.8, width: (this.world.width / 8) * 1.8}, 1000, Phaser.Easing.Linear.None, true);
            this.tweenitem = this.add.tween(sprite.position).to({x: this.world.width / 6 * 1.35 + (this.world.width / 16), y: sprite.position.y + sprite.width / 2.5}, 1000, Phaser.Easing.Linear.None, true, 0, 0, false);
        } else {
            //this.tweenitem = this.add.tween(sprite).to({height: (this.world.width / 8) * 1.8, width: (this.world.width / 8) * 1.8}, 1000, Phaser.Easing.Linear.None, true);
            this.tweenitem = this.add.tween(sprite).to({x: sprite.position.x - sprite.width / 2, y: sprite.position.y - sprite.width / 6.75, height: (this.world.width / 8) * 1.8, width: (this.world.width / 8) * 1.8}, 1500, Phaser.Easing.Linear.None, true, 0, 0, false);
        }

        // this.tweenitem = this.add.tween(sprite).to({height: this.world.height, width: (this.world.width / 2) - (this.world.width / 6) * 0.2}, 1000, Phaser.Easing.Linear.None, true);
        //this.tweenitem = this.add.tween(sprite).to({x: door.x/2, y: door.y/2, key: 2}, 1000, Phaser.Easing.Linear.None, true);
//        this.tweenModal = this.add.tween(this.modal).to({alpha: 1}, 200, "Linear", true, 200);
//        this.tweenModal.onComplete.add(this.completed, this);
        this.showModal1(sprite, signo);
//        }
    },
    completed: function () {
        this.text.text = "You Lose";
    },
    showModal1: function (sprite, signo) {
        // Technotip.reg.modal.showModal("modal3");

        this.world.bringToTop(this.backgroundBack);
        this.world.bringToTop(this.personaje);
        this.world.bringToTop(this.backgroundFront);
        this.world.bringToTop(this.wow);
        this.world.bringToTop(this.yeah);
        this.world.bringToTop(signo);
        this.world.bringToTop(sprite);




        this.world.bringToTop(this.back_emitter);
        this.world.bringToTop(this.mid_emitter);
        this.world.bringToTop(this.mid_emitter_g);
        this.world.bringToTop(this.front_emitter);



        if (this.ganador) {
            this.opcion = 1;
            this.tweenitem = this.add.tween(signo).to({alpha: 1}, 500, "Linear", true, 100);
            this.tweenitem = this.add.tween(signo).to({x: this.world.width / 6 * 0.90 + (this.world.width / 16), y: sprite.position.y - sprite.width / 6.75}, 1000, Phaser.Easing.Linear.None, true, 0, 0, false);



            this.tweenPersonaje = this.add.tween(this.personaje).to({angle: 12}, 300, "Linear", true, 0, -1);
            this.tweenPersonaje.yoyo(true, 400);

            this.tweenWow = this.add.tween(this.wow).to({angle: 1}, 300, "Linear", true, 0, -1);
            this.tweenWow.yoyo(true, 400);

            this.tweenYeah = this.add.tween(this.yeah).to({angle: -1}, 300, "Linear", true, 0, -1);
            this.tweenYeah.yoyo(true, 400);

        } else {
            this.opcion = 2;
        }
    },
    resetVariable: function () {
        this.opcion = 0;
        count = 0;
        // localStorage.setItem("opcion", 0);
        this.endgame = false;
        this.play = false;
        this.ejecution = true;
        this.win = false;
        this.doorSelect = null;
        this.openDoor = false;
        this.objectDoor = [];
        this.doorani = null;
        this.regalo;
        this.signo = null;
        this.music.destroy();
        this.songOpen.destroy();
    },
    tween2: function () {

        this.tween.to({x: this.reflector.x - 20}, 1500, Phaser.Easing.Linear.None, true);
        this.tween.onComplete.addOnce(this.tween3, this);
    },
    tween3: function () {

        this.tween.to({x: this.reflector.x + 40}, 1500, Phaser.Easing.Linear.None, true);
        this.tween.onComplete.addOnce(this.tween2, this);
    }

};





function ram(max,min=1) {
    return Math.floor(Math.random() * (max - min + 1)) + min;
}


