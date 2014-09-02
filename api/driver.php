<?php

$DEV = 0;
require "connect.php";

require "sendMail.php";

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
			$bstatement = $mysqli->prepare("INSERT INTO games (token, from_name, throw_data, done, spawn, parent_token) VALUES(?, ?, ?, 0, 0, ?)");
			$bstatement->bind_param('ssss', $info['hash'], $info['name'], $info['throwData'], $info['token']);
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
	if($gstatement){
		$gstatement->bind_param('s', $token);
	}
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

function getOpenGames($mysqli) {
	$result = $mysqli->query("SELECT COUNT(done) FROM games WHERE done = 0");
	$row = $result->fetch_row();
	return $row[0];
}

function getClosedGames($mysqli) {
	$result = $mysqli->query("SELECT COUNT(done) FROM games WHERE done = 1");
	$row = $result->fetch_row();
	return $row[0];
}

function generateTokenForAdmin($mysqli) {
	//$mysqli = getDB();
	$info = array();
	$info['throwData'] = "t";
	$info['name'] = "The Dude";
	$info['hash'] = generateHash();
	$genstmt = $mysqli->prepare("INSERT INTO games (token, from_name, throw_data, done, spawn, parent_token) VALUES(?, ?, ?, 0, 1, 0)");
	if($genstmt){
		$genstmt->bind_param('sss', $info['hash'], $info['name'], $info['throwData']);
		if($genstmt->execute()){
	    	return $info['hash'];
		}else{
	    	die('Error : ('. $mysqli->errno .') '. $mysqli->error);
		}
	}
	$genstmt->close();
}

function storeDodgeInfo($info, $mysqli){
	//set key info
	$dodges = $info['throwData'];
	$toEmail = $info['toEmail'];
	$fromEmail = $info['fromEmail'];
	
	$info['token'] = $info['token'];
	// if (filter_var($fromEmail, FILTER_VALIDATE_EMAIL)) {
		foreach($toEmail as $em) {
			// if (filter_var($em, FILTER_VALIDATE_EMAIL)){
				$info['hash'] = generateHash();
				$info['sendToEmail'] = $em;
				// if(!$DEV) {
				$result = sendMail($info);
				// } else {
					// $result = 1;
				// }
				
				if($result) {
					if(saveToDatabase($info, $mysqli)){

					} else {
						$err = 1;
					}
				} else {
					$err = 1;
				}
			// }
		}
		if($err) {
			return 0;
		} else {
			return 1;
		}
	// }
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