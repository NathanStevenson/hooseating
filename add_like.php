<?php 

print_r($_POST);

require("connect-db.php");
require("utilities.php");
require("main_page_proc.php");
require("profile_page_db.php");

$restaurant_id = $_POST['rest_id'];
$user_id = $_POST['user_id'];
$rating_id = $_POST['rating_id'];

// checks if Like entry exists; stops user from liking a review more than once 
function did_user_already_like($restaurant_id, $user_id, $rating_id){
    global $db;
    $query = "SELECT user_id FROM Likes where restaurant_id=:restaurant_id AND user_id=:user_id AND rating_id=:rating_id";
    $statement = $db->prepare($query);
    $statement->bindValue(':restaurant_id',$restaurant_id);
    $statement->bindValue(':user_id',$user_id);
    $statement->bindValue(':rating_id',$rating_id);
    $statement->execute();
    $res = $statement->fetch();
    $statement->closeCursor();
    return $res;
}

// fetch user_id from rating_id in Review table; used to get fill author_id column when adding new Likes
function get_user_id_from_rating_id($rating_id){
    global $db;
    $query = "SELECT user_id FROM Review WHERE rating_id=:rating_id";
    $statement = $db->prepare($query);
    $statement->bindValue(':rating_id',$rating_id);
    $statement->execute();
    $res = $statement->fetch();
    $statement->closeCursor();
    // print_r($res);
    return $res['user_id'];
}

// updates number_of_likes column in Review and adds new like to like table
function add_like($restaurant_id, $user_id, $rating_id){
    global $db;
    $review = "UPDATE Review SET number_of_likes = number_of_likes + 1 WHERE restaurant_id=:restaurant_id AND rating_id=:rating_id";
    $statement = $db->prepare($review);
    $statement->bindValue(':restaurant_id',$restaurant_id);
    $statement->bindValue(':rating_id',$rating_id);
    $statement->execute();
    $res = $statement->fetch();
    $statement->closeCursor();

    $author_id = get_user_id_from_rating_id($rating_id);

    $like = "INSERT INTO Likes (user_id, restaurant_id, author_id, rating_id) VALUES (:user_id, :restaurant_id, :author_id, :rating_id)";
    $statement2 = $db->prepare($like);
    $statement2->bindValue('user_id', $user_id);
    $statement2->bindValue('restaurant_id',  $restaurant_id);
    $statement2->bindValue('author_id', $author_id);
    $statement2->bindValue('rating_id', $rating_id);
    $statement2->execute();
    $statement2->closeCursor();

}

// update number_of_likes column in Review and deletes like from like table
function remove_like($restaurant_id, $user_id, $rating_id){
    global $db; 
    $review = "UPDATE Review SET number_of_likes = number_of_likes - 1 WHERE restaurant_id=:restaurant_id AND rating_id=:rating_id";
    $statement = $db->prepare($review);
    $statement->bindValue(':restaurant_id',$restaurant_id);
    $statement->bindValue(':rating_id',$rating_id);
    $statement->execute();
    $res = $statement->fetch();
    $statement->closeCursor();

    $author_id = get_user_id_from_rating_id($rating_id);

    $like = "DELETE FROM Likes WHERE user_id=:user_id AND restaurant_id=:restaurant_id AND author_id=:author_id AND rating_id=:rating_id";
    $statement2 = $db->prepare($like);
    $statement2->bindValue('user_id', $user_id);
    $statement2->bindValue('restaurant_id',  $restaurant_id);
    $statement2->bindValue('author_id', $author_id);
    $statement2->bindValue('rating_id', $rating_id);
    $statement2->execute();
    $statement2->closeCursor();

}

$already_liked = did_user_already_like($restaurant_id, $user_id, $rating_id);

if (!$already_liked){
    echo "user didn't like this. we will add the like now.";
    add_like($restaurant_id, $user_id, $rating_id);
} else {
    echo "user already liked";
    remove_like($restaurant_id, $user_id, $rating_id);
}

$id = $restaurant_id;

header("Location: https://www.cs.virginia.edu/~nts7bcj/hooseating/restaurant.php?id=$id");
// header("Location: https://localhost/hooseating/restaurant.php?id=$id");

?>