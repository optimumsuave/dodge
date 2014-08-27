<?php

require_once('Mandrill.php');
function sendMail($info) {
	try {
	$fromEmail = $info['fromEmail'];
	$emailTo = $info['sendToEmail'];
	$name = $info['fromName'];

	$mandrill = new Mandrill('jzg4jjCnxE8bb1imwcIi9Q');
	$template_name = 'dodgeball';
	$template_content = array(
        	array(
            		'name' => 'example name',
            		'content' => 'example content'
        	)
    	);
	$hash = $info['hash'];
	$url = "http://phawnts.com/dodge/?game=" . $hash;
    	$message = array(
        'html' => '
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
		<td style="width:30%"><img style="width:100%;height:auto;" src="http://phawnts.com/dodge/assets/images/mandrill/right.png" alt="" /></td>
		</tr>
		</table>
            </div>
        </body>',
        'text' => 'Example text content',
        'subject' => 'Incoming Dodgeballs from ' . $name . '!',
        'from_email' => $fromEmail,
        'from_name' => 'Rosetta Dodgeball Tournament',
        'to' => array(
            array(
                'email' => $emailTo,
                'type' => 'to'
            )
        ),
        'headers' => array('Reply-To' => 'connorwnielsen@gmail.com'),
        'important' => false,
        'track_opens' => null,
        'track_clicks' => null,
        'auto_text' => null,
        'auto_html' => null,
        'inline_css' => null,
        'url_strip_qs' => null,
        'preserve_recipients' => null,
        'view_content_link' => null,
        'tracking_domain' => null,
        'signing_domain' => null,
        'return_path_domain' => null,
    	);

		$async = false;
    		$result = $mandrill->messages->sendTemplate($template_name, $template_content, $message, $async);
		//print_r($result);
		if(!isset($result['reject_reason'])){
			return 1;
		} else {
			return 0;
		}



	} catch(Mandrill_Error $e) {
    		// Mandrill errors are thrown as exceptions
   		echo 'A mandrill error occurred: ' . get_class($e) . ' - ' . $e->getMessage();
    		// A mandrill error occurred: Mandrill_Unknown_Subaccount - No subaccount exists with the id 'customer-123'
    		throw $e;
	}

}

?>
