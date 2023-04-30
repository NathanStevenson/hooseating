<?php
// TOP OF EVERY PAGE WITH HTML
session_start();

$active_user = "";
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

require("connect-db.php");
require("utilities.php");
require("main_page_proc.php");
require("profile_page_db.php");

// getting current restaurant into $restaurant
$id = $_GET['id'];
function get_rest($id){
    global $db;
    $query = "SELECT * FROM Restaurant where restaurant_id=:id;";
    $statement = $db->prepare($query);
    $statement->bindValue(':id',$id);
    $statement->execute();
    $restaurant = $statement->fetch();
    $statement->closeCursor();
    return $restaurant;
}

// getting the four most recent reviews for the restaurant
function get_rest_reviews($id){
    global $db;
    $query = "SELECT * FROM Review WHERE restaurant_id=:id ORDER BY time_published DESC LIMIT 4;";
    $statement = $db->prepare($query);
    $statement->bindValue(':id',$id);
    $statement->execute();
    $reviews = $statement->fetchALL(PDO::FETCH_ASSOC);
    $statement->closeCursor();
    return $reviews;
}

$restaurant = get_rest($id);
$reviews = get_rest_reviews($id);

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

function get_username_from_id($id){
    global $db;
    $query = "SELECT name FROM User where user_id=:id";
    $statement = $db->prepare($query);
    $statement->bindValue('id', $id);
    $statement->execute();
    $user_name = $statement->fetch();
    $statement->closeCursor();
    return $user_name['name'];
}

function add_like($restaurant_id){
    
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

        a.button {
            display: inline-block;
            border: 1px solid;
            border-radius: 5px;
            padding: 10px;
            text-decoration: none;
            color: #fff;
            background-color: #007bff;
        }

        a.button:hover {
            background-color: #0069d9;
        }

        #heart {
            color: lightpink;
        }

        #heart:hover{
            color: red;
            cursor: pointer;
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
    <body style="background-color: #DFFFFD; height: 100vh;">
        <nav>
            <a href="https://www.cs.virginia.edu/~nts7bcj/hooseating/main_page.php/" class="fs-3 ps-5 fw-bold">Hoos Eating</a>
            <a href="https://www.cs.virginia.edu/~nts7bcj/hooseating/add_review.php/" class="fs-4 mt-1 ps-5">Add a Review</a>
            <a href="https://www.cs.virginia.edu/~nts7bcj/hooseating/profile_page.php/" class="fs-4 mt-1 ps-5 prof">My Profile</a>
            <form method="POST">
                <input type="submit" value="Log Out" name="logout" class="fs-4 mt-1 ps-5 prof" id="logout">
            </form>
        </nav>

        <div style="height: 45vh;">
            <!-- Background will eventually be the restaurant image on DB -->
            <div class="d-flex align-items-end border-bottm border-end border-2" style="float: left; width: 65%; height: 50vh; 
            background-image: url('images/restaurant_<?php echo $id % 25; ?>.jpg'); background-repeat: no-repeat; background-size: 100% 100%">
                <?php echo "<div style='font-size: 50px; color: white; font-weight: bold; padding-left: 5%;'>" . $restaurant['name'] . "</div>"?>
            </div>

            <div style="float: right; width: 35%; height: 50vh;">
                <div class='fw-bold fs-2 text-center mt-3'>Restaurant Info</div>
                <div class='fw-bold fs-4 text-center mt-2'>Address</div>
                <div class='text-center mb-3'><?php echo $restaurant['address']; ?></div>

                <div class='fw-bold fs-4 text-center'>Average Rating</div>
                <div class='text-center mb-3'><?php echo $restaurant['avg_rating']; ?></div>

                <div class='fw-bold fs-4 text-center'>Cuisine</div>
                <div class='text-center mb-4'><?php echo $restaurant['cuisine']; ?></div>

                <div class='text-center'>
                    <a class='button' href='https://www.cs.virginia.edu/~nts7bcj/hooseating/submit_review.php?id=<?php echo $id;?>'>Leave a Review</a>
                </div>
            </div>
        </div>

        <div class="mt-2">
            <div class="fw-bold fs-3 mb-3 ms-3">Most Recent Reviews</div>

            <?php
            echo '<div class="w-100 mx-auto mx-5 mt-3 d-flex justify-content-evenly">';
            foreach ($reviews as $r){
                $username = get_username_from_id($r['user_id']);
                $summary = $r['summary'];
                $rating = $r['rating'];
                $num_likes = $r['number_of_likes'];
                $time_pub = $r['time_published'];
                $id = $_GET['id'];
                echo "<div class='shadow border border-secondary border-3 rounded-3' style='background-color: white; width: 20%;'>
                    <div class='mx-3 mt-2'>
                        <div class='d-flex justify-content-between mb-2 border-bottom border-secondary border-4'>
                            <div>$username</div>
                            <div>$rating</div>
                        </div>
                        <div class='mb-2' style='height:135px;'>$summary</div>
                        <div class='mb-2 d-flex justify-content-between align-self-end'>
                            <div>$time_pub</div>
                            <form action='add_like.php' method='POST'>
                                <input type='hidden' name='rest_id' value='{$id}'>
                                <input type='hidden' name='user_id' value='{$r['user_id']}'>
                                <input type='hidden' name='rating_id' value='{$r['rating_id']}'>
                                <button type='submit' class='custom-button'>$num_likes<span class='square' title='Like'>&hearts;</span></button>
                            </form>
                        </div>
                    </div>
                </div>";
            }
            echo '</div>';

            ?>
        </div>                    
    </body>
</html>
