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
$myForm = new Curator\Form\App('UserForm', 'honeyPot', $myWhiteList, 10);
    dump('POST', $_POST);
    dump('SESSION', $_SESSION);

//The Validate() method returns TRUE or FALSE if the form validate passes or not.
$formValidation = $myForm->Validate();
    dump('Form ID', $formValidation);

    dump('Error Details', $myForm->GetError());

    $test = 0;

    dump('CheckIF: Number(123123)', $myForm->CheckIF(123123, 'Number'));
    dump('CheckIF: Number(-12312)', $myForm->CheckIF(-12312, 'Number'));
    dump('CheckIF: Number(0)', $myForm->CheckIF(0, 'Number'));
    dump('CheckIF: Number(TRUE)', $myForm->CheckIF(TRUE, 'Number'));
    dump('CheckIF: Number(FALSE)', $myForm->CheckIF(FALSE, 'Number'));
    dump('CheckIF: Number("123123")', $myForm->CheckIF('123123', 'Number'));
    dump('CheckIF: Number("-12312")', $myForm->CheckIF('-12312', 'Number'));
    dump('CheckIF: Number("0")', $myForm->CheckIF('0', 'Number'));
    dump('CheckIF: Number("TRUE")', $myForm->CheckIF('TRUE', 'Number'));
    dump('CheckIF: Number("FALSE")', $myForm->CheckIF('FALSE', 'Number'));
    dump('CheckIF: Number($test)', $myForm->CheckIF($test, 'Number'));
    dump('CheckIF: Number(0.3)', $myForm->CheckIF(0.3, 'Number'));
    dump('CheckIF: Number(POST EMAIL)', $myForm->CheckIF($_POST['email'], 'Number'));

    //CheckIF (ALPHA)
    //CheckIF (ALPHANUMERIC)
    //CheckIF (EMAIL)
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
                        </diav>
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
                            <input name="UserForm" class="form-control" value="<?=$myForm::AssignIDToken()?>">
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
