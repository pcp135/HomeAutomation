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

    <?php function milightBlockHTML ($replStr) { ob_start(); ?>
    <h3><?php echo ($replStr) ?></h3>
    <?php $actions=array('On','White','Random','Off','Night Mode');
    foreach ($actions as &$action): ?>
    <a href="index.php?action=<?php
             echo str_replace(' ', '',strtolower($replStr).'_'.strtolower($action))
			      ?>"
       class="btn btn-primary"><?php echo $action ?></a>
	<?php endforeach; ?>
        <div class="btn-group">
          <button type="button" class="btn btn-primary dropdown-toggle"
		  data-toggle="dropdown" aria-expanded="false">
	    Brightness <span class="caret"></span>
          </button> 
          <ul class="dropdown-menu" role="menu">
	    <?php $levels=array('0','10','20','30','40','50','60','70','80','90','100');
	       foreach ($levels as &$level): ?>
	    <li><a href="index.php?action=
	    <?php echo str_replace(' ', '', strtolower($replStr)).'_'.$level ?>
		   "><?php echo $level ?>%</a></li>
	<?php endforeach; ?>
          </ul>
        </div>
	<?php
	return ob_get_clean();
	}
	
	function orviboBlockHTML ($replStr) { ob_start(); ?>
	<h3><?php echo ($replStr) ?></h3>
	<?php $actions=array('On','Off');
	foreach ($actions as &$action): ?>
	<a href="index.php?action=
	  <?php echo str_replace(' ', '',strtolower($replStr)).'_'.strtolower($action) ?>"
	   class="btn btn-primary"><?php echo $action ?></a>
	<?php endforeach; ?>
	<?php
	return ob_get_clean();
	} 
	
    require 'Controllable.php';
	
    $lounge = new Controllable("RGBWMilight",'192.168.1.7');
    $hallway = new Controllable("RGBWMilight",'192.168.1.8');
    $kitchen = new Controllable("RGBWMilight",'192.168.1.9');
    $babyroom = new Controllable("RGBWMilight",'192.168.1.12');
    $bedroom = new Controllable("RGBWMilight",'192.168.1.13');
    $balcony = new Controllable("Orvibo", '192.168.1.10','10000',
				array(0xAC,0xCF,0x23,0x4F,0x09,0x0C));   
    $kitchenfairy = new Controllable("Orvibo", '192.168.1.11','10000',
				array(0xAC,0xCF,0x23,0x4B,0xB5,0xBA));   
    $kitchencounter = new Controllable("Orvibo", '192.168.1.14','10000',
				       array(0xAC,0xCF,0x23,0x53,0x64,0x36));
    $kitchenextractor = new Controllable("Orvibo", '192.168.1.15','10000',
				       array(0xAC,0xCF,0x23,0x53,0x30,0x42));
    $christmastree = new Controllable("Orvibo", '192.168.1.16','10000',
				       array(0xAC,0xCF,0x23,0x53,0x64,0x5a));
    $allrooms = array($lounge, $hallway, $kitchen, $balcony, $kitchenfairy, $babyroom,
		      $bedroom, $kitchencounter, $kitchenextractor, $christmastree);

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
    $items[] =  new Obj('kitchen_', array($kitchen, $kitchenfairy, $kitchencounter,
					  $kitchenextractor), 0);
    $items[] =  new Obj('kitchenfairy_', array($kitchenfairy), 0);
    $items[] =  new Obj('kitchencounter_', array($kitchencounter), 0);
    $items[] =  new Obj('kitchenextractor_', array($kitchenextractor), 0);
    $items[] =  new Obj('hightable_', array($kitchen), 1);
    $items[] =  new Obj('diningtable_', array($kitchen), 2);
    $items[] =  new Obj('sink_', array($kitchen), 3);
    $items[] =  new Obj('fridge_', array($kitchen), 4);
    $items[] =  new Obj('lounge_', array($lounge, $balcony,$christmastree), 0);
    $items[] =  new Obj('balcony_', array($balcony), 0);
    $items[] =  new Obj('christmastree_', array($christmastree), 0);
    $items[] =  new Obj('sofa_', array($lounge), 1);
    $items[] =  new Obj('loungedoor_', array($lounge), 2);
    $items[] =  new Obj('desk_', array($lounge), 3);
    $items[] =  new Obj('sidecupboards_', array($lounge), 4);
    $items[] =  new Obj('hallway_', array($hallway), 0);
    $items[] =  new Obj('frontdoor_', array($hallway), 1);
    $items[] =  new Obj('pictureright_', array($hallway), 2);
    $items[] =  new Obj('pictureleft_', array($hallway), 3);
    $items[] =  new Obj('halllights_', array($hallway), 4);
    $items[] =  new Obj('babyroom_', array($babyroom), 0);
    $items[] =  new Obj('overhead1_', array($babyroom), 1);
    $items[] =  new Obj('overhead2_', array($babyroom), 2);
    $items[] =  new Obj('window_', array($babyroom), 3);
    $items[] =  new Obj('corner_', array($babyroom), 4);
    $items[] =  new Obj('bedroom_', array($bedroom), 0);
    $items[] =  new Obj('bedroom1_', array($bedroom), 1);
    $items[] =  new Obj('bedroom2_', array($bedroom), 2);
    $items[] =  new Obj('bedroom3_', array($bedroom), 3);
    
    foreach ($items as &$item) {
      if (strpos($_GET["action"],$item->trigger) !== false) {
	$rooms = $item->controllers;
	$group = $item->group;
	if (strpos($_GET["action"],"_on") !== false) {
	  foreach ($rooms as &$room) {
	    $room->sendOn($group);
	  }
	}
	elseif (strpos($_GET["action"],"_off") !== false) {
	  foreach ($rooms as &$room) {
	    $room->sendOff($group);
	  }
	}
	elseif (strpos($_GET["action"],"_white") !== false) {
	  foreach ($rooms as &$room) {
	    $room->setWhite($group);
	  }
	}
	elseif (strpos($_GET["action"],"_nightmode") !== false) {
	  foreach ($rooms as &$room) {
	    $room->setNightMode($group);
	  }
	}
	elseif (strpos($_GET["action"],"_random") !== false) {
	  foreach ($rooms as &$room) {
	    if ($group == 0) {
	      for ($bulb=1; $bulb<5; $bulb++) {
		$room->setRandom($bulb);
	      }
	    }
	    else $room->setRandom($group);
	  }
	}
	$levels=array('0','10','20','30','40','50','60','70','80','90','100');
	foreach ($levels as &$brightness) {
	  if (strpos($_GET["action"],'_'.$brightness) !== false) {
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
    if ($_GET["action"] == "nightmode") {
      foreach ($allrooms as &$room) {
	$room->sendOff(0);
      }
      $hallway->setNightMode(1);
      $babyroom->setNightMode(3);
      $bedroom->setNightMode(1);
    }
    if ((time()+60*60)>date_sunset(time(), SUNFUNCS_RET_TIMESTAMP, 48, 11, 90, 1)) {
      if ($_GET["action"] == "tv") {
	for ($bulb=2; $bulb<5; $bulb++) {
	  $lounge->setRandom($bulb);
	}
	$lounge->setWhite(1);
	$lounge->setBrightness(0, 60);
	$hallway->sendOff(0);
	$hallway->setWhite(1);
	$hallway->setBrightness(1,15);
	$kitchen->sendOff(0);
	$kitchen->setWhite(3);
	$kitchen->setBrightness(3,25);
	$balcony->sendOn(0);
	$kitchenfairy->sendOn(0);
	$kitchenextractor->sendOff(0);
	$kitchencounter->sendOn(0);
	$christmastree->sendOn(0);
      }
      if ($_GET["action"] == "cooking") {
	$kitchen->setWhite(0);
	$kitchen->setBrightness(0,100);
	for ($bulb=1; $bulb<5; $bulb++) {
	  $lounge->setRandom($bulb);
	}
	$lounge->setBrightness(0, 60);
	$hallway->sendOff(0);
	$hallway->setWhite(1);
	$hallway->setBrightness(1,40);
	$balcony->sendOn(0);
	$kitchenfairy->sendOn(0);
	$kitchenfairy->sendOn(0);
	$kitchenextractor->sendOn(0);
	$kitchencounter->sendOn(0);
	$christmastree->sendOn(0);
      }
    }

    ?>
    <div class="container">
      <div class="well">
        <h3>General modes</h3>
        <a href="index.php?action=tv" class="btn btn-primary">TV</a>
        <a href="index.php?action=cooking" class="btn btn-primary">Cooking</a>
        <a href="index.php?action=disco" class="btn btn-primary">Disco</a>
        <a href="index.php?action=nightmode" class="btn btn-primary">Night Mode</a>
        <a href="index.php?action=all_off" class="btn btn-primary">Off</a>
      </div>  
	<ul class="nav nav-tabs">
	  <li role="presentation"><a href="#rooms" data-toggle="tab">Rooms</a></li>
	  <li role="presentation"><a href="#lounge" data-toggle="tab">Lounge</a></li>
	  <li role="presentation"><a href="#kitchen" data-toggle="tab">Kitchen</a></li>
	  <li role="presentation"><a href="#hallway" data-toggle="tab">Hallway</a></li>
	  <li role="presentation"><a href="#bedroom" data-toggle="tab">Bedroom</a></li>
	  <li role="presentation"><a href="#babyroom" data-toggle="tab">Baby Room</a></li>
	</ul>
	<div class="tab-content">
	  <div id="rooms" class="tab-pane active">
	    <?php
	    $rooms=array("All","Lounge","Kitchen","Hallway","Bedroom","Baby Room");
	    foreach ($rooms as &$room) echo milightBlockHTML($room);
	    ?>
	  </div>
	  <div id="lounge" class="tab-pane">
	    <?php
	    $individual_lights=array("Lounge",'Sofa','Lounge Door','Desk','Side Cupboards');
	    foreach ($individual_lights as &$bulb) echo milightBlockHTML($bulb);
	    echo orviboBlockHTML("Balcony");
	    echo orviboBlockHTML("Christmas Tree");
	    ?>
	  </div>
	  <div id="kitchen" class="tab-pane">
	    <?php
	    $individual_lights=array("Kitchen",'High Table','Dining Table', 'Sink', 'Fridge');
	    foreach ($individual_lights as &$bulb) echo milightBlockHTML($bulb);
	    echo orviboBlockHTML("Kitchen Fairy");
	    echo orviboBlockHTML("Kitchen Counter");
	    echo orviboBlockHTML("Kitchen Extractor");
	    ?>
	  </div>
	  <div id="hallway" class="tab-pane">
	    <?php
	    $individual_lights=array("Hallway", 'Front Door','Picture Left','Picture Right','Hall Lights');
	    foreach ($individual_lights as &$bulb) echo milightBlockHTML($bulb);
	    ?>
	  </div>
	  <div id="bedroom" class="tab-pane">
	    <?php
	    $individual_lights=array("Bedroom", 'Bedroom 1','Bedroom 2','Bedroom 3');
	    foreach ($individual_lights as &$bulb) echo milightBlockHTML($bulb);
	    ?>
	  </div>
	  <div id="babyroom" class="tab-pane">
	    <?php
	    $individual_lights=array("Baby Room", 'Overhead 1','Overhead 2','Window','Corner');
	    foreach ($individual_lights as &$bulb) echo milightBlockHTML($bulb);
	    ?>
	  </div>
	</div>
      </div>

    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="js/bootstrap.min.js"></script>
    <script>
     $('#myTab a').click(function (e) {
       e.preventDefault()
       $(this).tab('show')
     })

     $(function() { 
       //for bootstrap 3 use 'shown.bs.tab' instead of 'shown' in the next line
       $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
	 //save the latest tab; use cookies if you like 'em better:
	      localStorage.setItem('lastTab', $(this).attr('href'));
       });
      
      //go to the latest tab, if it exists:
       var lastTab = localStorage.getItem('lastTab');
       if (lastTab) {
	 $('a[href=' + lastTab + ']').tab('show');
       }
       else {
	 $('a[data-toggle="tab"]:first').tab('show');
       }
     });
    </script>

  </body>
</html>

