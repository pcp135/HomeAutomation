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
       
       $milight = new Milight('192.168.1.7');


       if ($_GET["action"]=="all_on") {
       $milight->rgbwAllOn();
      }
      elseif ($_GET["action"]=="all_off") {
      $milight->rgbwAllOff();
      }
      elseif ($_GET["action"]=="all_white") {
      $milight->rgbwAllSetToWhite();
      }
      elseif ($_GET["action"]=="all_random") {
      $milight->rgbwSetActiveGroup(1);
      $milight->rgbwSetColorHexString(sprintf('#%06X', mt_rand(0, 0xFFFFFF)));
      $milight->rgbwSetActiveGroup(2);
      $milight->rgbwSetColorHexString(sprintf('#%06X', mt_rand(0, 0xFFFFFF)));
      $milight->rgbwSetActiveGroup(3);
      $milight->rgbwSetColorHexString(sprintf('#%06X', mt_rand(0, 0xFFFFFF)));
      }
      elseif ($_GET["action"]=="all_25") {
      $milight->rgbwSetActiveGroup(0);
      $milight->rgbwBrightnessPercent(25);
      }
      elseif ($_GET["action"]=="all_50") {
      $milight->rgbwSetActiveGroup(0);
      $milight->rgbwBrightnessPercent(50);
      }
      elseif ($_GET["action"]=="all_75") {
      $milight->rgbwSetActiveGroup(0);
      $milight->rgbwBrightnessPercent(75);
      }
      elseif ($_GET["action"]=="all_100") {
      $milight->rgbwSetActiveGroup(0);
      $milight->rgbwBrightnessPercent(100);
      }

      if ($_GET["action"]=="sofa_on") {
       $milight->rgbwGroup1On();
      }
      elseif ($_GET["action"]=="sofa_off") {
      $milight->rgbwGroup1Off();
      }
      elseif ($_GET["action"]=="sofa_white") {
      $milight->rgbwGroup1SetToWhite();
      }
      elseif ($_GET["action"]=="sofa_random") {
      $milight->rgbwSetActiveGroup(1);
      $milight->rgbwSetColorHexString(sprintf('#%06X', mt_rand(0, 0xFFFFFF)));
      }
      elseif ($_GET["action"]=="sofa_25") {
      $milight->rgbwSetActiveGroup(1);
      $milight->rgbwBrightnessPercent(25);
      }
      elseif ($_GET["action"]=="sofa_50") {
      $milight->rgbwSetActiveGroup(1);
      $milight->rgbwBrightnessPercent(50);
      }
      elseif ($_GET["action"]=="sofa_75") {
      $milight->rgbwSetActiveGroup(1);
      $milight->rgbwBrightnessPercent(75);
      }
      elseif ($_GET["action"]=="sofa_100") {
      $milight->rgbwSetActiveGroup(1);
      $milight->rgbwBrightnessPercent(100);
      }

      if ($_GET["action"]=="side_on") {
       $milight->rgbwGroup3On();
      }
      elseif ($_GET["action"]=="side_off") {
      $milight->rgbwGroup3Off();
      }
      elseif ($_GET["action"]=="side_white") {
      $milight->rgbwGroup3SetToWhite();
      }
      elseif ($_GET["action"]=="side_random") {
      $milight->rgbwSetActiveGroup(3);
      $milight->rgbwSetColorHexString(sprintf('#%06X', mt_rand(0, 0xFFFFFF)));
      }
      elseif ($_GET["action"]=="side_25") {
      $milight->rgbwSetActiveGroup(3);
      $milight->rgbwBrightnessPercent(25);
      }
      elseif ($_GET["action"]=="side_50") {
      $milight->rgbwSetActiveGroup(3);
      $milight->rgbwBrightnessPercent(50);
      }
      elseif ($_GET["action"]=="side_75") {
      $milight->rgbwSetActiveGroup(3);
      $milight->rgbwBrightnessPercent(75);
      }
      elseif ($_GET["action"]=="side_100") {
      $milight->rgbwSetActiveGroup(3);
      $milight->rgbwBrightnessPercent(100);
      }

      if ($_GET["action"]=="desk_on") {
       $milight->rgbwGroup2On();
      }
      elseif ($_GET["action"]=="desk_off") {
      $milight->rgbwGroup2Off();
      }
      elseif ($_GET["action"]=="desk_white") {
      $milight->rgbwGroup2SetToWhite();
      }
      elseif ($_GET["action"]=="desk_random") {
      $milight->rgbwSetActiveGroup(2);
      $milight->rgbwSetColorHexString(sprintf('#%06X', mt_rand(0, 0xFFFFFF)));
      }
      elseif ($_GET["action"]=="desk_25") {
      $milight->rgbwSetActiveGroup(2);
      $milight->rgbwBrightnessPercent(25);
      }
      elseif ($_GET["action"]=="desk_50") {
      $milight->rgbwSetActiveGroup(2);
      $milight->rgbwBrightnessPercent(50);
      }
      elseif ($_GET["action"]=="desk_75") {
      $milight->rgbwSetActiveGroup(2);
      $milight->rgbwBrightnessPercent(75);
      }
      elseif ($_GET["action"]=="desk_100") {
      $milight->rgbwSetActiveGroup(2);
      $milight->rgbwBrightnessPercent(100);
      }



      
      ?>
    
    <h1>All lights</h1>
    
    <a href="index.php?action=all_on" class="btn btn-primary">On</a>
    <a href="index.php?action=all_off" class="btn btn-primary">Off</a>
    <a href="index.php?action=all_white" class="btn btn-primary">White</a>
    <a href="index.php?action=all_random" class="btn btn-primary">Random</a>
    <a href="index.php?action=all_25" class="btn btn-primary">25%</a>
    <a href="index.php?action=all_50" class="btn btn-primary">50%</a>
    <a href="index.php?action=all_75" class="btn btn-primary">75%</a>
    <a href="index.php?action=all_100" class="btn btn-primary">100%</a>

        <h1>Sofa light</h1>
    
    <a href="index.php?action=sofa_on" class="btn btn-primary">On</a>
    <a href="index.php?action=sofa_off" class="btn btn-primary">Off</a>
    <a href="index.php?action=sofa_white" class="btn btn-primary">White</a>
    <a href="index.php?action=sofa_random" class="btn btn-primary">Random</a>
    <a href="index.php?action=sofa_25" class="btn btn-primary">25%</a>
    <a href="index.php?action=sofa_50" class="btn btn-primary">50%</a>
    <a href="index.php?action=sofa_75" class="btn btn-primary">75%</a>
    <a href="index.php?action=sofa_100" class="btn btn-primary">100%</a>

    <h1>Side light</h1>
    
    <a href="index.php?action=side_on" class="btn btn-primary">On</a>
    <a href="index.php?action=side_off" class="btn btn-primary">Off</a>
    <a href="index.php?action=side_white" class="btn btn-primary">White</a>
    <a href="index.php?action=side_random" class="btn btn-primary">Random</a>
    <a href="index.php?action=side_25" class="btn btn-primary">25%</a>
    <a href="index.php?action=side_50" class="btn btn-primary">50%</a>
    <a href="index.php?action=side_75" class="btn btn-primary">75%</a>
    <a href="index.php?action=side_100" class="btn btn-primary">100%</a>

    <h1>Desk light</h1>
    
    <a href="index.php?action=desk_on" class="btn btn-primary">On</a>
    <a href="index.php?action=desk_off" class="btn btn-primary">Off</a>
    <a href="index.php?action=desk_white" class="btn btn-primary">White</a>
    <a href="index.php?action=desk_random" class="btn btn-primary">Random</a>
    <a href="index.php?action=desk_25" class="btn btn-primary">25%</a>
    <a href="index.php?action=desk_50" class="btn btn-primary">50%</a>
    <a href="index.php?action=desk_75" class="btn btn-primary">75%</a>
    <a href="index.php?action=desk_100" class="btn btn-primary">100%</a>

    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="js/bootstrap.min.js"></script>
  </body>
</html>

