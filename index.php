<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Lights dashboard</title>
    
    <!-- Bootstrap -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
      <![endif]-->
  </head>
  <body>
    
    <?php
    //to keep it simple using require
    require 'Milight.php';
    date_default_timezone_set('Europe/Berlin');
    echo date("H:i:s",time());
    echo date("D M d Y"). ', sunset time : ' .date_sunset(time(), SUNFUNCS_RET_STRING, 48, 11, 90, 1);
    echo time()>date_sunset(time(), SUNFUNCS_RET_STRING, 48, 11, 90, 1);
    
    $lounge = new Milight('192.168.1.7');
    $hallway = new Milight('192.168.1.8');
    $kitchen = new Milight('192.168.1.9');
    $allrooms = array($lounge, $hallway, $kitchen);

    if (strpos($_GET["action"],'hallway') !== false) {
      $rooms = array($hallway);
    }
    elseif (strpos($_GET["action"],'all') !== false) {
      $rooms = $allrooms;
    }
    elseif (strpos($_GET["action"],'lounge') !== false) {
      $rooms = array($lounge);
    }
    elseif (strpos($_GET["action"],'kitchen') !== false) {
      $rooms = array($kitchen);
    }
    else {
      
      if ($_GET["action"] == "disco") {
	foreach ($allrooms as &$room) {
	  $room->rgbwSetActiveGroup(0);
	  $room->rgbwDiscoMode();
	}
      }
      if ($_GET["action"] == "tv") {
	for ($bulb=1; $bulb<5; $bulb++) {
	  $lounge->rgbwSetActiveGroup($bulb);
	  $lounge->rgbwSetColorHexString(sprintf('#%06X', mt_rand(0, 0xFFFFFF)));
	  $lounge->rgbwBrightnessPercent(50);
	}
	$hallway->rgbwAllOff();
	$hallway->rgbwAllOff();
	$hallway->rgbwSetActiveGroup(1);
	$hallway->rgbwSetColorHexString(sprintf('#%06X', mt_rand(0, 0xFFFFFF)));
	$hallway->rgbwBrightnessPercent(25);
	$kitchen->rgbwAllOff();
	$kitchen->rgbwAllOff();
	$kitchen->rgbwGroup1SetToWhite();
	$kitchen->rgbwSetActiveGroup(1);
	$kitchen->rgbwBrightnessPercent(25);
      }
      if ($_GET["action"] == "cooking") {
	$kitchen->rgbwAllOn();
	$kitchen->rgbwAllSetToWhite();
	$kitchen->rgbwSetActiveGroup(0);
	$kitchen->rgbwBrightnessPercent(100);
	for ($bulb=1; $bulb<5; $bulb++) {
	  $lounge->rgbwSetActiveGroup($bulb);
	  $lounge->rgbwSetColorHexString(sprintf('#%06X', mt_rand(0, 0xFFFFFF)));
	  $lounge->rgbwBrightnessPercent(50);
	}
	$hallway->rgbwAllOff();
	$hallway->rgbwAllOff();
	$hallway->rgbwSetActiveGroup(1);
	$hallway->rgbwSetColorHexString(sprintf('#%06X', mt_rand(0, 0xFFFFFF)));
	$hallway->rgbwBrightnessPercent(25);
      }
    }
    if (strpos($_GET["action"],"on") !== false) {
      foreach ($rooms as &$room) {
	$room->rgbwAllOn();
      }
    }
    elseif (strpos($_GET["action"],"off") !== false) {
      foreach ($rooms as &$room) {
	$room->rgbwAllOff();
	$room->rgbwAllOff();
      }
    }
    elseif (strpos($_GET["action"],"white") !== false) {
      foreach ($rooms as &$room) {
	$room->rgbwAllSetToWhite();
      }
    }
    elseif (strpos($_GET["action"],"random") !== false) {
      foreach ($rooms as &$room) {
	for ($bulb=1; $bulb<5; $bulb++) {
	  $room->rgbwSetActiveGroup($bulb);
	  $room->rgbwSetColorHexString(sprintf('#%06X', mt_rand(0, 0xFFFFFF)));
	}
      }
    }
    elseif (strpos($_GET["action"],"25") !== false) {
      foreach ($rooms as &$room) {
	$room->rgbwSetActiveGroup(0);
	$room->rgbwBrightnessPercent(25);
      }
    }
    elseif (strpos($_GET["action"],"50") !== false) {
      foreach ($rooms as &$room) {
	$room->rgbwSetActiveGroup(0);
	$room->rgbwBrightnessPercent(50);
      }
    }
    elseif (strpos($_GET["action"],"75") !== false) {
      foreach ($rooms as &$room) {
	$room->rgbwSetActiveGroup(0);
	$room->rgbwBrightnessPercent(75);
      }
    }
    elseif (strpos($_GET["action"],"100") !== false) {
      foreach ($rooms as &$room) {
	$room->rgbwSetActiveGroup(0);
	$room->rgbwBrightnessPercent(100);
      }
    }

