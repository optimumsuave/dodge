<?php


require "connect.php";

require "sendMail.php";

// since we connect to default setting localhost
// and 6379 port there is no need for extra
// configuration. If not then you can specify the
// scheme, host and port to connect as an array
// to the constructor.
function generateHash(){
	$seed = time();
	$hash = sha1(uniqid($seed . mt_rand(), true));
	return $hash;
}

function checkIfDone($info, $mysqli){
	$statement = $mysqli->prepare("SELECT done FROM games WHERE token =?");
	$statement->bind_param('s', $info['token']);
	if($statement->execute()){
		$statement->bind_result($done);
		if($done) {
			return 0;
		} else {
			return 1;
		}
	}
}

function saveToDatabase($info, $mysqli){

	//Select to see if done
	if(checkIfDone($info, $mysqli)){
		//====================
		//Update the row to make sure that its done
		$astatement = $mysqli->prepare("UPDATE games SET done = 1 WHERE token =?");
		$astatement->bind_param('s', $info['token']);
		if($astatement->execute()){
			//Finally insert the new game if the old token checks out
			$bstatement = $mysqli->prepare("INSERT INTO games (token, from_name, throw_data, done) VALUES(?, ?, ?, 0)");
			$bstatement->bind_param('sss', $info['hash'], $info['name'], $info['throwData']);
			if($bstatement->execute()){
			    return 1;
			}else{
			    die('Error : ('. $mysqli->errno .') '. $mysqli->error);
			}
			$bstatement->close();	
		} else{
			return 0;
		    die('Error : ('. $mysqli->errno .') '. $mysqli->error);
		}
	}	
}

function getDodgeInfo($token, $mysqli){
	$gstatement = $mysqli->prepare("SELECT from_name, throw_data, done FROM games WHERE token =? AND done=0");
	$gstatement->bind_param('s', $token);
	if($gstatement->execute()){
		$r = array();
	   	$gstatement->bind_result($r['name'], $r['throw_data'], $r['done']);
	   	$r['token'] = $token;
	   	mysqli_stmt_fetch($gstatement);
	   	return $r;
	}else{
	    die('Error : ('. $mysqli->errno .') '. $mysqli->error);
	}
	$gstatement->close();
}

function storeDodgeInfo($info, $mysqli){
	//set key info
	$dodges = $info['throwData'];
	$toEmail = $info['toEmail'];
	$fromEmail = $info['fromEmail'];
	$info['hash'] = generateHash();
	$info['token'] = $info['token'];
	foreach($toEmail as $em) {
		if (filter_var($em, FILTER_VALIDATE_EMAIL)){

		} else {
			return 0;
		}
	}
	if (filter_var($fromEmail, FILTER_VALIDATE_EMAIL)) {
		//The emails are valid so far, let's try sending it.
		//$result = sendMail($info);
		//if($result) {
			//cool!
			return saveToDatabase($info, $mysqli);
		//}
	} else {
		return 0;
	}

}


//Do stuff....
if(isset($_POST['action'])){
	switch($_POST['action']){
		case "getGame":
			if(isset($_POST['id'])){
				print json_encode(getDodgeInfo($_POST['id'], $mysqli));
			}
		break;
		case "genGame":
			if(isset($_POST['toEmail']) && isset($_POST['fromEmail']) && isset($_POST['throwData']) && isset($_POST['fromName'])){
				$arr = array();
				$arr['throwData'] = $_POST['throwData'];
				$arr['toEmail'] = $_POST['toEmail'];
				$arr['fromEmail'] = $_POST['fromEmail'];
				$arr['name'] = $_POST['fromName'];
				$arr['token'] = $_POST['token'];
				print storeDodgeInfo($arr, $mysqli);
			}
		default:
		break;

	}
}


?>