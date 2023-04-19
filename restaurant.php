<?php
// TOP OF EVERY PAGE WITH HTML
session_start();

$active_user = "";
// if the user is not logged in then redirect them to the login_page
if (!isset($_SESSION['username'])) {
    // redirect the user to the login page
    header("Location: https://www.cs.virginia.edu/~ffk9uu/hooseating/form.php/");
    // header("Location: form.php/");
}else{
    $active_user = $_SESSION['username'];
}

require("connect-db.php");
require("utilities.php");
require("main_page_proc.php");
require("profile_page_db.php");


// getting current restaurant into $restaurant
$id = $_GET['id'];
$query = "SELECT * FROM Restaurant where restaurant_id=:id;";
$statement = $db->prepare($query);
$statement->bindValue(':id',$id);
$statement->execute();
$restaurant = $statement->fetch();
$statement->closeCursor();

// getting 50 most recent reviews into $reviews
$query = "SELECT * FROM Review WHERE restaurant_id=:id ORDER BY time_published;";
$statement = $db->prepare($query);
$statement->bindValue(':id',$id);
$statement->execute();
$reviews = $statement->fetchALL(PDO::FETCH_ASSOC);
$statement->closeCursor();

?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">  
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="author" content="your name">
        <meta name="description" content="include some description about your page">     
        <title>Hoos Eating</title> 
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
        <link rel="icon" type="image/png" href="http://www.cs.virginia.edu/~up3f/cs4750/images/db-icon.png" />

        <style>
        /* Add some basic styles for the navigation bar */
        nav {
            background-color: lightskyblue;
            overflow: hidden;
        }

        nav a {
            float: left;
            color: #f2f2f2;
            text-align: center;
            padding: 14px 16px;
            text-decoration: none;
            font-size: 17px;
        }

        nav a:hover {
            color: navy;
            text-decoration: underline;
        }

        img {
            float: left;
            clip-path: circle();
            position:  relative; 
            left: 50px;       
            top: 50px;  
            width: 20%;
            height: auto;
        }

        figcaption {
            padding: 30px;
            position:  relative; 
            left: 150px;       
            top: 50px;
        }

        div.container {
            width:600px;
            margin:200px ;
            float:right;
            top:-120px;
            position: relative;
        }

        nav a.prof{
            float:right;
        }

        .result {
            border: 1px solid;
            padding: 10px;
            margin-bottom: 10px;
        }

        .result h3 {
        font-size: 20px;
        margin-bottom: 5px;
        }

        .result p {
        font-size: 16px;
        margin-bottom: 0;
        }


        </style>
    </head>
    <body>
        <nav>
            <a href="https://www.cs.virginia.edu/~ffk9uu/hooseating/main_page.php/" class="fs-3 ps-5 fw-bold">Hoos Eating</a>
            <a href="https://www.cs.virginia.edu/~ffk9uu/hooseating/add_review.php/" class="fs-4 mt-1 ps-5">Add a Review</a>
            <a href="https://www.cs.virginia.edu/~ffk9uu/hooseating/view_reviews.php/" class="fs-4 mt-1 ps-5">View Other Reviews</a>
            <a href="https://www.cs.virginia.edu/~ffk9uu/hooseating/profile_page.php/" class="fs-4 mt-1 ps-5 prof">My Profile</a>
        </nav>
    </body>

        <body>

            <h1><?php echo $restaurant['name']; ?></h1>
            <p><?php echo $restaurant['address']; ?></p>

            <ul>
            <li>Average Rating: <?php echo $restaurant['avg_rating']; ?></li>
            <li>Cuisine: <?php echo $restaurant['cuisine']; ?></li>
            </ul>

            <div style="overflow-y: scroll; height: 200px;">
                <?php foreach ($reviews as $r): ?>
                    <p><?php echo $r['rating'] . ': ' . $r['summary']; ?></p>
                <?php endforeach; ?>
            </div>


            <h2>Write new review</h2>
            <form method="post" action="restaurant.php?id=<?php echo $id; ?>">
            <div class="mb-3">
                <label for="rating" class="form-label">Rating (0-10):</label>
                <input type="number" class="form-control" id="rating" name="rating" min="0" max="10" step="0.1">
            </div>
            <div class="mb-3">
                <label for="summary" class="form-label">Summary:</label>
                <input type="text" class="form-control" id="summary" name="summary">            
            </div>
                <button type="submit" name ="s" id="s" class="btn btn-primary">Submit Review</button>            
            </form>

        </body>
</html>

<?php

    function max_rest_id(){
        global $db;
        $query = "SELECT MAX(rating_id) FROM Review";
        $statement = $db->prepare($query);
        $statement->execute();
        $results = $statement->fetchColumn();
        $statement->closeCursor();
        return $results;
    }

    // to stop someone from spamming reviews: allow them to only have 1 review
    // per restaurant. TODO: edit review functionality
    function already_reviewed($user_id){
        global $db;
        $query = "SELECT * FROM Review where user_id=:user_id";
        $statement = $db->prepare($query);
        $statement->bindValue('user_id', $user_id);
        $statement->execute();
        $result = $statement->fetch();
        $statement->closeCursor();
        return $result;
    }

    function get_id_from_username($name){
        global $db;
        $query = "SELECT user_id FROM User where name=:name";
        $statement = $db->prepare($query);
        $statement->bindValue('name', $name);
        $statement->execute();
        $user_id = $statement->fetch();
        $statement->closeCursor();
        return $user_id['user_id'];

    }

    function write_review($user, $restaurant_id, $summary, $rating  ){
        global $db;
        $user_id = $user;
        $rating_id = max_rest_id() + 1;
        $numberoflikes = 0;
        $time = time();

        $query = "INSERT INTO Review VALUES (:rating_id, :user_id, :restaurant_id, :number_of_likes, :summary, :rating, :time_published);";
        $statement = $db->prepare($query);
        $statement->bindValue(':rating_id', $rating_id);
        $statement->bindValue(':user_id', $user_id);
        $statement->bindValue(':restaurant_id', $restaurant_id);
        $statement->bindValue(':number_of_likes', $numberoflikes);
        $statement->bindValue(':summary', $summary);
        $statement->bindValue(':rating', $rating);
        $statement->bindValue(':time_published', $time);
        $statement->execute();
        $statement->closeCursor();

    }

    
    if(isset($_POST['s']) and strlen($_POST['summary']) > 0 and strlen($_POST['rating']) > 0){

        $user_id = get_id_from_username($active_user);

        // originally thought we shouldn't let users write 2 reviews but I cant seem to delete reviews in the db for testing purposes.
        // if(!already_reviewed($user_id)){
        //     echo "eligible to write a review";
        //     $restaurant_id = $id;        
        //     $summary = $_POST['summary'];
        //     $rating = $_POST['rating'];

        //     write_review($user_id, $restaurant_id, $summary, $rating);
        //     $url = "restaurant.php?id=".$restaurant_id;
        //     header($url);
        
        // }else{
        //     echo "you've already left a review. soon we'll add a feature to edit reviews. wow";
        // }

        $restaurant_id = $id;        
        $summary = $_POST['summary'];
        $rating = $_POST['rating'];

        write_review($user_id, $restaurant_id, $summary, $rating);

    }
?>
