<?php 
// Function in order to get the top 8 restaurants by rating
function top_rated_restaurants(){
    global $db;
    $query = "SELECT * FROM Restaurant ORDER BY avg_rating DESC LIMIT 12";
    $statement = $db->prepare($query);
    $statement->execute();
    $results = $statement->fetchAll(); 
    $statement->closeCursor();
    return $results;
}
?>