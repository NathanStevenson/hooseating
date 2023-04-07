<?php 
    require("connect-db.php");
    require("utilities.php");
    require("main_page_proc.php");

    $top_rated_rests = top_rated_restaurants();
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
        </style>
    </head>
    <body>
        <nav>
            <a href="https://www.cs.virginia.edu/~nts7bcj/hooseating/main_page.php/" class="fs-3 ps-5 fw-bold">Hoos Eating</a>
            <a href="https://www.cs.virginia.edu/~nts7bcj/hooseating/add_review.php/" class="fs-4 mt-1 ps-5">Add a Review</a>
            <a href="https://www.cs.virginia.edu/~nts7bcj/hooseating/view_reviews.php/" class="fs-4 mt-1 ps-5">View Other Reviews</a>
        </nav>

        <?php
        // Counter variable to keep track of the number of elements printed
        $count = 0;
        echo '<div class="container mt-5">';
        // Outer loop for rows
        foreach (range(1, 3) as $row) {
            echo '<div class="row">';
            // Inner loop for columns
            foreach (range(1, 4) as $col) {
                // the periods are used in php to concat the echo calls
                echo '<div class="col border border-secondary border-2 rounded-3 m-3">' . '<div>' . $top_rated_rests[$count]['name'] . '</div>' . 
                              '<div>' . $top_rated_rests[$count]['address'] . '</div>' . 
                              '<div>' . $top_rated_rests[$count]['avg_rating'] . '</div>' .'</div>';
                $count++;
            }
            echo '</div>';
        }
        echo '</div>';
        ?>
    </body>
</html>
