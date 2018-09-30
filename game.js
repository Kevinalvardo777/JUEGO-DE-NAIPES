var game;
var gameOptions = {
    gameWidth: 2950,
    gameHeight: 1334,
    cardSheetWidth: 334,
    cardSheetHeight: 440,
    cardScale: 0.8
}
var gameGlobal = {
    playerScore: 0,
    machineScore: 0,
    turno: 0,
    partidas: 3
}

window.onload = function() {
    game = new Phaser.Game(gameOptions.gameWidth, gameOptions.gameHeight);
    game.state.add("PlayGame", playGame)
    game.state.start("PlayGame");
}
var playGame = function(game) {}
playGame.prototype = {
    preload: function() {
        for(var i = 0; i < 10; i++){
            game.load.spritesheet("cards" + i, "cards" + i + ".png", gameOptions.cardSheetWidth, gameOptions.cardSheetHeight);
        }
        game.load.spritesheet("info", "info.png", 500, 184);
        game.load.spritesheet("swipe", "swipe.png", 80, 130);
        game.scale.scaleMode = Phaser.ScaleManager.SHOW_ALL;
        game.scale.pageAlignHorizontally = true;
        game.scale.pageAlignVertically = true;
        game.load.image("tuPuntaje", "tuPuntaje.png");
        game.load.image("puntajeMaquina", "puntajeMaquina.png");
    },
    create: function() {

        game.stage.backgroundColor = "#4488AA" ;
        game.add.sprite(game.width * 101/144, game.height * 5/8, "tuPuntaje");
        game.add.sprite(game.width * 1/6, game.height * 5/8, "puntajeMaquina");

        this.playerScoreText = game.add.text(game.width * 25/32, game.height * 3/4, '' + gameGlobal.playerScore, { fontSize: '80px', fill: '#000' });
        this.machineScoreText = game.add.text(game.width / 4, game.height * 3/4, '' + gameGlobal.machineScore, { fontSize: '80px', fill: '#000' });

        if (this.check()) {            
            this.finalizar();
            this.playerScoreText.setText("0");
            this.machineScoreText.setText("0");
        }

        var textoTusNaipes = "TUS NAIPES";
        var style = {font: "40px Arial", fill: "#ffffff", align: "center"};

        var textoNaipesBaraja = "NAIPES DE BARAJA";
        
        game.add.text(game.width * 3/4, game.height / 4, textoTusNaipes, style);
        game.add.text(game.width / 5, game.height / 4, textoNaipesBaraja, style);
        

        this.infoGroup = game.add.group();
        this.infoGroup.visible = false;
        this.deck = Phaser.ArrayUtils.numberArray(0, 51);
        Phaser.ArrayUtils.shuffle(this.deck);
        this.cardsInGame = [this.makeCard(0), this.makeCard(1)];
        this.nextCardIndex = 2;
        var tween = game.add.tween(this.cardsInGame[0]).to({
            x: game.width / 2
        }, 500, Phaser.Easing.Cubic.Out, true);
        tween.onComplete.add(function(){
            this.infoGroup.visible = true;
        }, this)
        var infoUp = game.add.sprite(game.width / 2, game.height / 6, "info");
        infoUp.anchor.set(0.5);
        this.infoGroup.add(infoUp);
        var infoDown = game.add.sprite(game.width / 2, game.height * 5 / 6, "info");
        infoDown.anchor.set(0.5);
        infoDown.frame = 1;
        this.infoGroup.add(infoDown);
        var swipeUp = game.add.sprite(game.width / 2, game.height / 2 - gameOptions.cardSheetHeight / 2 - 20, "swipe");
        var swipeUpTween = game.add.tween(swipeUp).to({
            y: swipeUp.y - 60
        }, 1000, Phaser.Easing.Linear.None, true, 0, -1);     
        swipeUp.anchor.set(0.5);   
        this.infoGroup.add(swipeUp);
        var swipeDown = game.add.sprite(game.width / 2, game.height / 2 + gameOptions.cardSheetHeight / 2 + 20, "swipe");
        swipeDown.frame = 1;
        var swipeDownTween = game.add.tween(swipeDown).to({
            y: swipeDown.y + 60
        }, 1000, Phaser.Easing.Linear.None, true, 0, -1);     
        swipeDown.anchor.set(0.5);   
        this.infoGroup.add(swipeDown);
        game.input.onDown.add(this.beginSwipe, this);
        gameGlobal.turno += 1;
    },
    makeCard: function(cardIndex) {
        var card = game.add.sprite(gameOptions.cardSheetWidth * gameOptions.cardScale / -2, game.height / 2, "cards0");
        card.anchor.set(0.5);
        card.scale.set(gameOptions.cardScale);
        card.loadTexture("cards" + this.getCardTexture(this.deck[cardIndex]));
        card.frame = this.getCardFrame(this.deck[cardIndex]);
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
        if(swipeMagnitude > 20 && swipeTime < 1000 && Math.abs(swipeNormal.y) > 0.8) {
            if(swipeNormal.y > 0.8) {
                this.handleSwipe(1);
            }
            if(swipeNormal.y < -0.8) {
                this.handleSwipe(-1);
            }
        } else {
            game.input.onDown.add(this.beginSwipe, this);
        }
    },
    handleSwipe: function(dir) {
        var cardToMove = (this.nextCardIndex + 1) % 2;
        this.cardsInGame[cardToMove].y += dir * gameOptions.cardSheetHeight * gameOptions.cardScale * 1.1;
        var tween = game.add.tween(this.cardsInGame[cardToMove]).to({
            x: game.width / 2
        }, 500, Phaser.Easing.Cubic.Out, true);
        tween.onComplete.add(function() {
            var newCard = this.deck[this.nextCardIndex - 1];
            var oldCard = this.deck[this.nextCardIndex - 2];
            if(((dir == -1) && ((newCard % 13 > oldCard % 13) || ((newCard % 13 == oldCard % 13) && (newCard > oldCard)))) || ((dir == 1) && ((newCard % 13 < oldCard % 13) || ((newCard % 13 == oldCard % 13) && (newCard < oldCard))))){
                this.addPlayerScore();
                game.time.events.add(Phaser.Timer.SECOND, this.fadeCards, this);                
            }
            else{
                this.addMachineScore();
                game.time.events.add(Phaser.Timer.SECOND, this.fadeCards, this);
                   
            }
        }, this)
    },
    moveCards: function() {
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
        for(var i = 0; i < 2; i++){
            var fadeTween = game.add.tween(this.cardsInGame[i]).to({
                alpha: 0
            }, 500, Phaser.Easing.Linear.None, true);
        }
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
        if (gameGlobal.turno == gameGlobal.partidas) {
                return true;
        }
        return false;
    },

    finalizar: function() {
        //mensaje de final del juego
        
        var mensaje;
        
        if (gameGlobal.playerScore > gameGlobal.machineScore) {
            mensaje = game.add.text(game.width * 5/14, game.height /3, 'Fin del juego\n Usted Gana', { fontSize: '150px', fill: '#000' });
            game.paused = true;            
        } else {
            mensaje = game.add.text(game.width * 5/14, game.height /3, 'Fin del juego\n Usted Pierde', { fontSize: '150px', fill: '#000' });
            game.paused = true;
        }
        gameGlobal = {
            playerScore: 0,
            machineScore: 0,
            turno: 0,
            partidas: gameGlobal.partidas
        }
        var continuar = game.add.text(game.width * 11/28, game.height * 2/3, "Continuar", { fontSize: '150px', fill: '#000' })
        continuar.inputEnabled = true;
        continuar.events.onInputDown.add(function () {            
            game.paused = false;
            continuar.destroy();
            mensaje.destroy();
        });

    }
}