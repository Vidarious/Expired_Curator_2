<?php
require_once('../resource/lib/debug/vendor/debug.php');

//Include session class.
require_once('../resource/lib/Session/App.php');
//Create session object (required for Curator Form class).
$mySession = Curator\Session\App::getSession();

//Include form class.
require_once('../resource/lib/form/App.php');

//Create a whitelist of POST variables that will be accepted.
$myWhiteList = array('email', 'password', 'honeyPot', 'UserForm');

//Create form object. Needed for page with form and form processing page.
$options = array('HoneyPot' => 'honeyPot', 'WhiteList' => $myWhiteList, 'Delay' => 10);

$myForm = new Curator\Form\App('UserForm');

    dump('POST', $_POST);
    dump('SESSION', $_SESSION);

//The Validate() method returns TRUE or FALSE if the form validate passes or not.
$formValidation = $myForm->Validate();

    dump('Form ID', $formValidation);

    dump('Error Details', $myForm->GetError());

    $data = array(
        'Test Data',
        ' Test Data ',
        ' Test Data',
        'a!b@c#d$h%i^j&k*l(m)n-o_q=u+sw|x\y]z}a[b{c;d:e,f<g.h>i/j?k`l~',
        'test""test',
        ' test""test ',
        '<b>Test Data</b>',
        ' <b>Test Data</b> ',
        'Test</b>Data',
        'Test<b>Data',
        ' <b>T<e"s\t D/a?t`a</b> ',
        ' <?php<b>T<e"s\t D/a?t`a</b>?> ',
        ' <?php <b>T<e"s\t D/a?t`a</b> ?> ',
        'fatherfn2k@hotmail.com',
        'asd123asd12d12d12d',
        'http://www.google.com',
        'www.google.com',
        'google.com',
    );

    $optionz = array('T', 'N', 'T');

    dump($data, $myForm->SanitizeArray($data, $optionz));

    //Sanitize
    //SanitizeArray
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Curator v1.0 - Test</title>

        <link rel="stylesheet" type="text/css" href="//fonts.googleapis.com/css?family=Ubuntu" />
        <link href="css/bootstrap.css" rel="stylesheet">
    </head>
    <body>
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <form action="test.php" method="POST">
                        <div class="form-group">
                            <label>Email address</label>
                            <input name="email" class="form-control" id="email">
                        </div>
                        <div class="form-group">
                            <label>Password</label>
                            <input name="password" class="form-control" id="password" placeholder="Password">
                        </div>
                            <div class="form-group">
                            <label>Honey Pot</label>
                            <!-- If a honey pot name was passed to the Curator Form object you must have a hidden element with the same name -->
                            <!-- Assign the value to "" -->
                            <!-- This element should be hidden (type='hidden' or display:none). -->
                            <!-- Ensure autocomplete is set to "off" for this element -->
                            <input name="honeyPot" class="form-control" value="" autocomplete="off">
                        </div>
                        <div class="form-group">
                            <label>Form ID</label>
                            <!-- AssignIDToken() method is used to assign the form ID to a form element. -->
                            <!-- Element name should be the name of the ID name passed to Curator Form -->
                            <!-- This element should be hidden (type='hidden' or display:none). -->
                            <input name="UserForm" class="form-control" autocomplete="off" value="<?=$myForm::AssignIDToken()?>">
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-default">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- JavaScript Inserts -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
        <script src="js/bootstrap.min.js"></script>
    </body>
</html>
