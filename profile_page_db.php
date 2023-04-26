<?php

function get_user_rated_retaurants($username){
    global $db;
    $query = "SELECT r.rated_restaurant 
                FROM User_rated_restaurant r NATURAL JOIN User u 
                WHERE u.name = :username";
    $statement = $db->prepare($query);
    $statement->bindValue(':username', $username);
    $statement->execute();
    $results = $statement->fetchAll();
    $statement->closeCursor();
    return $results;
}

function get_user_reviews($username){
    global $db;
    $query = "SELECT * from Review JOIN User on User.user_id=Review.user_id where User.name = :username";
    $statement = $db->prepare($query);
    $statement->bindValue(':username', $username);
    $statement->execute();
    $results = $statement->fetchAll();
    $statement->closeCursor();
    return $results;
}

function get_restuarant($rest_id, $username){
    global $db;
    $query = "SELECT rest.name
                FROM Restaurant rest NATURAL JOIN Review r JOIN User u ON u.user_id = r.user_id
                where r.restaurant_id=:rest_id AND u.name = :username";
    $statement = $db->prepare($query);
    $statement->bindValue(':rest_id', $rest_id);
    $statement->bindValue(':username', $username);
    $statement->execute();
    $results = $statement->fetchAll();
    $statement->closeCursor();
    return $results;
}

function get_profile_pic($username){
    global $db;
    $query = "SELECT profile_photo from User WHERE name=:username";
    $statement = $db->prepare($query);
    $statement->bindValue(':username', $username);
    $statement->execute();
    $results = $statement->fetchAll();
    $statement->closeCursor();
    return $results;
}


?>