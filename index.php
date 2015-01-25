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
   echo $_GET["action"];
   if ($_GET["action"]=="all_on") {
   $milight->rgbwAllOn();
$milight->rgbwAllSetToWhite();
    $milight->rgbwAllBrightnessMax();
    echo "clause 1";
    }
    
    if ($_GET["action"]=="all_off") {
    $milight->rgbwAllOff();
    echo "clause 2";
    }

    ?>

<h1>Hello, world!</h1>

<a href="index.php?action=all_on" class="btn btn-primary">Lights on</a>
<a href="index.php?action=all_off" class="btn btn-primary">Lights off</a>

    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="js/bootstrap.min.js"></script>
  </body>
</html>

