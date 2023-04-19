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

        </style>
    </head>
    <body>
        <nav>
            <a href="https://www.cs.virginia.edu/~ffk9uu/hooseating/main_page.php/" class="fs-3 ps-5 fw-bold">Hoos Eating</a>
            <a href="https://www.cs.virginia.edu/~ffk9uu/hooseating/add_review.php/" class="fs-4 mt-1 ps-5">Add a Review</a>
            <a href="https://www.cs.virginia.edu/~ffk9uu/hooseating/view_reviews.php/" class="fs-4 mt-1 ps-5">View Other Reviews</a>
            <a href="https://www.cs.virginia.edu/~ffk9uu/hooseating/profile_page.php/" class="fs-4 mt-1 ps-5 prof">My Profile</a>
        </nav>
        <h1>Please Enter Restaurant Name:</h1>
        <div style="display: flex; justify-content: left;">
            <form method="get" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <input type="text" id="rname" name="rname">
            <button type="submit">Search</button>

            </form>
            <form method="post"?>
            <button type="submit" id="radd" name="radd">Add new Restaurant</button>
            </form>
        </div>
</body>
</html>

<?php

    // add button
    if(isset($_POST['radd'])){
        header("Location: https://www.cs.virginia.edu/~ffk9uu/hooseating/add_restaurant.php");
        // header("Location: add_restaurant.php/"); // doesn't work when current URL ends in /
    }

    // search button logic
    else if(isset($_GET['rname']) and strlen($_GET['rname']) > 0){
        $res = isset($_GET['rname']) ? $_GET['rname'] : '';
        $query = "SELECT * FROM restaurant WHERE name LIKE :res";
        $statement = $db->prepare($query);
        $statement->bindValue(':res', "%$res%", PDO::PARAM_STR); //has to do this PDO jank to use LIKE :res
        $statement->execute();
        $result = $statement->fetchAll();
        $statement->closeCursor();
        
        if($result){  
            // print each returned restaurant name
            foreach ($result as &$val){
                $name = $val['name'];
                $id = $val['restaurant_id'];
                // echo "<div> <h3><a class='button' href='restaurant.php?id=$id'>$name</a></h3> </div>";
                echo "<div> <h3><a class='button' href='https://www.cs.virginia.edu/~ffk9uu/hooseating/restaurant.php?id=$id'>$name</a></h3> </div>";
                     
            }    
        }
        else{
            echo "<h3>Not found; refine search or add new restaurant!</h3>";
        }
    }
?>
