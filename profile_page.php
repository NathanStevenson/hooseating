<?php

    // TOP OF EVERY PAGE WITH HTML
    session_start();

    $active_user = "";
    // if the user is not logged in then redirect them to the login_page
    if (!isset($_SESSION['username'])) {
        // redirect the user to the login page
        header("Location: https://www.cs.virginia.edu/~ffk9uu/hooseating/form.php/");
    }else{
        $active_user = $_SESSION['username'];
    }


    require("connect-db.php");
    require("utilities.php");
    require("main_page_proc.php");
    require("profile_page_db.php");

    $user_rated_restaurants = get_user_rated_retaurants($active_user);
    $num_rated_restaurants = sizeof($user_rated_restaurants);
    $user_reviews = get_user_reviews($active_user);
    $num_reviews = sizeof($user_reviews);

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

        div.restaurants{
            border-style: solid;
            padding: 15px;
            margin: 30px;
        }


        div.reviews {
            border-style: solid;
            padding: 15px;
            margin: 30px;
        }

        div.reviewtitle {
            float: left;
        }

        div.reviewrating {
            float: right;
        }

        nav a.prof{
            float:right;
        }
        </style>
    </head>
    <body style="background-color: #DFFFFD">
        <nav>
            <a href="https://www.cs.virginia.edu/~ffk9uu/hooseating/main_page.php/" class="fs-3 ps-5 fw-bold">Hoos Eating</a>
            <a href="https://www.cs.virginia.edu/~ffk9uu/hooseating/add_review.php/" class="fs-4 mt-1 ps-5">Find a Restaurant</a>
            <a href="https://www.cs.virginia.edu/~ffk9uu/hooseating/view_reviews.php/" class="fs-4 mt-1 ps-5">View Other Reviews</a>
            <a href="https://www.cs.virginia.edu/~ffk9uu/hooseating/profile_page.php/" class="fs-4 mt-1 ps-5 prof">My Profile</a>
        </nav>

        <div>
            <figure>
                <!-- //placeholder image -->
                <img src="../images/default.png" alt="Profile Picture">
                <figcaption><h1><?php echo $_SESSION['username']; ?></h1></figcaption>
                <figcaption>User Summary</figcaption>
            </figure>
        </div>



        
        <div style="background-color: white">
            <?php
                echo '<div class = "container mt-5">';
                    echo '<div class = "restaurants">';
                        echo '<h3>Your Rated Restaurants</h3>';
                        foreach (range(0,$num_rated_restaurants-1) as $restaurant){
                            //apparently in php periods are used to concatenate strings lmao
                            echo '<p>'.$user_rated_restaurants[$restaurant][0].'</p>';
                        }
                    echo '</div>';
                    
                    echo '<div class="reviews">';
                        echo '<h3>Your Reviews</h3>';
                        foreach (range(0,$num_reviews-1) as $review){
                            $restaurant = get_restuarant($user_reviews[$review]['restaurant_id'], $active_user);
                            //apparently in php periods are used to concatenate strings lmao
                            echo '<p>';
                                echo '<div class="reviewtitle">';
                                    echo '<h5>'.$restaurant[0][0].'</h5>';
                                echo '</div>';

                                echo '<div class="reviewrating">';
                                    echo '<h5>'.$user_reviews[$review]['rating'].'</h5>';
                                echo '</div>';
                            echo '</p>';
                            echo '<br>';

                            // echo '<p>'.$user_reviews[$review]['summary'].', Rating: '.$user_reviews[$review]['rating'].'</p>';
                            echo '<p>';
                                echo '<p>'.$user_reviews[$review]['summary'].'</p>';
                            echo '</p>';
                        }
                    echo '</div>';
                echo '</div>';
            ?>
        </div>

    </body>
</html>
