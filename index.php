<!DOCTYPE html>
<html class="" lang="en">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <title>Dominate, Dodge &amp; Donate Tournament</title>
    <link rel="stylesheet" href="assets/css/main.css" />
    <link rel="stylesheet" href="assets/js/libs/owl/owl.carousel.css" />

    <script> var TOKEN = <?php if(isset($_GET["game"])){ print "'" . $_GET["game"] . "'"; } else { print "null"; } ?></script>

  </head>
  <body>
    <div class="cracked hide"></div>
    <div class="ball-y">
      <div class="ball-x">
        <div class="ball"></div>
      </div>
    </div>
    <div class="dodges">DODGES<span></span></div>
    <div class="help hide">
    <div class="help-inner">
      <h2 class="mobile hide">ROTATE DEVICE TO DODGE</h2>
      <h3 class="mobile hide">LOCK YOUR PHONE ORIENTATION</h3>
      <h2 class="desktop hide">USE ARROW KEYS TO DODGE</h2>
      <img class="mobile hide" src="assets/images/chalkdevice.png" alt="mobile help" />
      <img class="desktop hide" src="assets/images/chalkdesktop.png" alt="mobile help" />
      <a href="#" class="tapToHideHelp">GOT IT!</a>

      <div class="throwing hide">
        <div class="throwlist">
        </div>
        <div class="throwmore hide">+
        </div>
        <div class="throwchoose">
          <h2>Click the paths below to add to your dodgeball throw list.</h2>
          <div class="throwcenter">
            <div class="throwitem x" data-throw="x"></div>
            <div class="throwitem l" data-throw="l"></div>
            <div class="throwitem c" data-throw="c"></div>
            <div class="throwitem t" data-throw="t"></div>
            <div class="throwitem r" data-throw="r"></div>
            <div class="throwitem y" data-throw="y"></div>
          </div>
        </div>
      </div>
    </div> 
  </div>
  <div class="dude-wrap">
              <div class="dude hide">
                <div class="torso fr center">
                </div>
                <div class="legs">
                </div>
              </div>
            </div>
    <div class="wrap-outer">
    <div class="wrap">
      <div class="shell">
        <div class="content-inner">
          <div class="menu">
          <a class="tapToStartGameFromPractice">I'M READY FOR REALITY</a>
          <div class="challenged"></div>
            <ul>
              <li><a href="#">THE GAME</a></li>
              <li><a href="#">EVENT INFO</a></li>
              <li><a href="#">RULES</a></li>
              <li><a href="#">BRACKET</a></li>
            </ul>
          </div>
          <div class="banner">
          </div>
          <div class="bannermenu">
            <h3>You can always practice before you dodge friends' throws.</h3> 
            <ul>
              <li class="practice">PRACTICE</li>
              <li class="ready">I'M READY TO DODGE</li>
            </ul>
          </div>
          <div class="sdude-outer">
            <div class="sdude throw">
            </div>
          </div>
          </div>
        <div class="court">
        </div>
      </div>
    </div>
    </div>

    <!-- load js libs here -->

    <script src="assets/js/libs/jquery-1.11.1.js"></script>
    <script src="assets/js/libs/underscore-min.js"></script>
    <script src="http://ajax.cdnjs.com/ajax/libs/json2/20110223/json2.js"></script>
    <script src="assets/js/libs/owl/owl.carousel.js"></script>

    <!-- load js src here -->
    <script src="assets/js/src/app.js"></script>
  </body>
</html>