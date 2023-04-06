<?php
require("main_page_proc.php");
// require("form.php"); 
require("connect-db.php");

function checkifuserexist($username){
    global $db;
    debug_to_console($username);
    $query = "SELECT * FROM User where name=:username";
    $statement = $db->prepare($query);
    $statement->bindValue(':username', $username);
    $statement->execute();
    $results = $statement->fetchAll();
    $statement->closeCursor();
    return $results;
}
?>