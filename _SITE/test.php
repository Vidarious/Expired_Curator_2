<?php
//Include Curator Session.
require_once('../resource/lib/Session/App.php');
$mySession = Curator\Session\App::getSession();

require_once('../resource/lib/form/App.php');
$myForm = new Curator\Form\App('myID', 'honey');
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

       <?php

       print_r($_POST);

       echo "<br /><br />";
       echo "<br /><br />";

       echo "SESSION DATA: <br /><br />";
       var_dump($_SESSION);
       echo "SESSION DATA: <br /><br />";

       if($myForm->Validate() === FALSE)
       {
           echo "Form is bad.";
       }
       else
       {
           echo "Form is Good.";
       }
       ?>

      <div class="container-fluid">

          <form action="test.php" method="POST">
            <div class="form-group">
              <label for="exampleInputEmail1">Email address</label>
              <input name="email" class="form-control" id="exampleInputEmail1" placeholder="Email">
            </div>
            <div class="form-group">
              <label for="exampleInputPassword1">Password</label>
              <input name="password" class="form-control" id="exampleInputPassword1" placeholder="Password">
              <input name="honey" class="form-control" value="">
              <input name="myID" class="form-control" value="<?=$myForm::AssignIDToken()?>">
            </div>
            <button type="submit" class="btn btn-default">Submit</button>
          </form>
<?php


echo "<br /><br />";
echo "<br /><br />";

$test = '  te"s<br />t ';

$test_array = array
        (
            ' Ke"y 1 ' => ' Val"ue 1 ',
            ' Key 2 ' => ' Val<h1>aa</h1>ue 2 ',
            ' Key 3 ' => ' Value 3 ',
            ' Key 4 ' => ' Value 4 ',
            ' Key 5 ' => ' Value 5 '
        );

$test_array2 = array
        (
            ' Ke"y 1 ',
            ' Key 2 ',
            ' Key 3 ',
            ' Key 4 ',
            ' Key 5 '
        );

$test_array3 = array
        (
            array(' Ke"y 1 ' => ' Value 1 '),
            array(' Ke"y 2 ' => ' Value 2 '),
            array(' Ke"y 3 ' => ' Value 3 '),
            array(' Ke"y 4 ' => ' Value 4 ')
        );

echo "SESSION DATA: <br /><br />";
var_dump($_SESSION);
echo "<br /><br />";

echo "RAW STRING DATA: <br /><br />";
var_dump($test);
echo "<br /><br />";

echo "PROCESSED TEST DATA: <br /><br />";
var_dump($myForm::Sanitize($test, array('s', 't')));
echo "<br /><br />";

echo "RAW TEST ARRAY DATA: <br /><br />";
var_dump($test_array);
echo "<br /><br />";

echo "PROCESSED TEST ARRAY DATA: <br /><br />";
var_dump($myForm::SanitizeArray($test_array, 's'));
echo "<br /><br />";

$_POST['checkTest'] = 'asfasfasfasf sadas';

echo "CheckIF ALPHA: " . $_POST['checkTest'] . " <br /><br />";
echo $myForm::CheckIF('checkTest', 'ALPHA') ? 'true' : 'false';
echo "<br /><br />";

echo "CheckIF NUMBER: " . $_POST['checkTest'] . " <br /><br />";
echo $myForm::CheckIF('checkTest', 'NUMBER') ? 'true' : 'false';
echo "<br /><br />";

echo "CheckIF ALPHANUMERIC: " . $_POST['checkTest'] . " <br /><br />";
echo $myForm::CheckIF('checkTest', 'ALPHANUMERIC') ? 'true' : 'false';
echo "<br /><br />";

echo "CheckIF EMAIL: " . $_POST['checkTest'] . " <br /><br />";
echo $myForm::CheckIF('checkTest', 'EMAIL') ? 'true' : 'false';
echo "<br /><br />";

echo "SESSION DATA: <br /><br />";
var_dump($_SESSION);
echo "<br /><br />";
?>
      </div>
      <!-- JavaScript Inserts -->
      <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
      <script src="js/bootstrap.min.js"></script>
   </body>
</html>
