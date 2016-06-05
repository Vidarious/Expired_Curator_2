<?php 
   namespace MyWebsite;

   require_once('curator/application/start.php');
?>
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
         <div class="row">
            <div class="col-md-12">
               <h3>
                  <span class="glyphicon glyphicon-hand-right" aria-hidden="true"></span>
                  RIGHT
               </h3>
               <ol class="breadcrumb">
                  <li><a href="left.php">LEFT</a></li>
                  <li><a href="index.php">CENTER</a></li>
                  <li class="active">RIGHT</li>
                  <li><a href="register.php">Register</a></li>
               </ol>
            </div>
         </div>
         <div class="row">
            <div class="col-md-12">
               <?php if(isset($_SESSION['MESSAGE'])) : ?>
               <?php foreach($_SESSION['MESSAGE'] as $message) : ?>
               <div class="alert alert-warning" role="alert"><?=$message?></div>
               <?php endforeach; ?>
               <?php endif; ?>
               <?php unset($_SESSION['MESSAGE']); ?>
            </div>
         </div>
         <div class="row">
            <div class="col-md-12">
            <?php require_once('varDetails.php');?>
            </div>
         </div>
      </div>
      <!-- JavaScript Inserts -->
      <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
      <script src="js/bootstrap.min.js"></script>
   </body>
</html>
