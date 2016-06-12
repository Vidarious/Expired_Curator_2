<!DOCTYPE html>
<html lang="en">
   <head>
      <meta charset="utf-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="viewport" content="width=device-width, initial-scale=1">

      <title>Curator v1.0 - C9</title>

      <link rel="stylesheet" type="text/css" href="//fonts.googleapis.com/css?family=Ubuntu" />
      <link href="css/bootstrap.css" rel="stylesheet">
   </head>
   <body>
      <div class="container-fluid">
<?php

//Include Curator Session.
require_once('../resource/lib/session/App.php');

$mySession = Curator\Session\App::GetSession();

var_dump($mySession->GetValue('MYSITESESSION'));
?>
      </div>
      <!-- JavaScript Inserts -->
      <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
      <script src="js/bootstrap.min.js"></script>
   </body>
</html>
