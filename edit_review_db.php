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

// function update_review($id){
//     global $db;
//     $query = "INSERT INTO Review values()"; 
//     $statement = $db->prepare($query);
//     $statement->bindValue(':id', $id);
//     $statement->execute();
//     $statement->closeCursor();
// }
?>