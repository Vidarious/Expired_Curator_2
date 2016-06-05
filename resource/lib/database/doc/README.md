#Curator Database

####Author
James Druhan

####Start Date
January 16, 2016

####First Rehash
June 4, 2016

---

#####Description
This application manages the communication between your PHP application and database.

#####How To Use

#####Error Handling
Curator Database both logs errors to the PHP server log and throws errors to your application. Curator Database will NOT display any errors or use die() to stop run time. These tasks are left to the parent application developer. Using try{} and catch{} are recommended when performing Curator Database functions.

######Error \#1: Unable to connect to database.
Curator Database is unable to connect to the designated database. Check your configuration information and ensure the database server is up and running.
