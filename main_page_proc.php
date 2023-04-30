<?php 
function display_restaurants($filter_type, $restaurant_type){
    global $db;
    // ALL RESTS
    // Top_Rated_Rests
    if($filter_type == "best" && $restaurant_type == "all"){
        $query = "SELECT * FROM Restaurant ORDER BY avg_rating DESC LIMIT 8";
        $statement = $db->prepare($query);
        $statement->execute();
        $results = $statement->fetchAll(); 
        $statement->closeCursor();
        return $results;
    }

    // Worst_Rated_rests
    if($filter_type == "worst" && $restaurant_type == "all"){
        $query = "SELECT * FROM Restaurant ORDER BY avg_rating LIMIT 8";
        $statement = $db->prepare($query);
        $statement->execute();
        $results = $statement->fetchAll(); 
        $statement->closeCursor();
        return $results;
    }

    // Alphabetically
    if($filter_type == "alphabetically" && $restaurant_type == "all"){
        $query = "SELECT * FROM Restaurant ORDER BY name LIMIT 8";
        $statement = $db->prepare($query);
        $statement->execute();
        $results = $statement->fetchAll(); 
        $statement->closeCursor();
        return $results;
    }

    // BARS
    if($filter_type == "best" && $restaurant_type == "bars"){
        $query = "SELECT * FROM Bar NATURAL JOIN Restaurant ORDER BY avg_rating DESC LIMIT 8";
        $statement = $db->prepare($query);
        $statement->execute();
        $results = $statement->fetchAll(); 
        $statement->closeCursor();
        return $results;
    }

    // Worst_Rated_rests
    if($filter_type == "worst" && $restaurant_type == "bars"){
        $query = "SELECT * FROM Bar NATURAL JOIN Restaurant ORDER BY avg_rating LIMIT 8";
        $statement = $db->prepare($query);
        $statement->execute();
        $results = $statement->fetchAll(); 
        $statement->closeCursor();
        return $results;
    }

    // Alphabetically
    if($filter_type == "alphabetically" && $restaurant_type == "bars"){
        $query = "SELECT * FROM Bar NATURAL JOIN Restaurant ORDER BY name LIMIT 8";
        $statement = $db->prepare($query);
        $statement->execute();
        $results = $statement->fetchAll(); 
        $statement->closeCursor();
        return $results;
    }

    // Dine-In
    if($filter_type == "best" && $restaurant_type == "dine-in"){
        $query = "SELECT * FROM Dine_In NATURAL JOIN Restaurant ORDER BY avg_rating DESC LIMIT 8";
        $statement = $db->prepare($query);
        $statement->execute();
        $results = $statement->fetchAll(); 
        $statement->closeCursor();
        return $results;
    }

    // Worst_Rated_rests
    if($filter_type == "worst" && $restaurant_type == "dine-in"){
        $query = "SELECT * FROM Dine_In NATURAL JOIN Restaurant ORDER BY avg_rating LIMIT 8";
        $statement = $db->prepare($query);
        $statement->execute();
        $results = $statement->fetchAll(); 
        $statement->closeCursor();
        return $results;
    }

    // Alphabetically
    if($filter_type == "alphabetically" && $restaurant_type == "dine-in"){
        $query = "SELECT * FROM Dine_In NATURAL JOIN Restaurant ORDER BY name LIMIT 8";
        $statement = $db->prepare($query);
        $statement->execute();
        $results = $statement->fetchAll(); 
        $statement->closeCursor();
        return $results;
    }

    // Fast Food
    if($filter_type == "best" && $restaurant_type == "fast-food"){
        $query = "SELECT * FROM Fast_Food NATURAL JOIN Restaurant ORDER BY avg_rating DESC LIMIT 8";
        $statement = $db->prepare($query);
        $statement->execute();
        $results = $statement->fetchAll(); 
        $statement->closeCursor();
        return $results;
    }

    // Worst_Rated_rests
    if($filter_type == "worst" && $restaurant_type == "fast-food"){
        $query = "SELECT * FROM Fast_Food NATURAL JOIN Restaurant ORDER BY avg_rating LIMIT 8";
        $statement = $db->prepare($query);
        $statement->execute();
        $results = $statement->fetchAll(); 
        $statement->closeCursor();
        return $results;
    }

    // Alphabetically
    if($filter_type == "alphabetically" && $restaurant_type == "fast-food"){
        $query = "SELECT * FROM Fast_Food NATURAL JOIN Restaurant ORDER BY name LIMIT 8";
        $statement = $db->prepare($query);
        $statement->execute();
        $results = $statement->fetchAll(); 
        $statement->closeCursor();
        return $results;
    }
}
// Converts the session variable to the desired formatted string
function session_restaurant_str($restaurant_type){
    if($restaurant_type == "all"){
        return "All Restaurants";
    }

    if($restaurant_type == "bars"){
        return "Bars";
    }

    if($restaurant_type == "dine-in"){
        return "Dine-In";
    }

    if($restaurant_type == "fast-food"){
        return "Fast Food";
    }
}
// Converts the session variable to the formatted string
function session_filter_str($filter_type){
    if($filter_type == "best"){
        return "Best Restaurants";
    }

    if($filter_type == "worst"){
        return "Worst Restaurants";
    }

    if($filter_type == "alphabetically"){
        return "Alphabetically";
    }
}
?>