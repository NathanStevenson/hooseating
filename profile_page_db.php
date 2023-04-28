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

function get_info_from_username($name){
    global $db;
    $query = "SELECT * FROM User where name=:name";
    $statement = $db->prepare($query);
    $statement->bindValue(':name', $name);
    $statement->execute();
    $user = $statement->fetch();
    $statement->closeCursor();
    return $user;
}
function get_fav_rests($user_id){
    global $db;
    $query = "SELECT * FROM User_fav_restaurant WHERE user_id=:user_id;";
    $statement = $db->prepare($query);
    $statement->bindValue(':user_id', $user_id);
    $statement->execute();
    $fav_rests = $statement->fetchAll();
    $statement->closeCursor();
    return $fav_rests;
}

?>