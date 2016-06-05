#Curator Database

####Author
James Druhan

####Start Date
January 16, 2016

####First Rehash
June 4, 2016

---

#####Description
Curator Database is a wrapper application for PDO class. Designed with the singleton pattern only one instances of the Curator Database object can exist thus preventing multiple database connections for a single user.

#####How To Use

#####Error Handling
Curator Database both logs errors to the PHP server log and throws errors to your application. Curator Database will NOT display any errors or use die() to stop run time. These tasks are left to the parent application developer. Using try{} and catch{} are recommended when performing Curator Database functions.

The thrown error is an Exception() object. All error messages are suppressed to a generic statement which is user friendly. No PHP/PDO error data is displayed. Instead the PHP/PDO data is recorded to the PHP log.

######Error \#1: Unable to connect to database.
Curator Database is unable to connect to the designated database. Check your configuration information and ensure the database server is up and running.

######Error \#2: Unable to process your request.
No database statement was passed to Curator Database to be prepared -> PrepareStatement().

######Error \#3: Unable to process your request.
Bad database query. PDO was unable to prepare the query -> PrepareStatement().

######Error \#4: Unable to process your request.
No parameter or value to bind to statement -> BindValue().

######Error \#5: Unable to process your request.
Unable to bind provided data to PDO statement. -> BindValue().
