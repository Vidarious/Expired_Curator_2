# <a id="topHOWTO"></a>Curator - How To Guide

If you are looking for assistance with Curator classes, look no further! Curator comes with a number very lightweight utility classes which can be used in your own application alongside of Curator.

### Classes
- [**Database**](HOWTO/Database.md "Go to: Curator Database Class")

   The database class is used by The Curator to interact with your SQL database in order to manage application and user data. You can utilize the database object created by The Curator to perform all your necessary database actions: *Prepare a Statement*, *Bind Values*, *Execute Statement*, *Retrieve Results* (*Single Value*, *Many Rows*/*Columns*, *Many Columns*), *Row Count* and *Inserted ID*. All database action performed with this class are PDO secured.

- [**Cookie**](HOWTO/Cookie.md "Go to: Curator Cookie Class")

   The cookie class is used by The Curator to manage application cookie data. You can utilize the cookie object to manage your own cookie data as well! Create, retrieve and delete and even destroy all cookies safely with this easy to use class.

- [**Session**](HOWTO/Session.md "Go to: Curator Session Class")

   The session class is your safe and secure way of managing your site sessions. The Curator uses this class to manage all user interactions from page to page. Tap into this object for your own application needs with a handy Session getter and setter.

- [**Language**](HOWTO/Language.md "Go to: Curator Language Class")

   The language class is used to switch the language of The Curator. This class can be initialized with a default language set by the administrator or a locale language variable selected by the user.

- [**Log**](HOWTO/Log.md "Goto: Curator Log Class")

   The log class manages all errors that occur with The Curator.


### Traits
- [**Security**](HOWTO/Security.md "Go to: Curator Security Trait")

   The Security trait enables a number of handy methods which relate to the security of your site or data. With a quick USE statement to your application classes these traits instantly become available to you.
