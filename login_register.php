<?php
    // Function that sees whether or not a username has already been taken
    function username_taken($username){
        global $db;
        $query = "SELECT * FROM User where name=:username";
        $statement = $db->prepare($query);
        $statement->bindValue(':username', $username);
        $statement->execute();
        $results = $statement->fetch();
        $statement->closeCursor();
        return $results;
    }

    // Function that checks if the users username and password are valid upon sign in
    function checkLogin($username, $password){
        debug_to_console("CHECK LOGIN");
        global $db;
        $query = "SELECT * FROM User WHERE name=:name AND password=PASSWORD(:password)";
        $statement = $db->prepare($query);
        $statement->bindValue(':name', $username);
        $statement->bindValue(':password', $password);
        $statement->execute();
        $result = $statement->fetch();
        $statement->closeCursor();
        return $result;
    }

    // Function that registers a user in our database on login
    function add_user($id, $uname, $pwd){
        debug_to_console("add_user");
        global $db;
        $query = "INSERT INTO User VALUES (:id, :uname, NULL, PASSWORD(:pwd), NULL)";
        $statement = $db->prepare($query);
        // bind the templates if not an executable
        $statement->bindValue(':id', $id);
        $statement->bindValue(':uname', $uname);
        $statement->bindValue(':pwd', $pwd);
        $statement->execute();
        $statement->closeCursor();
    }

    // Finds the first available user_id
    function max_user_id(){
        global $db;
        $query = "SELECT MAX(user_id) FROM User";
        $statement = $db->prepare($query);
        $statement->execute();
        $results = $statement->fetchColumn();
        $statement->closeCursor();
        return $results;
    }

    // given a user allows the BLOB to become an image
    function blob_to_img($image_data){
        $mime_type = mime_content_type($image_data);
        header("Content-Type: $mime_type");
    }
?>