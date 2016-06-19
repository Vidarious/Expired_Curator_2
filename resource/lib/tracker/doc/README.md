# Curator Tracker

This class tracks user's page navigation. Curator Tracker helps keeps tabs on users current and previous page URI's. This is especially useful for authorization applications if you wish to redirect your user to a previously attempted to access, secure page.

##### Features
+ Uses sessions
+ Tracks Current and Previous Pages

---

### How To Use

First and foremost you should configure the two settings in the App.php page at the top of the document.

**HOME**
>This global variable stores the path to your default or home page. This page is used when the a previously recorded page is not found. This happens when your user arrives at your site from another domain or site.

**SITENAME**
>This global variable stores the name of your site. This is used to append with the scripts session variables. For example if your SITENAME was 'GOOGLE' your two session variables would be $_SESSION[GOOGLE_PageCurrent] and $_SESSION[GOOGLE_PagePrevious].

Once configured all you need to do is include the App.php file to your page and create a Curator Tracker object.

```php
require_once('../resource/lib/tracker/App.php');

$myTracker = Curator\Tracker\App::GetTracker();
```

**NOTE**
>Curator Tracker uses sessions. Please ensure to start sessions with "session_start();" before creating the object.

Now you can read and use the current and previous page information as required.

```php
echo $_SESSION['MYSITE_PagePrevious'];
echo $_SESSION['MYSITE_PageCurrent'];
```
