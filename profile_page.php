<?php

    // TOP OF EVERY PAGE WITH HTML
    session_start();

    $active_user = "";
    // if the user is not logged in then redirect them to the login_page
    if (!isset($_SESSION['username'])) {
        // redirect the user to the login page
        header("Location: https://www.cs.virginia.edu/~nts7bcj/hooseating/form.php/");
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
    $user = get_info_from_username($active_user);
    $user_summary = $user['summary'];


    //Log Out
    if($_SERVER['REQUEST_METHOD']=='POST'){
        if(isset($_POST['logout'])){
            session_destroy();
            header("Location: https://www.cs.virginia.edu/~nts7bcj/hooseating/form.php/");
        }
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
        nav a:hover {
            color: navy;
            text-decoration: underline;
        }

        img {
            clip-path: circle();
            position: relative;
            width: 50%;
            height: auto;
            text-align: center;
        }

        div.restaurants{
            border-style: solid;
            padding: 15px;
            margin: 30px;
            background-color: white;
        }


        div.reviews {
            border-style: solid;
            padding: 15px;
            margin: 30px;
            background-color: white;
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
            <a href="https://www.cs.virginia.edu/~nts7bcj/hooseating/main_page.php/" class="fs-3 ps-5 fw-bold">Hoos Eating</a>
            <a href="https://www.cs.virginia.edu/~nts7bcj/hooseating/add_review.php/" class="fs-4 mt-1 ps-5">Add Review</a>
            <a href="https://www.cs.virginia.edu/~nts7bcj/hooseating/view_reviews.php/" class="fs-4 mt-1 ps-5">View Other Reviews</a>
            <a href="https://www.cs.virginia.edu/~nts7bcj/hooseating/profile_page.php/" class="fs-4 mt-1 ps-5 prof">My Profile</a>
            <form method="POST">
                <input type="submit" value="Log Out" name="logout" class="fs-4 mt-1 ps-5 prof" id="logout">
            </form>
        </nav>

        <div class='d-flex pt-3'>
            <div class='w-50 d-flex flex-column text-center'>
                <div class='my-4'>
                    <img src="../images/default.png" alt="Profile Picture">
                </div>
                

                <div>
                    <div class='mb-3 d-inline-block'><h1><?php echo $_SESSION['username']; ?></h1></div>
                    <div class='d-inline-block '>
                        <a class='text-decoration-none fs-3' href="https://www.cs.virginia.edu/~nts7bcj/hooseating/edit_profile.php/">&#9881</a>
                    </div>
                    <div class='p-4 rounded-3 w-75 mx-auto' style='background-color: lightgray;'>
                        <div class='fw-bold fs-5'>User Summary</div>
                        <?php echo $user_summary; ?>
                    </div>
                </div>
            </div>

            <?php
                echo '<div class="d-flex flex-column justify-content-center text-center w-50">';
                    echo '<div class="restaurants">';
                        echo '<h3>Your Favorite Restaurants</h3>';
                        foreach (range(0,$num_rated_restaurants-1) as $restaurant){
                            //apparently in php periods are used to concatenate strings lmao
                            echo '<p>'.$user_rated_restaurants[$restaurant][0].'</p>';
                        }
                    echo '</div>';
                    
                    echo '<div class="reviews">';
                        echo '<h3>Your Top Reviews</h3>';
                        foreach (range(0,$num_reviews-1) as $review){
                            $restaurant = get_restuarant($user_reviews[$review]['restaurant_id'], $active_user);
                            //apparently in php periods are used to concatenate strings lmao
                            echo '<p>';
                                echo '<div class="reviewtitle">';
                                    echo '<h5>'.$restaurant[0][0].'  <a class="text-decoration-none fs-3" href="https://www.cs.virginia.edu/~nts7bcj/hooseating/edit_review.php/?review_id='.$user_reviews[$review]['rating_id'].'">&#9881</a>'.'</h5>';
                                echo '</div>';

                                echo '<div class="reviewrating">';
                                    echo '<h5>'.$user_reviews[$review]['rating'].'</h5>';
                                echo '</div>';
                            echo '</p>';
                            echo '<br>';
                            
                            // summary
                            echo '<p>';
                                echo '<p>'.$user_reviews[$review][4].'</p>';
                            echo '</p>';
                        }
                    echo '</div>';
                echo '</div>';
            ?>
        </div>
    </body>
</html>
