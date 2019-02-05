var game;
var gameOptions = {
    gameWidth: 2950,
    gameHeight: 1334,
    cardSheetWidth: 334,
    cardSheetHeight: 440,
    cardScale: 0.8, //cuando se usen las imagenes del diseñador esto cambia a 1.0
    flipZoom: 1.2,
    flipSpeed: 1000

}
var gameGlobal = {
    playerScore: 0,
    machineScore: 0,
    turno: 0, 
    partidas: 3,
    ganador: false
}
var cartasJugadas = [];
var cartasLimites = [0,12,13,25,26,38,39,51];

var swipeUp;


window.onload = function() {
    game = new Phaser.Game(gameOptions.gameWidth, gameOptions.gameHeight);
    game.state.add("PlayGame", playGame)
    game.state.start("PlayGame");
}
var playGame = function(game) {}
playGame.prototype = {
    preload: function() {
        var mesaF= game.load.spritesheet("background", "mesafondo-01.png", 2040, 1080);
        
        for(var i = 0; i < 10; i++){
            game.load.spritesheet("cards" + i, "cards" + i + ".png", gameOptions.cardSheetWidth, gameOptions.cardSheetHeight);
        }
        //game.load.spritesheet("cards9", "spritePrueba.png", 238, 0);
        
        game.load.image("fondoCarta", "carta_imajen_fondo.png");
        game.load.image("baraja", "baraja-poker-01.png");
        game.load.spritesheet("mano", "Mano-01.png", 500, 500);
        game.load.spritesheet("ruleta", "Ruleta-01.png", 300, 550);
        game.load.spritesheet("indicaciones", "indicaciones1.png", 500, 184);
        game.load.spritesheet("indicaciones", "indicaciones1.png", 500, 184);
        game.load.image("barraLinea", "Barra_linea_celeste.png");
        game.load.image("barraLineaV", "Barra_linea_verde.png");
        game.load.image("masCeleste", "Barra_mas_celeste.png");
        game.load.image("masCelesteClaro", "Barra_mas_celesteclaro.png");
        game.load.image("masVerde", "Barra_mas_verde.png");
        game.load.image("masVerdeClaro", "Barra_mas_verdeclaro.png");
        game.load.spritesheet("masCelesteSheet", "mas_Celeste.png", 48,0)
        game.load.spritesheet("prueba", "Prueba.png", 158,0)
        game.load.spritesheet("cartaFiloVerde", "brillo_verde_cartafilo.png", 9805 / 28,0)
        

        game.load.spritesheet("barraCelesteSheet", "Barra_Celeste_Sprite.png", 182,0)
        game.load.spritesheet("barraVerdeSheet", "Barra_Verde_Sprite.png", 182,0)

        game.load.image("masCelesteBrillo", "Barra_mas_brillo_celeste.png");
        
        game.load.image("boton", "boton_celeste.png");
        game.load.image("botonV", "boton_verde.png");
        game.load.spritesheet("swipe", "swipe.png", 80, 130);
        game.scale.scaleMode = Phaser.ScaleManager.SHOW_ALL;
        game.scale.pageAlignHorizontally = true;
        game.scale.pageAlignVertically = true;
        game.load.spritesheet("tuPuntaje", "fichas partes-01.png", 250, 167);
        game.load.spritesheet("puntajeMaquina", "fichas partes-06.png", 250, 167);
        game.load.spritesheet("cuadroUsuarioSheet", "cuadro_usuario-01.png", 1400,175 );
        game.load.spritesheet("avatarU", "Avatars-01.png", 85, 90);
        game.load.spritesheet("avatarD", "Avatars-02.png", 85, 87);
        game.load.spritesheet("publicidad", "Publicidad_barra _inferior-01.png");
        game.load.spritesheet("logoJuego", "logo -01.png");
        game.load.spritesheet("button3", "button3.png", 302, 200);
        game.load.spritesheet("button5", "button5.png", 302, 200);
        this.cartaMaquina = []
        this.cartaJugador = []
    },

    create: function() {
        mesaF1= game.add.tileSprite(0, 0, Math.floor(gameOptions.gameWidth/2) *3.5, Math.floor(gameOptions.gameHeight/2) *2, 'background');
        mesaF1.scale.setTo(1.44,1.3);

        if (gameGlobal.turno == 0) {
            barajaSprite = game.add.sprite(game.width * 1/8.2, 0, "baraja");
            barajaSprite.y = barajaSprite.height * -2;

            var tweenBaraja = game.add.tween(barajaSprite).to({
                y: game.height / 3.4
            }, 1000, Phaser.Easing.Cubic.Out, true);

        } else {
            barajaSprite = game.add.sprite(game.width * 1/8.2, game.height * 1/3.4, "baraja");
        }

        
        if(gameGlobal.turno== 0){
            manoSprite= game.add.sprite(game.width* 1/7.2, 0, "mano");
            manoSprite.y= manoSprite.height * -2;

            var tweenMano= game.add.tween(manoSprite).to({
                y: game.height/19.5
            }, 2500, Phaser.Easing.Cubic.Out, true);
        } else {
            manoSprite= game.add.sprite(game.width * 1/7.2, game.height * 1/19.5, "mano");
        }

        //barraLineaV = game.add.sprite(game.width * 2/3, game.height * 1/4, "barraLineaV");
        //barraLinea = game.add.sprite(game.width * 2/3, game.height * 1/4, "barraLinea");
        //masCeleste = game.add.sprite(game.width * 2/3 + (barraLinea.width * 1/6), game.height * 1/4 + (barraLinea.height * 1/20), "masCeleste");
        //masCelesteClaro = game.add.sprite(game.width * 2/3 + (barraLinea.width * 1/6), game.height * 1/4 + (barraLinea.height * 1/20), "masCelesteClaro");
        

        //masCelesteBrillo = game.add.sprite(game.width * 2/3 - (barraLinea.width * 7/12), game.height * 1/4 - (barraLinea.height * 1/30), "masCelesteBrillo");
        //prueba = game.add.sprite(game.width * 2/3 + (barraLinea.width * 1/6), game.height * 1/4 + (barraLinea.height * 1/20), "masCelesteSheet");

        barraVerdeSheet = game.add.sprite(game.width * 2/3 , game.height * 1/6 , "barraVerdeSheet");
        var tween = game.add.tween(barraVerdeSheet).to({
        }, 1, Phaser.Easing.Linear.None, true).loop(true);

        tween.onLoop.add(function() {
            if (barraVerdeSheet.frame == 2) {
                barraVerdeSheet.frame = 0;
            }
            else {
                barraVerdeSheet.frame++;
            }
        }, this);
        /*
        cartaFiloVerde = game.add.sprite(game.width * 1/2 , game.height * 1/2 , "cartaFiloVerde");
        var tween = game.add.tween(cartaFiloVerde).to({
        }, 10, Phaser.Easing.Linear.None, true).loop(true);
        tween.onLoop.add(function() {
            if (cartaFiloVerde.frame == 27) {
                cartaFiloVerde.frame = 0;
            }
            else {
                cartaFiloVerde.frame++;
            }
        }, this);
        */
        barraCelesteSheet = game.add.sprite(game.width * 2/3 , game.height * 1/6 , "barraCelesteSheet");
        var tween = game.add.tween(barraCelesteSheet).to({
        }, 10, Phaser.Easing.Linear.None, true).loop(true);

        tween.onLoop.add(function() {
            if (barraCelesteSheet.frame == 2) {
                barraCelesteSheet.frame = 0;
            }
            else {
                barraCelesteSheet.frame++;
            }
        }, this);


        masCelesteSheet = game.add.sprite(game.width * 257/384, game.height * 9/48, "prueba");
        var tween = game.add.tween(masCelesteSheet).to({
        }, 5, Phaser.Easing.Linear.None, true).loop(true);

        
        tween.onLoop.add(function() {
            if (masCelesteSheet.frame == 2) {
                masCelesteSheet.frame = 0;
            }
            else {
                masCelesteSheet.frame++;
            }
        }, this);



        botonV = game.add.sprite((game.width * 255/384) , game.height * 3/8, "botonV");
        boton = game.add.sprite((game.width * 255/384), game.height * 3/8, "boton");

        game.physics.enable(botonV, Phaser.Physics.ARCADE);
        
        puntajeM = game.add.sprite(game.width / 3.8, game.height / 2006, "puntajeMaquina");
        puntajeM.scale.y *= -1;
        puntajeM.scale.setTo(1.35, 1.35);
        puntajeJ = game.add.sprite(game.width - (game.width / 6) - (puntajeM.width), game.height / 2006, "tuPuntaje");
        puntajeJ.scale.setTo(-1.35,1.35);

        cuadroUsuarioIzq= game.add.sprite(game.width / 15, game.height/2006, "cuadroUsuarioSheet");
        cuadroUsuarioIzq.scale.setTo(1,1.4);

        cuadroUsuarioDer= game.add.sprite(1.05*game.width - (game.width / 50) - (puntajeM.width), game.height / 2006, "cuadroUsuarioSheet");
        cuadroUsuarioDer.scale.setTo(1,1.4);
        cuadroUsuarioDer.scale.x *= -1;

        avatarIzq= game.add.sprite(game.width/ 12, game.height/37, "avatarU");
        avatarIzq.scale.setTo(2, 2);

        avatarDer= game.add.sprite(1.03*game.width - (game.width / 50) - (puntajeM.width), game.height / 37, "avatarD");
        avatarDer.scale.setTo(2,2);
        avatarDer.scale.x *= -1;
        /*avatarDer.angle = -360;*/

        ruleta= game.add.sprite( 1.03*game.width - (game.width / 50) - (puntajeM.width), game.height/3.6, "ruleta");
        ruleta.scale.setTo(1.2,1.2);

        publicidad= game.add.sprite(game.width/1000, game.height/1.129, "publicidad");
        publicidad.scale.setTo(1.44,1.38);

        logo= game.add.sprite(game.width/ 2.35, game.height/250, "logoJuego");
        logo.scale.setTo(0.5, 0.5);

        if (this.check()) {            
            this.finalizar(this);          
        }
        console.log(gameGlobal.turno)

        var button3= game.add.button(game.width * 3/8, game.height * 1/2, 'button3');
        button3.scale.setTo(0.5,0.5);
        button3.inputEnabled= true;

        var button5= game.add.button(game.width * 3/5.3, game.height * 1/2, 'button5');
        button5.scale.setTo(0.5,0.5);
        button5.inputEnabled= true;
        
        var mensaje3= game.add.text(game.width * 3/14, game.height /5, 'Usted tiene 3 intentos', { fontSize: '50px', fill: '#000' });
        var mensaje5= game.add.text(game.width * 3/14, game.height /5, 'Usted tiene 5 intentos', { fontSize: '50px', fill: '#000' });
        mensaje3.visible= false;
        mensaje5.visible= false;
        

        this.playerScoreText = game.add.text(game.width * 4/6.32, game.height * 5 / 140, '' + gameGlobal.playerScore, { fontFamily: 'courier', fontSize: '110px', fill: '#FFFFFF' });
        this.machineScoreText = game.add.text((game.width / 3.66) + puntajeM.width/2, game.height * 5 / 140, '' + gameGlobal.machineScore, { fontFamily: 'monospace',fontSize: '110px', fill: '#FFFFFF' });

        this.playerText = game.add.text(game.width * 4/5.1, game.height * 5 / 100, '' + "Tu", { fontSize: '110px', fill: '#cc0066' });
        this.machineText = game.add.text((game.width / 12) + puntajeM.width/2, game.height * 5 / 100, '' + "Comp", { fontSize: '100px', fill: '#cc0066' });
        


        for (var i = 0; i < gameGlobal.turno; i++) {
            cartaTemporal = cartasJugadas[cartasJugadas.length-(2 * (i+1))]
            this.cartaMaquina[i] = game.add.sprite(game.width / 12, game.height * 4/ 5, "cards0");
            this.cartaMaquina[i].anchor.set(0.6);
            this.cartaMaquina[i].scale.set(gameOptions.cardScale);
            this.cartaMaquina[i].loadTexture("cards" + this.getCardTexture(cartaTemporal));
            this.cartaMaquina[i].frame = this.getCardFrame(cartaTemporal);  

            this.cartaMaquina[i].x = (game.width / 12)-(30*i) + ((i) * this.cartaMaquina[i].width * 0.55) 
            this.cartaMaquina[i].y = (game.height * 4/5.5)+(35*i) + ((i) * this.cartaMaquina[i].height/90)
            
                                  
            
            this.cartaMaquina[i].angle += -40 + (i * this.cartaMaquina[i].width * 0.08);

            cartaTemporal = cartasJugadas[cartasJugadas.length- (1 + (i*2))]
            console.log("Carta del jugador Temporal: "+ cartaTemporal + ", " + cartaTemporal%13)
            this.cartaJugador[i] = game.add.sprite(game.width - (game.width / 12), game.height * 4/ 5, "cards0");
            this.cartaJugador[i].anchor.set(0.65);
            this.cartaJugador[i].scale.set(gameOptions.cardScale);
            this.cartaJugador[i].loadTexture("cards" + this.getCardTexture(cartaTemporal));
            this.cartaJugador[i].frame = this.getCardFrame(cartaTemporal);
            this.cartaJugador[i].x = game.width - (game.width / 13)+(32*i) - (i * this.cartaJugador[i].width * 0.55)
            this.cartaJugador[i].y = (game.height * 4/ 5.5)+(35*i) + (i * this.cartaJugador[i].height/95)
            
            
            this.cartaJugador[i].angle += 40 - (i * this.cartaJugador[i].width * 0.08);
        }
        

        var textoTusNaipes = "TUS NAIPES";
        var style = {font: "40px Arial", fill: "#ffffff", align: "center"};

        var textoNaipesBaraja = "NAIPES DE BARAJA";
        
        game.add.text(game.width * 4/4.5, game.height / 2, textoTusNaipes, style);
        game.add.text(game.width / 50, game.height / 2, textoNaipesBaraja, style);
        

        this.infoGroup = game.add.group();
        this.infoGroup.visible = false;
        this.deck = Phaser.ArrayUtils.numberArray(0, 51);
        
        Phaser.ArrayUtils.shuffle(this.deck);
//Eligiendo cartas que saldran
        this.cardsInGame = [this.makeCard(0), this.makeCard(1)];                
        
        this.nextCardIndex = 2;


        
        var tween = this.flipCard(this, this.cardsInGame[0])
        

        tween.onComplete.add(function(){
            this.infoGroup.visible = true;
        }, this)
        var infoUp = game.add.sprite(game.width * 7 / 12, game.height / 4.2, "indicaciones");
        infoUp.anchor.set(0.4);
        this.infoGroup.add(infoUp);
        var infoDown = game.add.sprite(game.width * 7 / 9.03, game.height * 7 / 10.8, "indicaciones");
        infoDown.anchor.set(0.4);
        infoDown.frame = 1;
        this.infoGroup.add(infoDown);
        swipeUp = game.add.sprite(game.width / 1.3, game.height / 2 - gameOptions.cardSheetHeight / 4 - 20, "swipe");
        var swipeUpTween = game.add.tween(swipeUp).to({
            y: swipeUp.y - swipeUp.height*2
        }, 1000, Phaser.Easing.Linear.None, true, 0, -1);     
        swipeUp.anchor.set(0.5);   
        this.infoGroup.add(swipeUp);
        var swipeDown = game.add.sprite(game.width / 1.55, game.height / 2 + gameOptions.cardSheetHeight / 4 + 20, "swipe");
        swipeDown.angle = -180;
        swipeDown.frame = 1;
        var swipeDownTween = game.add.tween(swipeDown).to({
            y: swipeDown.y + swipeUp.height*2
        }, 1000, Phaser.Easing.Linear.None, true, 0, -1);     
        swipeDown.anchor.set(0.5); 
        this.infoGroup.add(swipeDown);
        game.input.onDown.add(this.beginSwipe, this);
        

//Accion boton 3
        button3.events.onInputDown.add(function () { 
            mensaje5.visible= false;
            mensaje3.visible= true;

            gameGlobal.partidas = 3;
        });

//Accion boton 5
        button5.events.onInputDown.add(function () { 
            mensaje5.visible= true;
            mensaje3.visible= false;

            gameGlobal.partidas = 5;
        });
        console.log(cartasJugadas)
        gameGlobal.turno += 1;
        
    },

    makeCard: function(cardIndex) {
        var card = game.add.sprite((game.width * 1/6) + barajaSprite.width/2, (game.height * 1/12) + barajaSprite.height/2, "cards0");
        card.visible = false;
        card.anchor.set(0.5);
        card.scale.set(gameOptions.cardScale);
        //card.angle += 5;
        
        var carta = this.deck[cardIndex];        
        var comprobador = cardIndex;
        var ultima = cartasJugadas[cartasJugadas.length-1]

//Validacion de que no salga la misma carta
        while (cartasLimites.indexOf(carta) !== -1 || cartasJugadas.indexOf(carta) !== -1) {
            cardIndex += 2;
            carta = this.deck[cardIndex];
        }
//Cercania entre las cartas
        if (comprobador == 1) {
            cartaM = carta % 13
            ultimaM = ultima % 13
            if (Math.floor(Math.random() * 10) < 7) {
                while (cartaM < ultimaM - 3 || cartaM > ultimaM + 3 || cartaM == ultimaM || cartasJugadas.indexOf(carta) !== -1 || cartasLimites.indexOf(carta) !== -1) {
                    console.log("Cartas no cercanas " + ultimaM + ", " + cartaM)
                    cardIndex += 2;
                    carta = this.deck[cardIndex];
                    cartaM = carta % 13;
                }
            }
            
//Que pierda si ganador = false
/*
            if (gameGlobal.turno == gameGlobal.partidas-1) {
                if (gameGlobal.ganador) {                    
                        console.log("Aqui se comprueba")
                        cartaM = carta % 13
                        ultimaM = ultima % 13
                        while (cartaM <= ultimaM) {
                            console.log("Cartas no cercanas " + ultimaM + ", " + cartaM)
                            cardIndex += 2;
                            carta = this.deck[cardIndex];
                            cartaM = carta % 13;
                        }
                }
            }*/    
        }
        
        cartasJugadas.push(carta);
        console.log(cartasJugadas)
        card.loadTexture("cards" + this.getCardTexture(carta));
        card.frame = this.getCardFrame(carta);        
        return card;        
    },
    getCardTexture: function(cardValue){
        return Math.floor((cardValue % 13) / 3) + 5 * Math.floor(cardValue / 26);
    },
    getCardFrame: function(cardValue){
        return (cardValue % 13) % 3 + 3 * (Math.floor(cardValue / 13) % 2);
    },
    beginSwipe: function(e) {
        this.infoGroup.visible = false;
        game.input.onDown.remove(this.beginSwipe, this);
        game.input.onUp.add(this.endSwipe, this);
    },
    endSwipe: function(e) {     
        game.input.onUp.remove(this.endSwipe, this);
        var swipeTime = e.timeUp - e.timeDown;
        var swipeDistance = Phaser.Point.subtract(e.position, e.positionDown);
        var swipeMagnitude = swipeDistance.getMagnitude();
        var swipeNormal = Phaser.Point.normalize(swipeDistance);
        if(swipeMagnitude > 25 && swipeTime < 1000 && Math.abs(swipeNormal.y) > 0.8) {
            if(swipeNormal.y > 0.8) {
                barraCelesteSheet.visible= false;
                boton.visible= false;
                game.add.tween(botonV).to({
                    y: (game.height * 125/192)
                }, 1000, Phaser.Easing.Cubic.Out, true);
                if (gameGlobal.turno >= gameGlobal.partidas/2) {        //Si esta en el ultimo turno
                    console.log("Entro a menor")
                    var cartaSalvavidas = this.makeWinnerCard(1, false);
                    this.cardsInGame = [this.cardsInGame[0], cartaSalvavidas];              
                }
                console.log("Se llama a handleSwipe(1)")
                this.handleSwipe(1);
            }
            if(swipeNormal.y < -0.8) {
                barraCelesteSheet.visible= false;
                boton.visible= false;
                game.add.tween(botonV).to({
                    y: (game.height * 1 / 6)
                }, 1000, Phaser.Easing.Cubic.Out, true);
                if (gameGlobal.turno >= gameGlobal.partidas/2) {        //Si esta en el ultimo turno
                    console.log("Entro a mayor")
                    var cartaSalvavidas = this.makeWinnerCard(1, true);
                    this.cardsInGame = [this.cardsInGame[0], cartaSalvavidas];
                }
                console.log("Se llama a handleSwipe(-1)")
                this.handleSwipe(-1);
            }
        } else {
            game.input.onDown.add(this.beginSwipe, this);
        }
    },
    handleSwipe: function(dir) {
        console.log("Empieza handleSwipe")
        var cardToMove = (this.nextCardIndex + 1) % 2;
        console.log("Carta a mover: "+cardToMove)
        this.cardsInGame[cardToMove].visible = true;
        this.cardsInGame[cardToMove].y += dir * gameOptions.cardSheetHeight * gameOptions.cardScale * 1.1;
        if (cardToMove == 1) {
            var tween = game.add.tween(this.cardsInGame[cardToMove]).to({
                y: game.height * 1 / 2,
                x: game.width / 2
            }, 500, Phaser.Easing.Cubic.Out, true); 
        }
        var tween = game.add.tween(this.cardsInGame[cardToMove]).to({
            y: game.height * 1 / 3,
            x: game.width / 2
        }, 500, Phaser.Easing.Cubic.Out, true);        
        tween.onComplete.add(function() {
            var newCard = cartasJugadas[cartasJugadas.length-1];
            var oldCard = cartasJugadas[cartasJugadas.length-2];
            console.log("direccion :" + dir)
            if(((dir == -1) && ((newCard % 13 > oldCard % 13) || ((newCard % 13 == oldCard % 13) && (newCard > oldCard))))
             || ((dir == 1) && ((newCard % 13 < oldCard % 13) || ((newCard % 13 == oldCard % 13) && (newCard < oldCard))))){
//Si acierta el jugador                
                this.addPlayerScore();
                console.log("Jugador gano")
                console.log(" Carta Jugador: " + newCard + " O " + newCard%13 + " Carta Maquina: " + oldCard+ " O " + oldCard%13)
                game.time.events.add(Phaser.Timer.SECOND, this.fadeCards, this);                
            }
            else{
//Si acierta la maquina
                this.addMachineScore();
                console.log("Maquina gano")
                console.log(" Carta Jugador: " + newCard + " O " + newCard%13 + " Carta Maquina: " + oldCard+ " O " + oldCard%13)
                game.time.events.add(Phaser.Timer.SECOND, this.fadeCards, this);
                   
            }
        }, this)
    },
    moveCards: function() {
        console.log("Empieza moveCards")
        var cardToMove = this.nextCardIndex % 2;
        var moveOutTween = game.add.tween(this.cardsInGame[cardToMove]).to({
            x: game.width + gameOptions.cardSheetWidth * gameOptions.cardScale
        }, 500, Phaser.Easing.Cubic.Out, true);
        cardToMove = (this.nextCardIndex + 1) % 2
        var moveDownTween = game.add.tween(this.cardsInGame[cardToMove]).to({
            y: game.height / 2
        }, 500, Phaser.Easing.Cubic.Out, true);
        moveDownTween.onComplete.add(function() {
            var cardToMove = this.nextCardIndex % 2
            this.cardsInGame[cardToMove].loadTexture("cards" + this.getCardTexture(this.deck[this.nextCardIndex]));
            this.cardsInGame[cardToMove].frame = this.getCardFrame(this.deck[this.nextCardIndex]);
            this.nextCardIndex = (this.nextCardIndex + 1) % 52;
            this.cardsInGame[cardToMove].x = gameOptions.cardSheetWidth * gameOptions.cardScale / -2;
            game.input.onDown.add(this.beginSwipe, this);
            this.infoGroup.visible = true;
        }, this)
    },

    fadeCards: function(){

        for (var i = 1; i < gameGlobal.turno; i++) {
            game.world.bringToTop(this.cartaMaquina[i-1]);
            game.world.bringToTop(this.cartaJugador[i-1]);
            var fadeMaquina = game.add.tween(this.cartaMaquina[i-1]).to({
                x: (game.width / 12) + (i * this.cartaMaquina[i-1].width * 0.6),
                y: (game.height * 4/ 5) + (i * this.cartaMaquina[i-1].height/16),
                angle: -45 + (i * this.cartaMaquina[i-1].width * 0.15)
            }, 700, Phaser.Easing.Cubic.Out, true);
            var fadeJugador = game.add.tween(this.cartaJugador[i-1]).to({
                x: game.width - (game.width / 12) - (i * this.cartaJugador[i-1].width * 0.6),
                y: (game.height * 4/ 5) + (i * this.cartaJugador[i-1].height/16),
                angle: +45 - (i * this.cartaJugador[i-1].width * 0.15)
            }, 700, Phaser.Easing.Cubic.Out, true); 
        }

        var tween = game.add.tween(this.cardsInGame[0]).to({
            x: game.width / 12,
            y: (game.height * 4/ 5),
            angle: -45
        }, 700, Phaser.Easing.Cubic.Out, true);  

        this.cardsInGame[0].scale.set(gameOptions.cardScale);
        var tween = game.add.tween(this.cardsInGame[1]).to({
            x: game.width - (game.width / 12),
            y: game.height * 4/ 5,
            angle: 45
        }, 700, Phaser.Easing.Cubic.Out, true);
        this.cardsInGame[1].scale.set(gameOptions.cardScale);
        
        
        game.time.events.add(Phaser.Timer.SECOND, function(){
            game.state.start("PlayGame");    
        }, this)  

    },


    addPlayerScore: function() {
        gameGlobal.playerScore += 1;

    },

    addMachineScore: function() {
        gameGlobal.machineScore += 1;
    },

    check: function() {
        if (gameGlobal.turno >= gameGlobal.partidas) {
                return true;
        }
        return false;
    },

    makeWinnerCard: function(cardIndex, choice) {
        var card = game.add.sprite((game.width * 1/6) + barajaSprite.width/2, (game.height * 1/12) + barajaSprite.height/2, "cards0");
        card.anchor.set(0.5);
        card.scale.set(gameOptions.cardScale);
        var carta = cartasJugadas[cartasJugadas.length-1];        
        var comprobador = cardIndex;
        var ultima = cartasJugadas[cartasJugadas.length-2]

//Cercania entre las cartas
/*
        if (comprobador == 1) {     //Si se esta sacando la segunda carta
            cartaM = carta % 13
            ultimaM = ultima % 13
            while (cartaM < ultimaM - 3 || cartaM > ultimaM + 3 || cartaM == ultimaM || cartasLimites.indexOf(carta) !== -1 || cartasJugadas.indexOf(carta) !== -1) {
                console.log("Cartas no cercanas " + ultimaM + ", " + cartaM)
                cardIndex += 2;
                carta = this.deck[cardIndex];
                cartaM = carta % 13;
            }
        } else {
//Validacion de que no salga la misma carta
            while (cartasLimites.indexOf(carta) !== -1 || cartasJugadas.indexOf(carta) !== -1) {
                cardIndex += 2;
                carta = this.deck[cardIndex];
            }        
        }*/
//Que pierda si ganador = false
console.log("validacion de ultimo turno")
        if (gameGlobal.turno == gameGlobal.partidas) {    //Si es el ultimo turno
            console.log("Ultimo turno")
            if (comprobador == 1) {                         //Si se esta sacando la segunda carta
                console.log("Ultima carta")
                if (gameGlobal.ganador) {                   //Si es ganador
                    console.log("Es ganador")
                    cartaM = carta % 13
                    ultimaM = ultima % 13
                    if (choice) {                           //Si escogio mayor
                        console.log("Escogio mayor")
                        while (cartaM <= ultimaM || cartasJugadas.indexOf(carta) !== -1) {
                            console.log("Cartas muy pequeña " + ultimaM + ", " + cartaM)
                            cardIndex += 2;
                            carta = this.deck[cardIndex];
                            cartaM = carta % 13;
                        }
                    } else {                                //Si escogio menor
                        console.log("Escogio menor")
                        while (cartaM >= ultimaM || cartasJugadas.indexOf(carta) !== -1) {
                            console.log("Cartas muy grande " + ultimaM + ", " + cartaM)
                            cardIndex += 2;
                            carta = this.deck[cardIndex];
                            cartaM = carta % 13;
                        }
                    }
                } else {
                    console.log("Es perdedor")
                    cartaM = carta % 13
                    ultimaM = ultima % 13
                    if (choice) {                           //Si escogio mayor
                        console.log("Escogio mayor")
                        while (cartaM >= ultimaM || cartasJugadas.indexOf(carta) !== -1) {
                            console.log("Cartas muy grande " + ultimaM + ", " + cartaM)
                            cardIndex += 2;
                            carta = this.deck[cardIndex];
                            cartaM = carta % 13;
                        }
                    } else {                                //Si escogio menor
                        console.log("Escogio menor")                        
                        while (cartaM <= ultimaM || cartasJugadas.indexOf(carta) !== -1) {
                            console.log("Cartas muy pequeña " + ultimaM + ", " + cartaM)
                            cardIndex += 2;
                            carta = this.deck[cardIndex];
                            cartaM = carta % 13;
                        }
                    }
                }
            }

            
            console.log("Carta Salvavidas" + carta % 13)
            cartasJugadas[cartasJugadas.length-1] = carta;
            console.log(cartasJugadas)
            card.loadTexture("cards" + this.getCardTexture(carta));
            card.frame = this.getCardFrame(carta);
            return card;  


        }

        //Para penultimo turno 
        console.log("validacion de penultimo turno")

        if (gameGlobal.turno == gameGlobal.partidas - 1) {    //Si es el ultimo turno
            console.log("penultimo turno")
            if (comprobador == 1) {                         //Si se esta sacando la segunda carta
                console.log("Ultima carta")
                cartaM = carta % 13
                ultimaM = ultima % 13
                if(gameGlobal.playerScore== 1){
                    if (choice) {                           //Si escogio mayor
                        console.log("Escogio mayor")
                        while (cartaM >= ultimaM || cartasJugadas.indexOf(carta) !== -1) {
                            console.log("Cartas muy pequeña " + ultimaM + ", " + cartaM)
                            cardIndex += 2;
                            carta = this.deck[cardIndex];
                            cartaM = carta % 13;
                        }
                    } else {                                //Si escogio menor
                        console.log("Escogio menor")
                        while (cartaM <= ultimaM || cartasJugadas.indexOf(carta) !== -1) {
                            console.log("Cartas muy grande " + ultimaM + ", " + cartaM)
                            cardIndex += 2;
                            carta = this.deck[cardIndex];
                            cartaM = carta % 13;
                        }
                    }
                
                } else {
                    if  (choice) {                           //Si escogio mayor
                        console.log("Escogio mayor")
                        while (cartaM <= ultimaM || cartasJugadas.indexOf(carta) !== -1) {
                            console.log("Cartas muy pequeña " + ultimaM + ", " + cartaM)
                            cardIndex += 2;
                            carta = this.deck[cardIndex];
                            cartaM = carta % 13;
                        }
                    } else {                                //Si escogio menor
                        console.log("Escogio menor")
                        while (cartaM >= ultimaM || cartasJugadas.indexOf(carta) !== -1) {
                            console.log("Cartas muy grande " + ultimaM + ", " + cartaM)
                            cardIndex += 2;
                            carta = this.deck[cardIndex];
                            cartaM = carta % 13;
                        }
                    }
                
                }
                
            }
        }

            /**** CONDICIONES BOTON 5 ***/

console.log("validacion de ultimo turno")
        if (gameGlobal.turno == gameGlobal.partidas) {    //Si es el ultimo turno
            console.log("Ultimo turno")
            if (comprobador == 1) {                         //Si se esta sacando la segunda carta
                console.log("Ultima carta")
                if (gameGlobal.ganador) {                   //Si es ganador
                    console.log("Es ganador")
                    cartaM = carta % 13
                    ultimaM = ultima % 13
                    if (choice) {                           //Si escogio mayor
                        console.log("Escogio mayor")
                        while (cartaM <= ultimaM || cartasJugadas.indexOf(carta) !== -1) {
                            console.log("Cartas muy pequeña " + ultimaM + ", " + cartaM)
                            cardIndex += 2;
                            carta = this.deck[cardIndex];
                            cartaM = carta % 13;
                        }
                    } else {                                //Si escogio menor
                        console.log("Escogio menor")
                        while (cartaM >= ultimaM || cartasJugadas.indexOf(carta) !== -1) {
                            console.log("Cartas muy grande " + ultimaM + ", " + cartaM)
                            cardIndex += 2;
                            carta = this.deck[cardIndex];
                            cartaM = carta % 13;
                        }
                    }
                } else {
                    console.log("Es perdedor")
                    cartaM = carta % 13
                    ultimaM = ultima % 13
                    if (choice) {                           //Si escogio mayor
                        console.log("Escogio mayor")
                        while (cartaM >= ultimaM || cartasJugadas.indexOf(carta) !== -1) {
                            console.log("Cartas muy grande " + ultimaM + ", " + cartaM)
                            cardIndex += 2;
                            carta = this.deck[cardIndex];
                            cartaM = carta % 13;
                        }
                    } else {                                //Si escogio menor
                        console.log("Escogio menor")                        
                        while (cartaM <= ultimaM || cartasJugadas.indexOf(carta) !== -1) {
                            console.log("Cartas muy pequeña " + ultimaM + ", " + cartaM)
                            cardIndex += 2;
                            carta = this.deck[cardIndex];
                            cartaM = carta % 13;
                        }
                    }
                }
            }


            console.log("Carta Salvavidas" + carta % 13)
            cartasJugadas[cartasJugadas.length-1] = carta;
            console.log(cartasJugadas)
            card.loadTexture("cards" + this.getCardTexture(carta));
            card.frame = this.getCardFrame(carta);
            return card;  


        }

        //Para penultimo turno 
        console.log("validacion de penultimo turno")

        if (gameGlobal.turno == gameGlobal.partidas - 1) {    //Si es el penultimo turno
            console.log("penultimo turno")
            if (comprobador == 1) {                         //Si se esta sacando la segunda carta
                console.log("Ultima carta")
                cartaM = carta % 13
                ultimaM = ultima % 13
                if(gameGlobal.playerScore==2 && gameGlobal.machineScore==1){
                    if (choice) {                           //Si escogio mayor
                        console.log("Escogio mayor")
                        while (cartaM >= ultimaM || cartasJugadas.indexOf(carta) !== -1) {
                            console.log("Cartas muy pequeña " + ultimaM + ", " + cartaM)
                            cardIndex += 2;
                            carta = this.deck[cardIndex];
                            cartaM = carta % 13;
                        }
                    } else {                                //Si escogio menor
                        console.log("Escogio menor")
                        while (cartaM <= ultimaM || cartasJugadas.indexOf(carta) !== -1) {
                            console.log("Cartas muy grande " + ultimaM + ", " + cartaM)
                            cardIndex += 2;
                            carta = this.deck[cardIndex];
                            cartaM = carta % 13;
                        }
                    }

                } else if(gameGlobal.playerScore==1 && gameGlobal.machineScore==2){
                    if  (choice) {                           //Si escogio mayor
                        console.log("Escogio mayor")
                        while (cartaM <= ultimaM || cartasJugadas.indexOf(carta) !== -1) {
                            console.log("Cartas muy pequeña " + ultimaM + ", " + cartaM)
                            cardIndex += 2;
                            carta = this.deck[cardIndex];
                            cartaM = carta % 13;
                        }
                    } else {                                //Si escogio menor
                        console.log("Escogio menor")
                        while (cartaM >= ultimaM || cartasJugadas.indexOf(carta) !== -1) {
                            console.log("Cartas muy grande " + ultimaM + ", " + cartaM)
                            cardIndex += 2;
                            carta = this.deck[cardIndex];
                            cartaM = carta % 13;
                        }
                    }

                }

            }


            console.log("Carta Salvavidas" + carta % 13)
            cartasJugadas[cartasJugadas.length-1] = carta;
            console.log(cartasJugadas)
            card.loadTexture("cards" + this.getCardTexture(carta));
            card.frame = this.getCardFrame(carta);
            return card; 
        }

        console.log("validacion de antepenultimo turno")

        if (gameGlobal.turno == gameGlobal.partidas - 2) {    //Si es el antepenultimo turno
            console.log("antepenultimo turno")
            if (comprobador == 1) {                         //Si se esta sacando la segunda carta
                console.log("Ultima carta")
                cartaM = carta % 13
                ultimaM = ultima % 13
                if((gameGlobal.playerScore==1 && gameGlobal.machineScore==1) || (gameGlobal.playerScore==2 && gameGlobal.machineScore==0)){
                    if (choice) {                           //Si escogio mayor
                        console.log("Escogio mayor")
                        while (cartaM >= ultimaM || cartasJugadas.indexOf(carta) !== -1) {
                            console.log("Cartas muy pequeña " + ultimaM + ", " + cartaM)
                            cardIndex += 2;
                            carta = this.deck[cardIndex];
                            cartaM = carta % 13;
                        }
                    } else {                                //Si escogio menor
                        console.log("Escogio menor")
                        while (cartaM <= ultimaM || cartasJugadas.indexOf(carta) !== -1) {
                            console.log("Cartas muy grande " + ultimaM + ", " + cartaM)
                            cardIndex += 2;
                            carta = this.deck[cardIndex];
                            cartaM = carta % 13;
                        }
                    }

                } else if(gameGlobal.playerScore==0 && gameGlobal.machineScore==2){
                    if  (choice) {                           //Si escogio mayor
                        console.log("Escogio mayor")
                        while (cartaM <= ultimaM || cartasJugadas.indexOf(carta) !== -1) {
                            console.log("Cartas muy pequeña " + ultimaM + ", " + cartaM)
                            cardIndex += 2;
                            carta = this.deck[cardIndex];
                            cartaM = carta % 13;
                        }
                    } else {                                //Si escogio menor
                        console.log("Escogio menor")
                        while (cartaM >= ultimaM || cartasJugadas.indexOf(carta) !== -1) {
                            console.log("Cartas muy grande " + ultimaM + ", " + cartaM)
                            cardIndex += 2;
                            carta = this.deck[cardIndex];
                            cartaM = carta % 13;
                        }
                    }

                }

            }

            console.log("Carta Salvavidas" + carta % 13)
            cartasJugadas[cartasJugadas.length-1] = carta;
            console.log(cartasJugadas)
            card.loadTexture("cards" + this.getCardTexture(carta));
            card.frame = this.getCardFrame(carta);
            return card; 
        }

        /**** CONDICIONES BOTON 5 ***/

            console.log("ERROR-----------------")
        return 0;     
    },


    flipCard: function(e, cardFront) {
        e.cardBack = game.add.sprite((game.width * 1/6) + barajaSprite.width/2, (game.height * 1/12) + barajaSprite.height/2, "fondoCarta");
        e.cardBack.anchor.set(0.5);
        //e.cardBack.scale.set(gameOptions.cardScale);
        e.cardBack.angle += 5;
        //e.cardBack.visible = false;

        e.cardBack.isFlipping = true;

        var movimiento0 = game.add.tween(e.cardBack).to({
            y: e.cardBack.y + (((game.height * 2 / 3) - e.cardBack.y)/3),
            x: e.cardBack.x + (((game.width / 2) - e.cardBack.x)/3),
        }, 400, null, true);

        

        var movimiento1 = game.add.tween(e.cardBack).to({
            y: game.height * 2 / 3,
            x: game.width / 2,
            angle: -5
        }, 1500, Phaser.Easing.Cubic.Out, false);
        //movimiento1.pause()
        cardFront.isFlipping = true;
        cardFront.scale.set(gameOptions.cardScale);
        cardFront.angle += 5;
        flipTween = game.add.tween(e.cardBack.scale).to({
                x: 0,
                y: gameOptions.flipZoom
            }, gameOptions.flipSpeed / 2, Phaser.Easing.Linear.None);

            // once the card is flipped, we change its frame and call the second tween
            flipTween.onComplete.add(function(){
                backFlipTween.start();
            });

            // second tween: we complete the flip and lower the card
            backFlipTween = game.add.tween(e.cardBack.scale).to({
                x: 1,
                y: 1
            }, gameOptions.flipSpeed / 2, Phaser.Easing.Linear.None);

            flipTween2 = game.add.tween(cardFront.scale).to({
                x: 0,
                y: gameOptions.flipZoom,
                angle: 0
            }, gameOptions.flipSpeed / 2, Phaser.Easing.Linear.None);

            flipTween.onComplete.add(function(){
                cardFront.visible = true;
                //e.cardBack.visible = false;
                backFlipTween.start();
            });

            backFlipTween = game.add.tween(cardFront.scale).to({
                x: 1,
                y: 1
            }, gameOptions.flipSpeed / 2, Phaser.Easing.Linear.None);

            backFlipTween.onComplete.add(function(){
                e.cardBack.visible = false;
                
                e.cardBack.isFlipping = false;
            });
            console.log("FLIP!! " + e.cardBack)
            if (gameGlobal.turno == gameGlobal.partidas) {
                e.cardBack.visible = false;
            } else {
                e.cardBack.visible = true;
            }
             

            movimiento0.onComplete.add(function(){
                movimiento1.start()
                flipTween.start()
                flipTween2.start()
            });
            

            return game.add.tween(cardFront).to({
                y: game.height * 2 / 3,
                x: game.width / 2,
                angle: 0
            }, 2500, Phaser.Easing.Cubic.Out, true);

    },

    finalizar: function(e) {
        //mensaje de final del juego
        var mensaje;
        
        if (gameGlobal.playerScore > gameGlobal.machineScore) {
            mensaje = game.add.text(game.width * 5/14, game.height /3, 'Fin del juego\n Usted Gana', { fontSize: '150px', fill: '#000' });
            game.paused = true;            
        } else {
            mensaje = game.add.text(game.width * 5/14, game.height /3, 'Fin del juego\n Usted Pierde', { fontSize: '150px', fill: '#000' });
            game.paused = true;
        }

        var continuar = game.add.text(game.width * 11/28, game.height * 2/3, "Continuar", { fontSize: '150px', fill: '#000' })
        continuar.inputEnabled = true;
//Si se presiona continuar
        continuar.events.onInputDown.add(function () {

            gameGlobal = {
                playerScore: 0,
                machineScore: 0,
                turno: 1,
                partidas: gameGlobal.partidas,
                ganador: gameGlobal.ganador
            }
            /*
            e.cartaMaquina = [];
            e.cartaJugador = [];
            */
            for (var i = 0; i < gameGlobal.partidas; i++) {
                e.cartaMaquina[i].destroy();
                e.cartaJugador[i].destroy();
            }

            cartasJugadas = [cartasJugadas[cartasJugadas.length-2], cartasJugadas[cartasJugadas.length-1]];
            e.cardBack.visible = true;
            game.paused = false;
            e.playerScoreText.setText("0");
            e.machineScoreText.setText("0");
            continuar.destroy();
            mensaje.destroy();

        });
        

    }
}