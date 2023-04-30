<?php

require("connect-db.php");
require("utilities.php");

// TOP OF EVERY PAGE WITH HTML
session_start();

$active_user = "";
$id = $_GET['id'];
// if the user is not logged in then redirect them to the login_page
if (!isset($_SESSION['username'])) {
    // redirect the user to the login page
    header("Location: https://www.cs.virginia.edu/~nts7bcj/hooseating/form.php/");
    // header("Location: form.php/");
}else{
    $active_user = $_SESSION['username'];
}
//Log Out
if($_SERVER['REQUEST_METHOD']=='POST'){
    if(isset($_POST['logout'])){
        session_destroy();
        header("Location: https://www.cs.virginia.edu/~nts7bcj/hooseating/form.php/");
    }
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

function get_rest_from_id($id){
    global $db;
    $query = "SELECT name FROM Restaurant where restaurant_id=:id";
    $statement = $db->prepare($query);
    $statement->bindValue('id', $id);
    $statement->execute();
    $rest_name = $statement->fetch();
    $statement->closeCursor();
    return $rest_name['name'];
}

function max_rest_id(){
    global $db;
    $query = "SELECT MAX(rating_id) FROM Review";
    $statement = $db->prepare($query);
    $statement->execute();
    $results = $statement->fetchColumn();
    $statement->closeCursor();
    return $results;
}

function write_review($user, $restaurant_id, $summary, $rating  ){
    global $db;
    $user_id = $user;
    $rating_id = max_rest_id() + 1;
    $numberoflikes = 0;

    $query = "INSERT INTO Review VALUES (:rating_id, :user_id, :restaurant_id, :number_of_likes, :summary, :rating, NULL);";
    $statement = $db->prepare($query);
    $statement->bindValue(':rating_id', $rating_id);
    $statement->bindValue(':user_id', $user_id);
    $statement->bindValue(':restaurant_id', $restaurant_id);
    $statement->bindValue(':number_of_likes', $numberoflikes);
    $statement->bindValue(':summary', $summary);
    $statement->bindValue(':rating', $rating);
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
    // Redirect back to the restaurant main page
    header("Location: https://www.cs.virginia.edu/~nts7bcj/hooseating/restaurant.php?id=".$id);
}

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

        nav a.prof{
            float:right;
        }
        
        #sortbydropdown input{
            color: white;
            background-color: lightgray;
            width: 100%;
        }

        #sortbydropdown input:hover{
            color: navy;
        }
        nav input.prof{
            background: lightskyblue;
            float: right;
            color: #f2f2f2;
            text-align: center;
            padding: 14px 16px;
            text-decoration: none;
            font-size: 17px;
            border: none;
        }

        nav input.prof:hover {
            color: navy;
            text-decoration: underline;
        }
        </style>
    </head>

    <body style="background-color:#DFFFFD">
        <nav>
            <a href="https://www.cs.virginia.edu/~nts7bcj/hooseating/main_page.php/" class="fs-3 ps-5 fw-bold">Hoos Eating</a>
            <a href="https://www.cs.virginia.edu/~nts7bcj/hooseating/add_review.php/" class="fs-4 mt-1 ps-5">Find a Restaurant</a>
            <a href="https://www.cs.virginia.edu/~nts7bcj/hooseating/profile_page.php/" class="fs-4 mt-1 ps-5 prof">My Profile</a>
            <form method="POST">
                <input type="submit" value="Log Out" name="logout" class="fs-4 mt-1 ps-5 prof" id="logout">
            </form>
        </nav>

        <h2 class='text-center my-3'>Write a Review for <?php echo get_rest_from_id($id);?></h2>
        <form method="post" class=" shadow w-50 mx-auto border border-secondary border-3 rounded-3 mt-2" style="background-color: white;" action="submit_review.php?id=<?php echo $id; ?>">
            <div class='mx-3 my-4'>
                <div class="mb-3">
                    <label for="rating" class="form-label fw-bold">Rating (0-10):</label>
                    <input type="number" class="form-control" id="rating" name="rating" min="0" max="10" step="0.1">
                </div>

                <div class="mb-3">
                    <label for="summary" class="form-label fw-bold">Summary:</label>
                    <input type="text" class="form-control" id="summary" name="summary">            
                </div>

                <div class='w-25 mx-auto'>
                    <button type="submit" name="s" id="s" class="btn btn-primary">Submit Review</button>
                </div>
            </div>           
        </form>
    </body>
</html>