if ($_GET["action"] == "sofa_on") {
    $lounge->rgbwGroup1On();
} elseif ($_GET["action"] == "sofa_off") {
    $lounge->rgbwGroup1Off();
} elseif ($_GET["action"] == "sofa_white") {
    $lounge->rgbwGroup1SetToWhite();
    $lounge->rgbwGroup1SetToWhite();
} elseif ($_GET["action"] == "sofa_random") {
    $lounge->rgbwSetActiveGroup(1);
    $lounge->rgbwSetColorHexString(sprintf('#%06X', mt_rand(0, 0xFFFFFF)));
} elseif ($_GET["action"] == "sofa_25") {
    $lounge->rgbwSetActiveGroup(1);
    $lounge->rgbwBrightnessPercent(25);
} elseif ($_GET["action"] == "sofa_50") {
    $lounge->rgbwSetActiveGroup(1);
    $lounge->rgbwBrightnessPercent(50);
} elseif ($_GET["action"] == "sofa_75") {
    $lounge->rgbwSetActiveGroup(1);
    $lounge->rgbwBrightnessPercent(75);
} elseif ($_GET["action"] == "sofa_100") {
    $lounge->rgbwSetActiveGroup(1);
    $lounge->rgbwBrightnessPercent(100);
}

if ($_GET["action"] == "door_on") {
    $lounge->rgbwGroup3On();
} elseif ($_GET["action"] == "door_off") {
    $lounge->rgbwGroup3Off();
} elseif ($_GET["action"] == "door_white") {
    $lounge->rgbwGroup3SetToWhite();
    $lounge->rgbwGroup3SetToWhite();
} elseif ($_GET["action"] == "door_random") {
    $lounge->rgbwSetActiveGroup(3);
    $lounge->rgbwSetColorHexString(sprintf('#%06X', mt_rand(0, 0xFFFFFF)));
} elseif ($_GET["action"] == "door_25") {
    $lounge->rgbwSetActiveGroup(3);
    $lounge->rgbwBrightnessPercent(25);
} elseif ($_GET["action"] == "door_50") {
    $lounge->rgbwSetActiveGroup(3);
    $lounge->rgbwBrightnessPercent(50);
} elseif ($_GET["action"] == "door_75") {
    $lounge->rgbwSetActiveGroup(3);
    $lounge->rgbwBrightnessPercent(75);
} elseif ($_GET["action"] == "door_100") {
    $lounge->rgbwSetActiveGroup(3);
    $lounge->rgbwBrightnessPercent(100);
}

