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
    
    $lounge = new Milight('192.168.1.7');
    $hallway = new Milight('192.168.1.8');
    $kitchen = new Milight('192.168.1.9');
    $allrooms = array($lounge, $hallway, $kitchen);

    if (strpos($_GET["action"],'hallway') !== false) {
      $rooms = array($hallway);
      $group = 0;
    }
    elseif (strpos($_GET["action"],'all') !== false) {
      $rooms = $allrooms;
      $group = 0;
    }
    elseif (strpos($_GET["action"],'loungedoor') !== false) {
      $rooms = array($lounge);
      $group = 2;
    }
    elseif (strpos($_GET["action"],'lounge') !== false) {
      $rooms = array($lounge);
      $group = 0;
    }
    elseif (strpos($_GET["action"],'kitchen') !== false) {
      $rooms = array($kitchen);
      $group = 0;
    }

    elseif (strpos($_GET["action"],'hightable') !== false) {
      $rooms = array($kitchen);
      $group = 1;
    }
    elseif (strpos($_GET["action"],'diningtable') !== false) {
      $rooms = array($kitchen);
      $group = 2;
    }
    elseif (strpos($_GET["action"],'sink') !== false) {
      $rooms = array($kitchen);
      $group = 3;
    }
    elseif (strpos($_GET["action"],'fridge') !== false) {
      $rooms = array($kitchen);
      $group = 4;
    }

    elseif (strpos($_GET["action"],'frontdoor') !== false) {
      $rooms = array($hallway);
      $group = 1;
    }
    elseif (strpos($_GET["action"],'pictureright') !== false) {
      $rooms = array($hallway);
      $group = 2;
    }
    elseif (strpos($_GET["action"],'pictureleft') !== false) {
      $rooms = array($hallway);
      $group = 3;
    }
    elseif (strpos($_GET["action"],'halllights') !== false) {
      $rooms = array($hallway);
      $group = 4;
    }

    elseif (strpos($_GET["action"],'sofa') !== false) {
      $rooms = array($lounge);
      $group = 1;
    }
    elseif (strpos($_GET["action"],'desk') !== false) {
      $rooms = array($lounge);
      $group = 3;
    }
    elseif (strpos($_GET["action"],'sidecupboards') !== false) {
      $rooms = array($lounge);
      $group = 4;
    }

    else {
      
      if ($_GET["action"] == "disco") {
	foreach ($allrooms as &$room) {
	  $room->rgbwSetActiveGroup(0);
	  $room->rgbwDiscoMode();
	}
      }
      if (time()>date_sunset(time(), SUNFUNCS_RET_TIMESTAMP, 48, 11, 90, 1)) {
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
    }
    if (strpos($_GET["action"],"on") !== false) {
      foreach ($rooms as &$room) {
	$room->rgbwSendOnToGroup($group);
      }
    }
    elseif (strpos($_GET["action"],"off") !== false) {
      foreach ($rooms as &$room) {
	$room->rgbwSendOffToGroup($group);
      }
    }
    elseif (strpos($_GET["action"],"white") !== false) {
      foreach ($rooms as &$room) {
	$room->rgbwSetGroupToWhite($group);
      }
    }
    elseif (strpos($_GET["action"],"random") !== false) {
      foreach ($rooms as &$room) {
	if ($group == 0) {
	  for ($bulb=1; $bulb<5; $bulb++) {
	    $room->rgbwSetActiveGroup($bulb);
	    $room->rgbwSetColorHexString(sprintf('#%06X', mt_rand(0, 0xFFFFFF)));
	  }
	}
	else {
	  $room->rgbwSetActiveGroup($group);
	  $room->rgbwSetColorHexString(sprintf('#%06X', mt_rand(0, 0xFFFFFF)));
	} 
      }
    }
    elseif (strpos($_GET["action"],"20") !== false) {
      foreach ($rooms as &$room) {
	$room->rgbwSetActiveGroup($group);
	$room->rgbwBrightnessPercent(20);
      }
    }
    elseif (strpos($_GET["action"],"40") !== false) {
      foreach ($rooms as &$room) {
	$room->rgbwSetActiveGroup($group);
	$room->rgbwBrightnessPercent(40);
      }
    }
    elseif (strpos($_GET["action"],"60") !== false) {
      foreach ($rooms as &$room) {
	$room->rgbwSetActiveGroup($group);
	$room->rgbwBrightnessPercent(60);
      }
    }
    elseif (strpos($_GET["action"],"80") !== false) {
      foreach ($rooms as &$room) {
	$room->rgbwSetActiveGroup($group);
	$room->rgbwBrightnessPercent(80);
      }
    }
    elseif (strpos($_GET["action"],"100") !== false) {
      foreach ($rooms as &$room) {
	$room->rgbwSetActiveGroup($group);
	$room->rgbwBrightnessPercent(100);
      }
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
	<?php
	$rooms=array("All","Lounge","Hallway","Kitchen",'Sofa','Lounge Door','Desk','Side Cupboards','High Table','Dining Table', 'Sink', 'Fridge', 'Front Door','Picture Left','Picture Right','Hall Lights');
	foreach ($rooms as &$room): ?>
	<h3><?php echo $room; ?></h3>
	<?php $actions=array('On','White','Random','Off');
	foreach ($actions as &$action): ?>
        <a href="index.php?action=
	  <?php echo str_replace(' ', '',strtolower($room)).'_'.strtolower($action) ?>"
	   class="btn btn-primary"><?php echo $action ?></a>
	<?php endforeach; ?>
        <div class="btn-group">
          <button type="button" class="btn btn-primary dropdown-toggle"
		  data-toggle="dropdown" aria-expanded="false">
	    Brightness <span class="caret"></span>
          </button> 
          <ul class="dropdown-menu" role="menu">
	    <?php $levels=array('20','40','60','80', '100');
	    foreach ($levels as &$level): ?>
	    <li><a href="index.php?action=
	      <?php echo str_replace(' ', '', strtolower($room)).'_'.$level ?>
		   "><?php echo $level ?>%</a></li>
	<?php endforeach; ?>
          </ul>
        </div>
	<?php endforeach; ?>
      </div>
    </div>
    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="js/bootstrap.min.js"></script>
  </body>
</html>

