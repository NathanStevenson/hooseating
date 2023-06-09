<?php

    require("connect-db.php");
    require("utilities.php");
    require("main_page_proc.php");

    // TOP OF EVERY PAGE WITH HTML
    session_start();

    $active_user = "";
    // if the user is not logged in then redirect them to the login_page
    if (!isset($_SESSION['username'])) {
        // redirect the user to the login page
        header("Location: https://www.cs.virginia.edu/~nts7bcj/hooseating/form.php");
    }else{
        $active_user = $_SESSION['username'];
    }

    // Two Session variables (global) one that holds the sort by type and the other details the restaurants (default all rests with best ratings)
    // Checking if the Session Variables are not set and if they are not then giving them default values (otherwise just use the ones it is set as)
    if(!isset($_SESSION['filter_type'])){
        $_SESSION['filter_type'] = "best";
    }

    if(!isset($_SESSION['restaurants'])){
        $_SESSION['restaurants'] = "all";
    }

    // By default the restaurants are displayed listing the best rated restaurants first
    $top_rated_rests = display_restaurants($_SESSION['filter_type'], $_SESSION['restaurants']);

    // If any form is submitted this code is run
    if($_SERVER['REQUEST_METHOD']=='POST'){
        // sort by the best restaurants
        if(!empty($_POST['actionBtn']) && $_POST['actionBtn']=="Best Restaurants"){
            $_SESSION['filter_type'] = "best";
            $top_rated_rests = display_restaurants($_SESSION['filter_type'], $_SESSION['restaurants']);
        }
        // sort by the worst restaurants
        if(!empty($_POST['actionBtn']) && $_POST['actionBtn']=="Worst Restaurants"){
            $_SESSION['filter_type'] = "worst";
            $top_rated_rests = display_restaurants($_SESSION['filter_type'], $_SESSION['restaurants']);
        }
        // sort restaurants alphabetically
        if(!empty($_POST['actionBtn']) && $_POST['actionBtn']=="Alphabetically"){
            $_SESSION['filter_type'] = "alphabetically";
            $top_rated_rests = display_restaurants($_SESSION['filter_type'], $_SESSION['restaurants']);
        }

        // logic for displaying certain types of restaurants
        // All
        if(!empty($_POST['actionBtn']) && $_POST['actionBtn']=="All Restaurants"){
            $_SESSION['restaurants'] = "all";
            $top_rated_rests = display_restaurants($_SESSION['filter_type'], $_SESSION['restaurants']);
        }

        // Bars
        if(!empty($_POST['actionBtn']) && $_POST['actionBtn']=="Bars"){
            $_SESSION['restaurants'] = "bars";
            $top_rated_rests = display_restaurants($_SESSION['filter_type'], $_SESSION['restaurants']);
        }

        // Dine-In
        if(!empty($_POST['actionBtn']) && $_POST['actionBtn']=="Dine-In"){
            $_SESSION['restaurants'] = "dine-in";
            $top_rated_rests = display_restaurants($_SESSION['filter_type'], $_SESSION['restaurants']);
        }

        // Fast Food
        if(!empty($_POST['actionBtn']) && $_POST['actionBtn']=="Fast Food"){
            $_SESSION['restaurants'] = "fast-food";
            $top_rated_rests = display_restaurants($_SESSION['filter_type'], $_SESSION['restaurants']);
        }

        //Log Out
        if(isset($_POST['logout'])){
            session_destroy();
            header("Location: https://www.cs.virginia.edu/~nts7bcj/hooseating/form.php");
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

        nav a:hover {
            color: navy;
            text-decoration: underline;
        }

        nav a.prof{
            float:right;
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
        
        #sortbydropdown input{
            color: white;
            background-color: lightgray;
            width: 100%;
        }

        #sortbydropdown input:hover{
            color: navy;
        }

        </style>
    </head>
    <body style="background-color: #DFFFFD">
        <nav>
            <a href="https://www.cs.virginia.edu/~nts7bcj/hooseating/main_page.php" class="fs-3 ps-5 fw-bold">Hoos Eating</a>
            <a href="https://www.cs.virginia.edu/~nts7bcj/hooseating/add_review.php" class="fs-4 mt-1 ps-5">Add a Review</a>
            <a href="https://www.cs.virginia.edu/~nts7bcj/hooseating/profile_page.php" class="fs-4 mt-1 ps-5 prof">My Profile</a>
            <form method="POST">
                <input type="submit" value="Log Out" name="logout" class="fs-4 mt-1 ps-5 prof" id="logout">
            </form>
        </nav>

        <div class="d-md-inline-flex">
            <!-- Allows users to select which restaurants they are looking at reviews for -->
            <button onclick="toggleOptions('rest_type')" class="ms-5 mt-4 btn btn-secondary"><?php echo session_restaurant_str($_SESSION['restaurants']); ?></button>
            <div id="rest_type" style="display: none;" >
                <form class="px-3 py-1" name="sortbyform" action="main_page.php" method="post" style="position: absolute; cursor: pointer;" id="sortbydropdown">
                    <input type="submit" class="d-block border border-secondary text-center p-2 fw-bold rounded-top" value="All Restaurants" name="actionBtn">
                    <input type="submit" class="d-block border border-secondary text-center p-2 fw-bold" value="Bars" name="actionBtn">
                    <input type="submit" class="d-block border border-secondary text-center p-2 fw-bold rounded-bottom" value="Dine-In" name="actionBtn">
                    <input type="submit" class="d-block border border-secondary text-center p-2 fw-bold rounded-bottom" value="Fast Food" name="actionBtn">
                </form>
            </div>

            <!-- DropDown button to filter the restaurants by -->
            <button onclick="toggleOptions('sortby')" class="ms-5 mt-4 btn btn-secondary"><?php echo session_filter_str($_SESSION['filter_type']); ?></button>
            <div id="sortby" style="display: none;" >
                <form class="px-3 py-1" name="sortbyform" action="main_page.php" method="post" style="position: absolute; cursor: pointer;" id="sortbydropdown">
                    <input type="submit" class="d-block border border-secondary text-center p-2 fw-bold rounded-top" value="Best Restaurants" name="actionBtn">
                    <input type="submit" class="d-block border border-secondary text-center p-2 fw-bold" value="Worst Restaurants" name="actionBtn">
                    <input type="submit" class="d-block border border-secondary text-center p-2 fw-bold rounded-bottom" value="Alphabetically" name="actionBtn">
                </form>
            </div>
        </div>

        <script>
        // Script to Toggle Dropdown button 
        function toggleOptions(dropdown) {
        var options = document.getElementById(dropdown);
            if (options.style.display === "none") {
                options.style.display = "block";
            } else {
                options.style.display = "none";
            }
        }
        </script>


        <?php
        // Counter variable to keep track of the number of elements printed
        $count = 0;
        echo '<div class="container mt-5">';
        // Outer loop for rows
        foreach (range(1, 2) as $row) {
            echo '<div class="row mb-2">';
            // Inner loop for columns
            foreach (range(1, 4) as $col) {
                // the periods are used in php to concat the echo calls
                echo '<div class="w-25">' . 
                        '<img src="images/restaurant_'.$top_rated_rests[$count]['restaurant_id'] % 25 .'.jpg" alt="Restaurant Photo" class="rounded w-100" style="height: 25vh;">'.
                        '<div class="fw-bold">' . $top_rated_rests[$count]['name'] . '</div>' . 
                        '<div>' . $top_rated_rests[$count]['address'] . '</div>' . 
                        '<div class="d-inline">';
                        if($_SESSION['restaurants'] == 'all'){
                            echo '<div class="d-inline fw-bold">Cuisine: </div>' . $top_rated_rests[$count]['cuisine'];
                        }
                        if($_SESSION['restaurants'] == 'bars'){
                            echo '<div class="d-inline fw-bold">Theme: </div>' . $top_rated_rests[$count]['theme'];
                        } 
                        if($_SESSION['restaurants'] == 'dine-in'){
                            echo '<div class="d-inline fw-bold">Formality: </div>' . $top_rated_rests[$count]['formality'];
                        } 
                        if($_SESSION['restaurants'] == 'fast-food'){
                            echo '<div class="d-inline fw-bold">Wait Time: </div>' . $top_rated_rests[$count]['wait_time'] . ' mins';
                        } 
                        echo '<div class="d-inline fw-bold"> Avg Rating: </div>'.$top_rated_rests[$count]['avg_rating'] .'</div>' 
                    .'</div>';
                $count++;
            }
            echo '</div>';
        }
        echo '</div>';
        ?>
    </body>
</html>
