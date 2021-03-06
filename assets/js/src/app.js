$( document ).ready(function() {

	ROTATE_MAG = 2;
	PRACTICE_MODE = 0;
	GAME_INFO = null;
	GAME_MODE = 0;
	TOTAL_DODGED = 0;
	TOTAL_THROWN = 0;
	RESETTING = 0;
	CURRENT_BALL = 0;
	THROW_DATA = [];
	NEW_THROW_DATA = [];
	NEW_MAIL_DATA = [];
	LAST_BALL = "";
	HELP = 0;
	choices = ["c", "t", "l", "r", "x", "y"];

	bindStuff();
	clickables();
	if(TOKEN !== null) {
		getGameInfo();
		$(".menu ul").hide();
	} else {
		runApp();
	}
});


function bindStuff() {
	$hit = $(".hit");
	$dude = $(".dude");
	$sdude = $(".sdude");
	$sdudeouter = $(".sdude-outer");
	$torso = $(".torso");
	$legs = $(".legs");
	$wrap = $(".wrap");
	$cracked = $(".cracked");
	$ball = $(".ball");
	$ballx = $(".ball-x");
	$bally = $(".ball-y");
	$help = $(".help");
	$banner = $(".banner");
	$gpractice = $(".practice");
	$gready = $(".ready");
	$dodges = $(".dodges");
	$dodgesCount = $dodges.find("span");
	$bannerMenu = $(".bannermenu");
	$challenged = $(".challenged");
	$court = $(".court");
	$tapToHideHelp = $(".tapToHideHelp");
	$tapToStartGameFromPractice = $(".tapToStartGameFromPractice");
	$throwing = $(".throwing");
	$throwlist = $(".throwlist");
	$throwmore = $(".throwmore");
	$throwsend = $(".throwsend");
	$tapToAddMail= $(".addmore");
	$sendMails = $(".sendMails");
	$fromEmail = $("#emfrom");
	$fromName = $("#namefrom");
	$ending = $(".ending");
	dudeTop = 400;


	$(document).keydown(function(e) {
		//console.log(e.which);
		if(GAME_MODE || PRACTICE_MODE) {
		  	if(e.which == 37 || e.which == 65) {
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
		  	if(e.which == 39 || e.which == 68) {
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
	  	}
	});
	$(document).keyup(function(e) {
		if(e.which == 37 || e.which == 39) {
			//$torso.removeClass("left right");
		}
	});

	// if (window.DeviceMotionEvent != undefined) {
	// 	window.ondevicemotion = function(e) {
	// 		parseMotion(event.accelerationIncludingGravity.x);
	// 	}
	// }
}
function clickables(){
	$gpractice.click(function(event) {
		event.preventDefault();
		startPractice();
	});
	$gready.click(function(event) {
		event.preventDefault();
		startDodging();
	});
	$tapToHideHelp.click(function(event) {
		event.preventDefault();
		HELP = 1;
		$(".mobile, .desktop").addClass('hide');
		startGame();
	});
	$tapToStartGameFromPractice.click(function(event) {
		event.preventDefault();
		$tapToStartGameFromPractice.fadeOut(500);
		resetGameData();
		resetStats();
		if(GAME_INFO.throw_data) {
			THROW_DATA = GAME_INFO.throw_data;
		}
		setTimeout(function(){
			resetStats();
			startGame();
			RESETTING = 0;
			showCharacter();
			PRACTICE_MODE = 0;
			CURRENT_BALL = 0;
			GAME_MODE = 1;
		}, 2000);
		
	});
	$tapToAddMail.click(function(event) {
		event.preventDefault();
		var em = $("#throwto").val();
		$("#throwto").val("");
		console.log(em);

		if($(".mails span").size() < 3 && NEW_MAIL_DATA.length < 3){
			$(".mails").append('<span onclick="removeMe(this);">' + em + '</span>');
			NEW_MAIL_DATA.push(em);
		}
		if($(".mails span").size() > 0) {
			$sendMails.removeClass("hide");
		}
	});

	$sendMails.click(function(event) {
		event.preventDefault();
		if(TOKEN) {
			if(NEW_MAIL_DATA.length > 0 && NEW_MAIL_DATA.length <= 3) {
				var dodgedata = NEW_THROW_DATA.join("");
				var emaildata = NEW_MAIL_DATA.join("");
				
				
				if($fromName.val() != "" && $fromEmail.val() != "" && TOKEN && NEW_THROW_DATA.length>0 && NEW_MAIL_DATA.length>0){
					$sendMails.addClass("hide");
					var data = {
						fromEmail: $fromEmail.val(), //CHECK IF EMAIL
						fromName: $fromName.val(),
						toEmails: NEW_MAIL_DATA,
						dodgeData: NEW_THROW_DATA.join(""),
						token: TOKEN
					};
					sendNewGame(data);
				} else {
					$sendMails.addClass("e");
					setTimeout(function(){
						$sendMails.removeClass("e");
					}, 3000);
				}
			}
		}
	});

	$(".throwchoose .throwitem").click(function(event){
		event.preventDefault();
		addToThrowList($(this).attr("data-throw"));
	});
}

function removeMe(who){
	$(who).fadeOut(200).remove();
	if($(".mails span").size() == 0){
		$sendMails.addClass("hide");
	}
	if(NEW_MAIL_DATA.indexOf($(who).find("span").html())){
		NEW_MAIL_DATA.splice(NEW_MAIL_DATA.indexOf($(who).html()), 1);
	}
	console.log(NEW_MAIL_DATA);
}
function startPractice(){
	$help.removeClass("hide");
	clearScreenOfCrap();
	showCharacter();
	$help.find(".desktop").removeClass("hide");
	PRACTICE_MODE = 1;
}
function startDodging(){
	PRACTICE_MODE = 0;
	GAME_MODE = 1;
	clearScreenOfCrap();
	startGame();
	showCharacter();
	setTimeout(function(){
		
	}, 1000);
}
function startGame(){
	$help.addClass("hide");
	$tapToHideHelp.hide();
	
	if(PRACTICE_MODE) {
		setTimeout(startPracticeBallsLoop, 2500);
		setTimeout(showOpponent, 500);
	} else {
		setTimeout(startBallsLoop, 2500);
		setTimeout(showOpponent, 1500);
	}
}
function startPracticeBallsLoop(){
	$bally.css("top", "180px");
	throwBall();
}
function startBallsLoop(){
	if(GAME_INFO.throw_data) {
		THROW_DATA = GAME_INFO.throw_data;
	}
	$bally.css("top", "180px");
	throwBall();
}
function clearScreenOfCrap(){
	$bannerMenu.fadeOut('200');
	$challenged.fadeOut('200');
	$ball.addClass('hide');
	$sdude.addClass('hide');
}
function showOpponent(){
	$ball.addClass("playing");
	$sdudeouter.addClass("play");
	$sdude.addClass('throw');
	$sdude.removeClass("frown");
	setTimeout(function(){
		$sdude.removeClass("hide");
	}, 200);
	$dodges.fadeIn(1000);
	if(PRACTICE_MODE) {
		setTimeout(function(){
			$tapToStartGameFromPractice.fadeIn(1000);
		}, 1000);
	}
}
function showCharacter() {
	$dude.removeClass('hide');
	$court.addClass('play');
}
function dodgeRatioUpdate(){
	$dodgesCount.html(TOTAL_DODGED+"/"+TOTAL_THROWN);
}
function sendNewGame(data){
	$.ajax({
		url: "api/driver.php",
		type: "POST",
		data: {
			action: "genGame",
			toEmail: data.toEmails,
			fromEmail: data.fromEmail,
			fromName: data.fromName,
			throwData: data.dodgeData,
			token: data.token
		}
	}).success(function(data){
		console.log(data);
		if(data == "1") {
			hideEverything();
		}
	});
}
function hideEverything(){
	$throwing.fadeOut(1000);
	$ending.removeClass("hide");
}
function resetGameData(){
	RESETTING = 1;
	$ball.hide();
	$sdude.addClass("hide");
	$dodges.hide();
}
function resetStats(){
	CURRENT_BALL = 0;
	TOTAL_THROWN = 0;
	TOTAL_DODGED = 0;
	dodgeRatioUpdate();
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
			onGetGameInfo();
		});
	}
}
function onGetGameInfo() {
	if(GAME_INFO.name !== null) {
		$(".menu .challenged").html("<span>"+GAME_INFO.name+"</span><br>has thrown dodgeballs at you!").fadeIn(1500);
		setTimeout(function(){
			$sdude.addClass("frown");
			$banner.fadeOut(500);
			$bannerMenu.fadeIn(500);
		}, 1500);
	} else {
		$(".menu .challenged").html("Sorry, we can't find your game.").fadeIn(1000);
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

function addToThrowList(n) {
	if(NEW_THROW_DATA.length < 20) {
		setTimeout(function(){
			$throwsend.removeClass("hide");
		}, 500);
		$throwlist.prepend("<div class='throwitem skinny "+n+"'></div>");
		NEW_THROW_DATA.push(n);
		if(NEW_THROW_DATA.length > 10) {
			$throwmore.html("+ "+(NEW_THROW_DATA.length-10)+" MORE THROWS");
			$throwmore.removeClass("hide");
		} else {
			$throwmore.addClass("hide");
		}
		setTimeout(function(){
			$(".skinny").removeClass("skinny");
		}, 100);
	}
	if(NEW_THROW_DATA.length == 0){
		$throwsend.addClass("hide");
	}
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
function currentBall(n) {
	if(LAST_BALL == n) {
		return 1;
	} else {
		return 0;
	}
}
function checkIfDodged(){
	console.log("CHECKING");
	dead = false;
	if($torso.hasClass('left') && (currentBall('l') || (currentBall('y')))) {
		console.log("hitLeft");
		dead = true;
		$dude.addClass("spin left");
		setTimeout(function(){
			$dude.removeClass("spin left");
		}, 1000);
	}
	if($torso.hasClass('right') && (currentBall('r') || (currentBall('x')))) {
		console.log("hitRight");
		dead = true;
		$dude.addClass("spin");
		setTimeout(function(){
			$dude.removeClass("spin");
		}, 1000);
	}
	if(!$torso.hasClass('left') && !$torso.hasClass('right') && (currentBall('c') || currentBall('t'))) {
		console.log("hitCenter");
		$torso.addClass("deadScreen");
		$legs.addClass("deadScreen");
		setTimeout(function(){
			$cracked.show();
			$cracked.removeClass("hide");
			setTimeout(function(){
				$cracked.fadeOut(300);
				$cracked.addClass("hide");
			}, 1000);
		}, 80);
		dead = true;
	}	

	if(dead == true){
		$torso.addClass("dead");
	} else {
		TOTAL_DODGED++;
	}
	dodgeRatioUpdate();
}
function getRandomThrow(){
	var r = Math.floor(Math.random()*choices.length);
	LAST_BALL = choices[r];
	return r;
}
function getThrowIndex(ball) {
	var ind = choices.indexOf(ball);
	return ind;
}
function gameFinished(){
	$help.removeClass("hide");
	$dude.addClass("hide");
	$throwing.removeClass("hide");
	$dodges.addClass("ontop");
}
function throwBall(){
	$torso.removeClass("deadScreen dead");
	$legs.removeClass("deadScreen dead");
	$ball.show();
	$sdude.addClass('throw');

	setTimeout(function(){
		if(GAME_MODE || PRACTICE_MODE) {
			
			// var choices = ["t"];
		}
		if(GAME_MODE) {
			if(CURRENT_BALL < THROW_DATA.length) {
				rand = getThrowIndex(THROW_DATA[CURRENT_BALL]);
				LAST_BALL = choices[rand];
			}
		} else {
			rand = getRandomThrow();
		}
		//var speeds = ["s", "m", "f"];
		
		$ball.addClass('big');

		TOTAL_THROWN++;
		
		//$ball.addClass(speeds[rand2]); 
		if(GAME_MODE || PRACTICE_MODE){
			$ballx.addClass(choices[rand]); 
			if(choices[rand] == "t") {
				//change speed to fast
				$ball.addClass("fast"); 
				$bally.addClass("fast"); 
			} else {
				//same speed for others
				$bally.addClass('throw');
			}
			$bally.css("top", "580px");
		} else {
			$bally.css("top", Math.floor(Math.random()*300) + "px");
			$ball.css("margin-left", Math.floor(Math.random()*800-400) + "px");
		}
		$sdude.removeClass('throw');


		if(GAME_MODE || PRACTICE_MODE){
			var checking = setInterval(function(){
				if($bally.offset().top > 400) { 
					//Game and practice
					$bally.css("top", "180px");
					$ball.addClass("reset");
					$ball.removeClass('big hide fast');
					$ballx.removeClass('big hide l r c t x y');
					$bally.removeClass('throw fast');
				
					$ball.hide();
					setTimeout(function(){
						//$bally.addClass('throw');
						$sdude.addClass('throw');
						setTimeout(function(){
							console.log(GAME_MODE, PRACTICE_MODE);
							if(GAME_MODE) {
								if(!RESETTING){
									CURRENT_BALL++;
								}
								if(CURRENT_BALL < THROW_DATA.length){
									console.log("B", THROW_DATA.length, CURRENT_BALL, TOTAL_THROWN)
									if(!RESETTING){
										throwBall();
									}
								
								} else {
									gameFinished();
								}
							} else {
								CURRENT_BALL++;
								if(!RESETTING){
									throwBall();
								}
							}
						}, 100);
					}, 500);
					if(!RESETTING) {
						checkIfDodged();
					}
					clearInterval(checking);
				}

			}, 50);
		} else {
			setTimeout(function(){
				$bally.css("top", "280px");
				$ball.css("margin-left", "85px");
			}, 1000);
		}

	}, 200); // edit this for random throwing intervals

}


