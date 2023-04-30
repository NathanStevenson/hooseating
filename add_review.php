<?php
    // TOP OF EVERY PAGE WITH HTML
    session_start();

    $active_user = "";
    // if the user is not logged in then redirect them to the login_page
    if (!isset($_SESSION['username'])) {
        // redirect the user to the login page
        header("Location: https://www.cs.virginia.edu/~nts7bcj/hooseating/form.php");
        // header("Location: form.php/");
    }else{
        $active_user = $_SESSION['username'];
    }

    require("connect-db.php");
    require("utilities.php");
    require("main_page_proc.php");
    require("profile_page_db.php");
    //Log Out
    if($_SERVER['REQUEST_METHOD']=='POST'){
        if(isset($_POST['logout'])){
            session_destroy();
            header("Location: https://www.cs.virginia.edu/~nts7bcj/hooseating/form.php");
        }
    }

    // add button
    if(isset($_POST['radd'])){
        header("Location: https://www.cs.virginia.edu/~nts7bcj/hooseating/add_restaurant.php");
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
    <body style="background-color: #DFFFFD">
        <nav>
            <a href="https://www.cs.virginia.edu/~nts7bcj/hooseating/main_page.php/" class="fs-3 ps-5 fw-bold">Hoos Eating</a>
            <a href="https://www.cs.virginia.edu/~nts7bcj/hooseating/add_review.php/" class="fs-4 mt-1 ps-5">Add a Review</a>
            <a href="https://www.cs.virginia.edu/~nts7bcj/hooseating/profile_page.php/" class="fs-4 mt-1 ps-5 prof">My Profile</a>
            <form method="POST">
                <input type="submit" value="Log Out" name="logout" class="fs-4 mt-1 ps-5 prof" id="logout">
            </form>
        </nav>
        <h1 class="text-center my-4">Restaurant Finder</h1>
        <div class="mx-auto w-75 mb-4" style="background-color: #DFFFFD">
            <form class="w-100 d-inline" style="background-color: white" method="get" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <input class="w-75 d-inline-block" style="line-height: 2.5em; background-color: white" type="text" id="rname" name="rname" 
            placeholder="Enter the restaurant you would like to review!">
            <button class="btn btn-primary d-inline-block ms-3" style="height: 2.8em;" type="submit">Search</button>
            </form>
            <form method="post"? class="d-inline ms-3" style="background-color: #DFFFFD">
                <button class="btn btn-primary d-inline-block" style="height: 2.8em;" type="submit" id="radd" name="radd">Add new Restaurant</button>
            </form>
        </div>
        <?php
        // search button logic
        if(isset($_GET['rname']) and strlen($_GET['rname']) > 0){
            $res = $_GET['rname'];
            $query = "SELECT * FROM Restaurant WHERE name LIKE :res LIMIT 4";
            $statement = $db->prepare($query);
            $statement->bindValue(':res', "$res%", PDO::PARAM_STR); //has to do this PDO jank to use LIKE :res for good search functionality
            $statement->execute();
            $result = $statement->fetchAll();
            $statement->closeCursor();
            
            if($result){  
                echo '<div class="w-100 mx-auto mx-5 mt-5 d-flex justify-content-evenly">';
                // print each returned restaurant name
                foreach ($result as &$val){
                    $name = $val['name'];
                    $id = $val['restaurant_id'];
                    $address = $val['address'];
                    $cuisine = $val['cuisine'];
                    $avg_rating = $val['avg_rating'];

                    echo"<div class='d-inline-block shadow border border-secondary border-3 rounded-3' style='background-color: white; width: 20%;'>
                            <div class='p-3'>
                                <div class='mb-2'><p class='fw-bold d-inline'>Name:</p> $name</div>
                                <div class='mb-2'><p class='fw-bold d-inline'>Address:</p> $address</div>
                                <div class='mb-2'><p class='fw-bold d-inline'>Average Rating:</p> $avg_rating</div>
                                <div class='mb-3'><p class='fw-bold d-inline'>Cuisine:</p> $cuisine</div>
                                <div class='text-center'>
                                    <a class='button' href='https://www.cs.virginia.edu/~nts7bcj/hooseating/restaurant.php?id=$id'>Restaurant Details</a>
                                </div>
                            </div>
                        </div>";                     
                }
                echo "</div>";   
            }
            else{
                echo "<h3>Not found; refine search or add new restaurant!</h3>";
            }
        }
        ?>
    </body>
</html>