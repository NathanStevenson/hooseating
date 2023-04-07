<?php
    // Function that sees whether or not a username has already been taken
    function username_taken($username){
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

    // Function that checks if the users username and password are valid upon sign in
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