$( document ).ready(function() {

	ROTATE_MAG = 2;
	PRACTICE_MODE = 0;
	GAME_INFO = null;

	bindStuff();
	clickables();
	if(TOKEN !== null) {
		getGameInfo();
		$(".menu ul").hide();
		$(".menu .challenged").html("<span>CONNOR NIELSEN</span><br>has thrown dodgeballs at you!").fadeIn(1500);
		setTimeout(function(){
			$sdude.addClass("frown");
			$banner.fadeOut(500);
			$bannerMenu.fadeIn(500);
		}, 1500);
	} else {
		runApp();
	}
});


function bindStuff() {
	$hit = $(".hit");
	$dude = $(".dude");
	$sdude = $(".sdude");
	$torso = $(".torso");
	$wrap = $(".wrap");
	$ball = $(".ball");
	$bally = $(".ball-y");
	$help = $(".help");
	$banner = $(".banner");
	$gpractice = $(".practice");
	$gready = $(".ready");
	$bannerMenu = $(".bannermenu");
	$challenged = $(".challenged");
	$court = $(".court");
	$tapToHideHelp = $(".tapToHideHelp");


	$(document).keydown(function(e) {
		// console.log(e.which);
	  	if(e.which == 37) {
	   		//left
			if(!$torso.hasClass("left")){
				if($torso.hasClass("center")){
					$torso.removeClass("center");
			   		$torso.addClass("left");			
				}
				if($torso.hasClass("right")){
					$torso.removeClass("right");
					$torso.addClass("center");	
				}
			}


	  	}
	  	if(e.which == 39) {
	   		//right
			if(!$torso.hasClass("right")){
				if($torso.hasClass("center")){
					$torso.removeClass("center");
			   		$torso.addClass("right");			
				}
				if($torso.hasClass("left")){
					$torso.removeClass("left");
					$torso.addClass("center");	
				}
			}

	  	}
	  	if(e.which == 32) {
	   		//right
	   		throwBall();
	  	}
	});
	$(document).keyup(function(e) {
		if(e.which == 37 || e.which == 39) {
			//$torso.removeClass("left right");
		}
	});

	if (window.DeviceMotionEvent != undefined) {
		window.ondevicemotion = function(e) {
			parseMotion(event.accelerationIncludingGravity.x);
		}
	}
}
function clickables(){
	$gpractice.click(function(event) {
		event.preventDefault();
		startPractice();
	});
	$gready.click(function(event) {
		event.preventDefault();
		startGame();
	});
	$tapToHideHelp.click(function(event) {
		event.preventDefault();
		startGame();
	});
}
function startPractice(){
	$help.removeClass("hide");
	clearScreenOfCrap();
	showCharacter();
	$help.find(".desktop").removeClass("hide");
	PRACTICE_MODE = 1;
}
function startGame(){
	$help.removeClass("hide");
	clearScreenOfCrap();
}
function clearScreenOfCrap(){
	$bannerMenu.fadeOut('200');
	$challenged.fadeOut('200');
	$ball.addClass('hide');
	$sdude.addClass('hide');
}
function showCharacter() {
	$dude.removeClass('hide');
	$court.addClass('play');
}
function sendNewGame(){
	$.ajax({
		url: "api/driver.php",
		type: "POST",
		data: {
			action: "genGame",
			toEmail: "connorwnielsen@gmail.com",
			fromEmail: "nyrdbyrd@gmail.com",
			fromName: "Connor Nielsen",
			throwData: "djskuidsfusdfgsjdg"
		},
	}).success(function(data){
		console.log(data);
	});
}
function getGameInfo(){
	if(TOKEN !== null) {
		var jdata = null;
		$.ajax({
			url: "api/driver.php",
			type: "POST",
			data: {
				action: "getGame",
				id: TOKEN
			},
		}).success(function(data){
			GAME_INFO = JSON.parse(data);
		});
	}
}
function updateGameInfo(){
	if(TOKEN !== null) {
		var jdata = null;
		$.ajax({
			url: "api/driver.php",
			type: "POST",
			data: {
				action: "completeGame",
				id: TOKEN
			},
		}).success(function(data){
			GAME_INFO = JSON.parse(data);
		});
	}
}
function runApp() {
	
	setTimeout(function(){
		throwBall();
	}, 1000);
}


function parseMotion(mag){
	var r = Math.floor(mag);
	$("#rotationAlpha").html(r);
		

	if(r > ROTATE_MAG) {
		$torso.addClass("left");
	} else {
		$torso.removeClass("left");

	}

	if(r < (ROTATE_MAG*-1)) {
		$torso.addClass("right");

	} else {
		$torso.removeClass("right");

	}
}
function timerBall(){
	throwBall();
	setTimeout(function(){
		timerBall();
	}, 2000);
}
function throwBall(){
	
	$ball.show();
	setTimeout(function(){

		var rand = Math.floor(Math.random()*3);
		var choices = ["c", "l", "r"];
		//var speeds = ["s", "m", "f"];
		$bally.addClass('throw');
		
		$ball.addClass('big');
		$ball.addClass(choices[rand]); 
		//$ball.addClass(speeds[rand2]); 
		$bally.css("top", Math.floor(Math.random()*300) + "px");
		$ball.css("margin-left", Math.floor(Math.random()*800-400) + "px");

		setTimeout(function(){
			$sdude.removeClass('throw');
		}, 200);
		setTimeout(function(){
			$bally.css("top", "280px");
			$ball.removeClass('big l r c');
			$bally.removeClass('throw');

			$ball.css("margin-left", 85 + "px");
			$ball.hide();
			
			setTimeout(function(){
				$sdude.addClass('throw');
				setTimeout(function(){
					throwBall();
				}, 100);
			}, 1000);
		}, 1000);

	}, 200); // edit this for random throwing intervals

}