if ($_GET["action"] == "desk_on") {
    $lounge->rgbwGroup2On();
} elseif ($_GET["action"] == "desk_off") {
    $lounge->rgbwGroup2Off();
} elseif ($_GET["action"] == "desk_white") {
    $lounge->rgbwGroup2SetToWhite();
    $lounge->rgbwGroup2SetToWhite();
} elseif ($_GET["action"] == "desk_random") {
    $lounge->rgbwSetActiveGroup(2);
    $lounge->rgbwSetColorHexString(sprintf('#%06X', mt_rand(0, 0xFFFFFF)));
} elseif ($_GET["action"] == "desk_25") {
    $lounge->rgbwSetActiveGroup(2);
    $lounge->rgbwBrightnessPercent(25);
} elseif ($_GET["action"] == "desk_50") {
    $lounge->rgbwSetActiveGroup(2);
    $lounge->rgbwBrightnessPercent(50);
} elseif ($_GET["action"] == "desk_75") {
    $lounge->rgbwSetActiveGroup(2);
    $lounge->rgbwBrightnessPercent(75);
} elseif ($_GET["action"] == "desk_100") {
    $lounge->rgbwSetActiveGroup(2);
    $lounge->rgbwBrightnessPercent(100);
}
?>
    <div class="container">
      <div class="well">
        <h3>General modes</h3>
        
        <a href="index.php?action=tv" class="btn btn-primary">TV</a>
        <a href="index.php?action=cooking" class="btn btn-primary">Cooking</a>
        <a href="index.php?action=disco" class="btn btn-primary">Disco</a>
        <a href="index.php?action=all_off" class="btn btn-primary">Off</a>
      </div>  
      <div class="well">
        <h3>All lights</h3>
        
        <a href="index.php?action=all_on" class="btn btn-primary">On</a>
        <a href="index.php?action=all_white" class="btn btn-primary">White</a>
        <a href="index.php?action=all_random" class="btn btn-primary">Random</a>
        <a href="index.php?action=all_off" class="btn btn-primary">Off</a>
        <div class="btn-group">
          <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
            Brightness <span class="caret"></span>
          </button> 
          <ul class="dropdown-menu" role="menu">
            <li><a href="index.php?action=all_25">25%</a></li>
            <li><a href="index.php?action=all_50">50%</a></li>
            <li><a href="index.php?action=all_75">75%</a></li>
            <li><a href="index.php?action=all_100">100%</a></li>
          </ul>
        </div>
      
        <h3>Lounge lights</h3>
        
        <a href="index.php?action=lounge_on" class="btn btn-primary">On</a>
        <a href="index.php?action=lounge_white" class="btn btn-primary">White</a>
        <a href="index.php?action=lounge_random" class="btn btn-primary">Random</a>
        <a href="index.php?action=lounge_off" class="btn btn-primary">Off</a>
        <div class="btn-group">
          <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
            Brightness <span class="caret"></span>
          </button>
          <ul class="dropdown-menu" role="menu">
            <li><a href="index.php?action=lounge_25">25%</a></li>
            <li><a href="index.php?action=lounge_50">50%</a></li>
            <li><a href="index.php?action=lounge_75">75%</a></li>
            <li><a href="index.php?action=lounge_100">100%</a></li>
          </ul>
        </div>

      <h3>Hallway lights</h3>
      
      <a href="index.php?action=hallway_on" class="btn btn-primary">On</a>
      <a href="index.php?action=hallway_white" class="btn btn-primary">White</a>
      <a href="index.php?action=hallway_random" class="btn btn-primary">Random</a>
      <a href="index.php?action=hallway_off" class="btn btn-primary">Off</a>
      <div class="btn-group">
        <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
          Brightness <span class="caret"></span>
        </button>
        <ul class="dropdown-menu" role="menu">
          <li><a href="index.php?action=hallway_25">25%</a></li>
          <li><a href="index.php?action=hallway_50">50%</a></li>
          <li><a href="index.php?action=hallway_75">75%</a></li>
          <li><a href="index.php?action=hallway_100">100%</a></li>
        </ul>
      </div>

      <h3>Kitchen lights</h3>
      
      <a href="index.php?action=kitchen_on" class="btn btn-primary">On</a>
      <a href="index.php?action=kitchen_white" class="btn btn-primary">White</a>
      <a href="index.php?action=kitchen_random" class="btn btn-primary">Random</a>
      <a href="index.php?action=kitchen_off" class="btn btn-primary">Off</a>
      <div class="btn-group">
        <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
          Brightness <span class="caret"></span>
        </button>
        <ul class="dropdown-menu" role="menu">
          <li><a href="index.php?action=kitchen_25">25%</a></li>
          <li><a href="index.php?action=kitchen_50">50%</a></li>
          <li><a href="index.php?action=kitchen_75">75%</a></li>
          <li><a href="index.php?action=kitchen_100">100%</a></li>
        </ul>
      </div>
    </div>
          
  <h3>Sofa light</h3>
    
    <a href="index.php?action=sofa_on" class="btn btn-primary">On</a>
    <a href="index.php?action=sofa_white" class="btn btn-primary">White</a>
    <a href="index.php?action=sofa_random" class="btn btn-primary">Random</a>
    <a href="index.php?action=sofa_off" class="btn btn-primary">Off</a>
      <div class="btn-group">
  <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
    Brightness <span class="caret"></span>
  </button>
  <ul class="dropdown-menu" role="menu">
    <li><a href="index.php?action=sofa_25">25%</a></li>
    <li><a href="index.php?action=sofa_50">50%</a></li>
    <li><a href="index.php?action=sofa_75">75%</a></li>
    <li><a href="index.php?action=sofa_100">100%</a></li>
  </ul>
      </div>

    <h3>Door light</h3>
    
    <a href="index.php?action=door_on" class="btn btn-primary">On</a>
    <a href="index.php?action=door_white" class="btn btn-primary">White</a>
    <a href="index.php?action=door_random" class="btn btn-primary">Random</a>
    <a href="index.php?action=door_off" class="btn btn-primary">Off</a>
      <div class="btn-group">
  <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
    Brightness <span class="caret"></span>
  </button>
  <ul class="dropdown-menu" role="menu">
    <li><a href="index.php?action=door_25">25%</a></li>
    <li><a href="index.php?action=door_50">50%</a></li>
    <li><a href="index.php?action=door_75">75%</a></li>
    <li><a href="index.php?action=door_100">100%</a></li>
  </ul>
      </div>

    <h3>Desk light</h3>
    
    <a href="index.php?action=desk_on" class="btn btn-primary">On</a>
    <a href="index.php?action=desk_white" class="btn btn-primary">White</a>
    <a href="index.php?action=desk_random" class="btn btn-primary">Random</a>
    <a href="index.php?action=desk_off" class="btn btn-primary">Off</a>
      <div class="btn-group">
  <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
    Brightness <span class="caret"></span>
  </button>
  <ul class="dropdown-menu" role="menu">
    <li><a href="index.php?action=desk_25">25%</a></li>
    <li><a href="index.php?action=desk_50">50%</a></li>
    <li><a href="index.php?action=desk_75">75%</a></li>
    <li><a href="index.php?action=desk_100">100%</a></li>
  </ul>
      </div>
    </div>
    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="js/bootstrap.min.js"></script>
  </body>
</html>

