<?php
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

function update_review($id, $new_summary, $new_rating){
    global $db;
    $query = "UPDATE Review SET summary=:summary, rating=:rating WHERE rating_id=:id"; 
    $statement = $db->prepare($query);
    $statement->bindValue(':summary', $new_summary);
    $statement->bindValue(':rating', $new_rating);
    $statement->bindValue(':id', $id);
    $statement->execute();
    $statement->closeCursor();
}
?>