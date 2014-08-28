<?php
  $authorized = FALSE;
  $salt = "fc0586aca6e42cffade83252446d0613";
  if (isset($_SERVER['PHP_AUTH_USER']) && isset($_SERVER['PHP_AUTH_PW'])) {
    $username = $_SERVER['PHP_AUTH_USER'];
    $password = $_SERVER['PHP_AUTH_PW'  ];
    if (md5($username . $salt)=="ffaced3317fb75964fa5a5885f28ab82" && md5($password . $salt) =="65d9e002f25e076be02a41d423e59be9") {
      $authorized = TRUE;
    }  
  }

  //EXIT IF NOT AUTHORIZED.
  if (!$authorized) {
    header('WWW-Authenticate: Basic Realm="Authentication"');
    header("HTTP/1.1 401 Unauthorized");
    exit;
  }
  
  require_once("../driver.php");

  //ACCESS GRANTED.
  $content = "";

  if(!isset($_GET['q'])) {
    //HOME

    $open = getOpenGames($mysqli);
    $closed = getClosedGames($mysqli);

    $content = "<h2 class='stats'><span>" . $open . "</span> open games</h2>";
    $content .= "<h2 class='stats'><span>" . $closed . "</span> closed games</h2>";

  }

  if(isset($_GET['q']) && $_GET['q'] == "tokengen") {
    //TOKENGEN

    $protocol = strpos(strtolower($_SERVER['SERVER_PROTOCOL']),'https') 
                === FALSE ? 'http' : 'https';
    $host     = $_SERVER['HTTP_HOST'];
    $currentUrl = $protocol . '://' . $host . '/intern/dodge/?game=';
    $tokengen = $currentUrl . generateTokenForAdmin($mysqli);
  }

?>


<?php if($authorized): ?>

<!DOCTYPE html>
<html class="" lang="en">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <title>Dominate, Dodge &amp; Donate Tournament</title>
    <link rel="stylesheet" href="../../assets/css/main.css" />
    <script type="text/javscript">

    </script>
  </head>
  <body>
    <div class="admin">
      <h1>administrare</h1>
      <div class="amenu">
        <a href="?">Stats</a>
        <a href="?q=tokengen">Spawn Token</a>
      </div>
      <div class="content">
        <?php if($tokengen):?>
          <div class="token">
            <h2>Copy this link into your browser</h2>
            <div onClick="document.getElementById('toke').select();">
              <input type="text" disabled id="toke" value="<?php print $tokengen; ?>"/>
            </div>
          </div>
        <?php endif;?>
        <?php if($content) { print $content; }?>
      </div>
    </div>
  </body>
 </html>
<?php endif;?>
