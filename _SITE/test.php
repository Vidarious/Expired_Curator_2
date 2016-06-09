<?php
   require_once('../resource/lib/database/database.php');
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
          <?php
            $name = 'Doug';
            $age = 29;
            $statement = "INSERT INTO test (name, age) VALUES (:variable1, :variable2)";

            //$statement = "SELECT * FROM test";

            try
            {
                //Open DB Connection.
                $myDatabase = \Curator\Database\App::GetConnection();

                //Prepare the SQL query.
                $myDatabase->PrepareStatement($statement);

                //Bind values to the query.
                $myDatabase->BindValue('variable1', $name); //Type not defined.
                $myDatabase->BindValue('variable2', $age, PDO::PARAM_INT); //Type defined.

                //Execute the query.
                $myDatabase->ExecuteQuery();

                //var_dump($myDatabase->GetSingleRow());
                //var_dump($myDatabase->GetSingleRow());
                //var_dump($myDatabase->GetSingleRow());

                //var_dump($myDatabase->GetAllRows());

                //var_dump($myDatabase->GetColumn());

                //echo 'Rows: ' . $myDatabase->GetRowCount();

                //echo 'Columns: ' . $myDatabase->GetColumnCount();

                echo 'Last ID: ' . $myDatabase->GetInsertedID();
            }
            catch(Throwable $t)
            {
                echo $t->getMessage();
            }
           ?>
      </div>
      <!-- JavaScript Inserts -->
      <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
      <script src="js/bootstrap.min.js"></script>
   </body>
</html>
