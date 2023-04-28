<?php

// $change_review=$_POST['rev_id'];
function get_review($id){
    global $db;
    $query = "SELECT *
                FROM Review
                WHERE rating_id=:id;"; 
    $statement = $db->prepare($query);
    $statement->bindValue(':id', $id);
    $statement->execute();
    $result=$statement->fetchAll();
    $statement->closeCursor();
    return $result;
}

function update_review($change_review, $new_summary, $new_rating){
    global $db;
    $query = "UPDATE Review SET summary=:summary, rating=:rating WHERE rating_id=:id"; 
    $statement = $db->prepare($query);
    $statement->bindValue(':summary', $new_summary);
    $statement->bindValue(':rating', $new_rating);
    $statement->bindValue(':id', $change_review);
    $statement->execute();
    $statement->closeCursor();
}

function get_restaurant_name($id){
    global $db;
    $query = "SELECT name from Restaurant WHERE restaurant_id=:id"; 
    $statement = $db->prepare($query);
    $statement->bindValue(':id', $id);
    $statement->execute();
    $result=$statement->fetch();
    $statement->closeCursor();
    return $result;
}

function delete_review($change_review, $user, $rest){
    global $db;
    $query = "DELETE FROM Review WHERE rating_id=:id AND user_id=:user AND restaurant_id=:rest";
    // debug_to_console($query);
    // debug_to_console($change_review);
    $statement = $db->prepare($query);
    $statement->bindValue(':id', $change_review);
    $statement->bindValue(':user', $user);
    $statement->bindValue(':rest', $rest);
    $statement->execute();
    $statement->closeCursor();
}
?>