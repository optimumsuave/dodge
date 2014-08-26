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

function saveToDatabase($info, $mysqli){
	$statement = $mysqli->prepare("INSERT INTO games (token, from_name, throw_data) VALUES(?, ?, ?)");
	$statement->bind_param('sss', $info['hash'], $info['name'], $info['throwData']);
	if($statement->execute()){
	    return 1;
	}else{
	    die('Error : ('. $mysqli->errno .') '. $mysqli->error);
	}
	$statement->close();		
}

function getDodgeInfo($token, $mysqli){
	$gstatement = $mysqli->prepare("SELECT from_name, throw_data, done FROM games WHERE token =?");
	$gstatement->bind_param('s', $token);
	if($gstatement->execute()){
		$r = array();
	   	$gstatement->bind_result($r['info'], $r['throw_data'], $r['done']);
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
	if (filter_var($toEmail, FILTER_VALIDATE_EMAIL) && filter_var($toEmail, FILTER_VALIDATE_EMAIL)) {
		//The emails are valid so far, let's try sending it.
		//$result = sendMail($info);
		//if($result) {
			//cool!
			saveToDatabase($info, $mysqli);
		//}
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
				storeDodgeInfo($arr, $mysqli);
				print "1";
			}
		default:
		break;

	}
}


?>