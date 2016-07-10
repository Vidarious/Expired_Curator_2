#Curator Form
This class is intended to be used for processing web form submissions. Curator Form offers a variety of tools that help protect your forms from unwanted submissions. This includes protection from bots, spammers or people looking to perform malicious acts.

The class only support the POST method for form submission.

##### Features
+ Bot protection
+ Form spoof protection
+ Spam protection
+ Validation tools
+ Sanitization tools

#####Version
v1.0

#####Author
James Druhan

---

### How To Use

To use Curator Form you must include the application file into your project/page.

```php
require_once('form/App.php');
```

Finally you will then create your Curator Form object. You are required to pass a FormID argument. This is the name of the FormID field in your form (more information below).

```php
$myForm = new Curator\Form\App('TestForm');
```

**Note**: Curator Form uses sessions which must be started before creating the object.

**NOTICE:**
>This class is namespaced to Curator\Form for your convenience. You will only need to worry about the namespace for the initial object creation.

#### Options

When creating a new Curator Form object you must specific a FormID. However you may also specific an options array.

```php
$myWhiteList = array('email', 'password', 'honeyPot', 'TestForm');
$options = array('HoneyPot' => 'honeyPot', 'WhiteList' => $myWhiteList, 'Delay' => 10);

$myForm = new Curator\Form\App('TestForm', $options);
```

The options array allows you to add additional checks to your form submissions, increasing the protection level.

+ **HoneyPot**: This option tells Curator Form to enable the Honey Pot feature which adds bot protection to your forms. The value passed to HoneyPot is the name of your honey pot input.

+ **WhiteList**: This option tells Curator Form to compare the form POST keys to your white list. If there are too many or few POST keys, or, if the keys do not match validation of your form submission will fail.

+ **Delay**: This option allows you to customize how much time must pass between each form submission. This prevents submission spamming.

---

#### Form Elements

Once you have created the Curator Form object with your specific options you must add specific inputs to your form in order to be protected.

+ **FormID**: A FormID is required by Curator Form and should be added as an input to your form. This is done using the AssignIDToken() method. The name of this input must match the FormID string passed when the Curator Form object was created. Autocomplete should be set to "off" to prevent false positive results.

```php
$myForm = new Curator\Form\App('TestForm', $options);

<input name="TestForm" type="hidden" autocomplete="off" value="<?=$myForm::AssignIDToken()?>">
```

>This input should be hidden using either **type="hidden"** or **display:none (CSS)**.

+ **HoneyPot**: If used, the Honey Pot input should be inserted into your form with the same name passed in the HoneyPot option. The value **must** be set to "" and autocomplete = "off".

```php
<input name="honeyPot" value="" autocomplete="off">
```

>This input should be hidden using either **type="hidden"** or **display:none (CSS)**. The honey pot check fails if any characters are found in the POST honey pot variable.

+ **WhiteList**: If a white list has been provided to Curator Form *all* POST key's must match those found in your form. This includes the additional inputs necessary by Curator Form.

```php
$myWhiteList = array('email', 'password', 'honeyPot', 'TestForm');

<input name="email">
<input name="password">
<input name="TestForm" type="hidden" autocomplete="off" value="<?=$myForm::AssignIDToken()?>">
<input name="honeyPot" value="" autocomplete="off">
```

---

#### Putting It All Together

Below is an example of all components of a form protected by Curator Form.

```php
...

require_once('form/App.php');

$myWhiteList = array('email', 'password', 'honeyPot', 'TestForm');
$options = array('HoneyPot' => 'honeyPot', 'WhiteList' => $myWhiteList, 'Delay' => 10);

$myForm = new Curator\Form\App('TestForm', $options);

...

<html>
    <body>
        <form action="test.php" method="POST">
            <label>Email address</label>
            <input name="email">
            <label>Password</label>
            <input name="password">

            <input name="TestForm" type="hidden" autocomplete="off" value="<?=$myForm::AssignIDToken()?>">
            <input name="honeyPot" autocomplete="off" value="">

            <button type="submit">Submit</button>
        </form>
    </body>
</html>
```

**Note**: Curator Form uses sessions and must be started before creating the object.

---

#### Form Validation

Once submitted, you validate the form using the Validate() method. If the form was submitted and all checks passed Validate() will return TRUE while if a issue was found, Validate will return FALSE to indicate a problem.

```php
if($myForm->Validate() === TRUE)
{
    echo 'Form submission is GOOD!';
}
else
{
    echo 'Form submission is BAD!';
}
```

#### Managing Failed Validation (Errors)

When form validation fails an error message and code can be obtained so you may process the issue accordingly. This is done with the GetError() method which returns an array. When you call GetError() you can assign it to a variable to process the error.

```php
$formError = $myForm->GetError();

echo 'The form error message is: ' . $formError['Message'];
echo 'The form error code is: ' . $formError['Code'];
```

###### Error Codes

+ **0**: Form not submitted (Form ID was not found).
+ **1**: Form ID is invalid.
+ **2**: Honey pot is invalid.
+ **3**: Whitelist does not match.
+ **4**: Repeat form submission outside of allowed time.

**Note**: Codes can be used for managing custom error messages to your user. Alternately you may use the Message string for debugging or logging.

---

#### Validation

Curator Form includes a data validation method called CheckIF(). This allows you to confirm if data passed is of a specific type.

```php
$myForm::CheckIF($data, 'NUMBER');
```

###### Options

+ **NUMBER**: Verifies if the data passed only consists of a valid number. This includes INT and FLOAT as well as -+ symbols.

+ **ALPHA**: Verifies if the data passed only consists of alphabetical characters.

+ **ALPHANUMERIC**: Verifies if the data passed only consists of alphanumeric characters.

+ **EMAIL**: Verifies if the data passed only consists of characters allowable in an e-mail address.

---

#### Data Sanitization

Curator Form includes two methods for data sanitizing: Santize() and SanitizeArray(). These methods accept data as well as optional options.

```php
$data = ' This is a test. ';
$dataArray = array(' This is a test. ', 'Testing 1 2 3 4.');

$cleanData = $myForm::Sanitize($data, 'T');
$cleanDataArray = $myForm::SanitizeArray($data, array('T', 'N'));
```

###### Options

Both Sanitize() and SanitizeArray() can accept one or many options. Each option sanitizes the data in a different way.

+ '**T**': Trims the data of spaces at the beginning and end of the string.

+ '**E**': Applies the FILTER_SANITIZE_EMAIL filter.

+ '**M**': Applies the FILTER_SANITIZE_MAGIC_QUOTES filter.

+ '**N**': Applies the FILTER_SANITIZE_NUMBER_INT filter.

+ '**U**': Applies the FILTER_SANITIZE_URL filter.

+ '**S**': Applies the FILTER_SANITIZE_STRING filter.

+ '**H**': Applies the FILTER_SANITIZE_FULL_SPECIAL_CHARS filter.

**Note**: If no options are provided the default sanitization method will apply trim(), addslashes() and the FILTER_SANITIZE_STRING filter.
