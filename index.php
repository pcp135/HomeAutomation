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

    <?php function RoomBlockHTML ($replStr) { ob_start(); ?>
    <h3><?php echo ($replStr) ?></h3>
    <?php $actions=array('On','White','Random','Off');
    foreach ($actions as &$action): ?>
    <a href="index.php?action=
      <?php echo str_replace(' ', '',strtolower($replStr)).'_'.strtolower($action) ?>"
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
	      <?php echo str_replace(' ', '', strtolower($replStr)).'_'.$level ?>
		   "><?php echo $level ?>%</a></li>
	<?php endforeach; ?>
          </ul>
        </div>
	<?php
	return ob_get_clean();
	} ?>
	
    
    <?php
    require 'Controllable.php';
    
    $lounge = new Controllable("RGBWMilight",'192.168.1.7');
    $hallway = new Controllable("RGBWMilight",'192.168.1.8');
    $kitchen = new Controllable("RGBWMilight",'192.168.1.9');
    $balcony = new Controllable("Orvibo", '192.168.1.10','10000',
				array(0xAC,0xCF,0x23,0x4F,0x09,0x0C));   
    $allrooms = array($lounge, $hallway, $kitchen, $balcony);

    class Obj {
      public $trigger;
      public $controllers;
      public $group;

      public function __construct($trigger, $controllers, $group) {
	$this->trigger = $trigger;
	$this->controllers = $controllers;
	$this->group = $group;
      }
    }

    $items[] =  new Obj('all_', $allrooms, 0);
    $items[] =  new Obj('kitchen_', array($kitchen), 0);
    $items[] =  new Obj('hightable_', array($kitchen), 1);
    $items[] =  new Obj('diningtable_', array($kitchen), 2);
    $items[] =  new Obj('sink_', array($kitchen), 3);
    $items[] =  new Obj('fridge_', array($kitchen), 4);
    $items[] =  new Obj('lounge_', array($lounge, $balcony), 0);
    $items[] =  new Obj('balcony_', array($balcony), 0);
    $items[] =  new Obj('sofa_', array($lounge), 1);
    $items[] =  new Obj('loungedoor_', array($lounge), 2);
    $items[] =  new Obj('desk_', array($lounge), 3);
    $items[] =  new Obj('sidecupboards_', array($lounge), 4);
    $items[] =  new Obj('hallway_', array($hallway), 0);
    $items[] =  new Obj('frontdoor_', array($hallway), 1);
    $items[] =  new Obj('pictureright_', array($hallway), 2);
    $items[] =  new Obj('pictureleft_', array($hallway), 3);
    $items[] =  new Obj('halllights_', array($hallway), 4);
    
    foreach ($items as &$item) {
      if (strpos($_GET["action"],$item->trigger) !== false) {
	$rooms = $item->controllers;
	$group = $item->group;
	if (strpos($_GET["action"],"on") !== false) {
	  foreach ($rooms as &$room) {
	    $room->sendOn($group);
	  }
	}
	elseif (strpos($_GET["action"],"off") !== false) {
	  foreach ($rooms as &$room) {
	    $room->sendOff($group);
	  }
	}
	elseif (strpos($_GET["action"],"white") !== false) {
	  foreach ($rooms as &$room) {
	    $room->setWhite($group);
	  }
	}
	elseif (strpos($_GET["action"],"random") !== false) {
	  foreach ($rooms as &$room) {
	    if ($group == 0) {
	      for ($bulb=1; $bulb<5; $bulb++) {
		$room->setRandom($bulb);
	      }
	    }
	    else $room->setRandom($group);
	  }
	}
	$brightnesses=array("20","40","60","80","100");
	foreach ($brightnesses as &$brightness) {
	  if (strpos($_GET["action"],$brightness) !== false) {
	    foreach ($rooms as &$room) {
	      $room->setBrightness($group, (int)$brightness);
	    }
	  }
	}
      }
    }

    if ($_GET["action"] == "disco") {
      foreach ($allrooms as &$room) {
	$room->discoMode(0);
      }
    }
    if (time()>date_sunset(time(), SUNFUNCS_RET_TIMESTAMP, 48, 11, 90, 1)) {
      if ($_GET["action"] == "tv") {
	for ($bulb=1; $bulb<5; $bulb++) {
	  $lounge->setRandom($bulb);
	}
	$lounge->setBrightness(0, 50);
	$hallway->sendOff(0);
	$hallway->setRandom(1);
	$hallway->setBrightness(1,25);
	$kitchen->sendOff(0);
	$kitchen->setWhite(1);
	$kitchen->setBrightness(1,25);
	$balcony->sendOn(0);
      }
      if ($_GET["action"] == "cooking") {
	$kitchen->setWhite(0);
	$kitchen->setBrightness(0,100);
	for ($bulb=1; $bulb<5; $bulb++) {
	  $lounge->setRandom($bulb);
	}
	$lounge->setBrightness(0, 50);
	$hallway->sendOff(0);
	$hallway->setRandom(1);
	$hallway->setBrightness(1,25);
	$balcony->sendOn(0);
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
	$rooms=array("All","Lounge","Hallway","Kitchen");
	foreach ($rooms as &$room) {
	  echo RoomBlockHTML($room);
	}
	?>
      </div>
      <div class="well">
	<?php
	$individual_lights=array('Sofa','Lounge Door','Desk','Side Cupboards','High Table','Dining Table', 'Sink', 'Fridge', 'Front Door','Picture Left','Picture Right','Hall Lights');
	foreach ($individual_lights as &$bulb) {
	  echo RoomBlockHTML($bulb);
	}
	?>
      </div>
    </div>
    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="js/bootstrap.min.js"></script>
  </body>
</html>

