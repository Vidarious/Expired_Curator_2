#Curator Database

####Author
James Druhan

####Start Date
January 16, 2016

####First Rehash
June 4, 2016

---

#####Description
This application manages the communication between your PHP application and database. This application is developed with the Singleton design pattern to prevent multiple instances of the database connection.

#####How To Use

#####Error Handling
Curator Database both logs errors to the PHP server log and throws errors to your application. Curator Database will NOT display any errors or use die() to stop run time. These tasks are left to the parent application developer. Using try{} and catch{} are recommended when performing Curator Database functions.

The thrown error is an Exception() object.

######Error \#1: Unable to connect to database.
Curator Database is unable to connect to the designated database. Check your configuration information and ensure the database server is up and running.
