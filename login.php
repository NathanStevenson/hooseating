<?php

function checkLogin($username, $password){
    global $db;
    $query = "select * from User where name=:name and password=PASSWORD(:password)";
    $statement = $db->prepare($query);
    $statement->bindValue(':name', $username);
    $statement->bindValue(':password', $password);
    $statement->execute();
    $result = $statement->fetch();
    $statement->closeCursor();
    return $result;
}
?>