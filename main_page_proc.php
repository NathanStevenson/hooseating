<?php 
// Function in order to get the top 12 restaurants by rating
function top_rated_restaurants(){
    global $db;
    $query = "SELECT * FROM Restaurant ORDER BY avg_rating DESC LIMIT 12";
    $statement = $db->prepare($query);
    $statement->execute();
    $results = $statement->fetchAll(); 
    $statement->closeCursor();
    return $results;
}

// Function to get the worst 12 restaurants by rating
function worst_rated_restaurants(){
    global $db;
    $query = "SELECT * FROM Restaurant ORDER BY avg_rating LIMIT 12";
    $statement = $db->prepare($query);
    $statement->execute();
    $results = $statement->fetchAll(); 
    $statement->closeCursor();
    return $results;
}

// Function to get the first 12 restaurants alphabetically
function alphabetically(){
    global $db;
    $query = "SELECT * FROM Restaurant ORDER BY name LIMIT 12";
    $statement = $db->prepare($query);
    $statement->execute();
    $results = $statement->fetchAll(); 
    $statement->closeCursor();
    return $results;
}
?>