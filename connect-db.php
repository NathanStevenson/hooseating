<!-- TO TEST LOCALLY http://www.cs.virginia.edu/~your-computingID/path-to-your-file/helloworld.html (do not include public_html)-->

<?php
/** S23, PHP (on GCP, local XAMPP, or CS server) connect to MySQL (on CS server) **/
$username = 'nts7bcj'; 
$password = '4750Databases';
$host = 'mysql01.cs.virginia.edu';
$dbname = 'nts7bcj_d';
$dsn = "mysql:host=$host;dbname=$dbname";

// for gabe to develop; must be commented out before pushing to production! 
// $username = 'Gabriel'; 
// $password = 'Gabriel123';
// $host = 'localhost:3306';
// $dbname = 'project';
// $dsn = "mysql:host=$host;dbname=$dbname";

////////////////////////////////////////////

/** connect to the database **/
try 
{
//  $db = new PDO("mysql:host=$hostname;dbname=ffk9uu", $username, $password);
   $db = new PDO($dsn, $username, $password);
   
   // dispaly a message to let us know that we are connected to the database 
}
catch (PDOException $e)     // handle a PDO exception (errors thrown by the PDO library)
{
   // Call a method from any object, use the object's name followed by -> and then method's name
   // All exception objects provide a getMessage() method that returns the error message 
   $error_message = $e->getMessage();        
   echo "<p>An error occurred while connecting to the database: $error_message </p>";
}
catch (Exception $e)       // handle any type of exception
{
   $error_message = $e->getMessage();
   echo "<p>Error message: $error_message </p>";
}

?>
