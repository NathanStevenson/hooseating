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
    $query = "SELECT u.user_id, u.name, r.rating_id, r.restaurant_id, r.summary, r.number_of_likes, r.rating, r.time_published 
                FROM User u NATURAL JOIN Review r 
                WHERE u.name = :username";
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

function get_info_from_username($name){
    global $db;
    $query = "SELECT * FROM User where name=:name";
    $statement = $db->prepare($query);
    $statement->bindValue('name', $name);
    $statement->execute();
    $user = $statement->fetch();
    $statement->closeCursor();
    return $user;
}

?>