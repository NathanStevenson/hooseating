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

    $user = get_info_from_username($active_user);
    $user_summary = $user['summary'];
    $user_id = $user['user_id'];

    $fav_rests = get_fav_rests($user_id);

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
                    <div class='p-4 rounded-3 w-75 mx-auto border border-secondary border-3 shadow' style='background-color: lightgray;'>
                        <div class='fw-bold fs-5'>User Summary</div>
                        <?php echo $user_summary; ?>
                    </div>
                </div>
            </div>

            <?php
                echo '<div class="d-flex flex-column justify-content-center text-center w-50">';
                    echo '<div class="rounded-3 border border-secondary border-3 shadow ms-3 me-4 mt-5 mb-4 px-3 py-1" style="background-color: white;">';
                        echo '<h3>Your Favorite Restaurants</h3>';
                        foreach (range(0,2) as $fav_restaurant){
                            //apparently in php periods are used to concatenate strings lmao
                            $rest_info = get_rest_info($fav_rests[$fav_restaurant]['favorite_restaurant']);
                            echo '<div class="py-2">'.
                                    '<div class="d-inline-block fw-bold fs-4">'.$rest_info['name'].'</div>'." ".
                                    '<div class="d-inline-block"><div class="d-inline-block fw-bold">Address: </div> '.$rest_info['address'].'</div>'." ".
                                    '<div class="d-inline-block"><div class="d-inline-block fw-bold">Average Rating: </div> '.$rest_info['avg_rating'].'</div>'.
                                '</div>';
                        }
                    echo '</div>';
                    
                    echo '<div class="rounded-3 border border-secondary border-3 shadow ms-3 me-4 mt-4 mb-2 px-3 py-1" style="background-color: white;">';
                        echo '<h3>Your Top Reviews</h3>';
                        // only show top 3 reviews
                        foreach (range(0,2) as $review){
                            $restaurant = get_restuarant($user_reviews[$review]['restaurant_id'], $active_user);
                            if (sizeof($restaurant)==0) {
                                break;
                            }
                            //apparently in php periods are used to concatenate strings lmao
                            echo '<div class="d-flex justify-content-between my-3 px-2 py-2 border border-2 rounded-3">';
                                // number of likes
                                echo '<div class="ms-2">';
                                    echo '<div style="color:red;">&hearts;</div>';
                                    echo '<div>'.$user_reviews[$review]['number_of_likes'].'</div>';
                                echo '</div>';

                                // Restaurant name, rating, and summary
                                echo '<div>';
                                    echo '<div class="fw-bold">'.$restaurant[0][0].':  '.$user_reviews[$review]['rating'].'</div>';
                                    echo '<div>'.$user_reviews[$review][4].'</div>';
                                echo '</div>';

                                // Gear to send user to the edit review page
                                echo '<div>
                                        <a class="text-decoration-none fs-3" href="https://www.cs.virginia.edu/~nts7bcj/hooseating/edit_review.php/?review_id='.$user_reviews[$review]['rating_id'].'">&#9881</a>
                                    </div>';
                            echo '</div>';
                        }
                    echo '</div>';
                echo '</div>';
                // Name of rest: $restaurant[0][0]
                // Link to review: <a class="text-decoration-none fs-3" href="https://www.cs.virginia.edu/~nts7bcj/hooseating/edit_review.php/?review_id='.$user_reviews[$review]['rating_id'].'">&#9881</a>'
                // Rating given: $user_reviews[$review]['rating']
                // Review summary: $user_reviews[$review][4]
                // num_likes: $user_reviews[$review]['number_of_likes']
            ?>
        </div>
    </body>
</html>
