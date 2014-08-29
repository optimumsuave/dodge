<?php

// require_once('Mandrill.php');
function sendMail($info) {
	$fromEmail = $info['fromEmail'];
	$emailTo = $info['sendToEmail'];
	$name = $info['name'];
	$hash = $info['hash'];
	$url = "http://rosetta.com/dodgeball/?game=" . $hash;
    $html = '
	<body style="background:#eee;padding-top:20px;width:600px;">
            <div style="max-width:500px;margin:0 auto;background:#fff;padding:15px;height:500px;margin:0 50px;">
                <table>
		<tr>
                <td style="width:60%">
                    <h1 style="font-family:Arial;letter-spacing:1px;font-size:32px;">INCOMING DODGEBALLS!</h1>
                    <h2 style="font-family:Arial;font-size:16px;"><span style="color:#00BEE7;">' . $name . '</span> has thrown some dodgeballs in your direction!</h2>
			<a style="font-family:Arial;color:#00BEE7;padding-top:6px;padding-bottom:6px;font-size:18px;" href="' . $url . '">I\'m ready to dodge!</a><br><br>
			<div style="font-size:9px;color:#999;margin-top:3px;">Link will navigate to dodgeball game...</div>
                    <h2 style="font-family:Arial;font-size:16px;">Remember to show your support and come out for the tournament on Sept. 5th!</h2>
			<h3 style="font-size:14px;text-transform:uppercase;border-bottom:#eee solid 1px;padding-bottom: 5px;margin-bottom:3px;width:100%;clear:both;">Where:</h3>
			<p style="margin-top:0px;padding-top:0px;">Ludwick Community Center - 864 Santa Rosa St, San Luis Obispo, CA 93401</p>
			<h3 style="font-size:14px;text-transform:uppercase;border-bottom:#eee solid 1px;padding-bottom: 5px;margin-bottom:3px;width:100%;clear:both;">When:</h3>
			<p style="margin-top:0px;padding-top:0px;">
			September 5th 2014<br>
			5:30 – 7:30 PM
			</p>
		</td>
		<td style="width:30%"><img style="width:100%;height:auto;" src="http://rosetta.com/dodgeball/assets/images/mandrill/right.png" alt="" /></td>
		</tr>
		</table>
    </div>';
    $subject = 'Incoming Dodgeballs from ' . $name . '!';
            
            
    $headers = "From: " . $fromEmail . "\r\n";
    $headers .= "Reply-To: " . "connor.nielsen@rosetta.com" . "\r\n";
    $headers .= "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";

    if (mail($emailTo, $subject, $html, $headers)) {
      return 1;
    } else {
      return 0;
    }
}

?>
