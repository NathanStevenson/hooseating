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
    require("edit_review_db.php");

    define('REVIEW_ID', $_GET['review_id']);
    $review_data = get_review(REVIEW_ID);
    $summary=$review_data[0]['summary'];
    $user_id=$review_data[0]['user_id'];
    $rating_id=$review_data[0]['rating_id'];
    $rest_id=$review_data[0]['restaurant_id'];

    if($_SERVER['REQUEST_METHOD'] == 'POST'){
        if(isset($_POST['confirm_edit'])){
            update_review(REVIEW_ID, $_POST['summary'], $_POST['rating']);
            $error_message = "";
            header("Location: https://www.cs.virginia.edu/~ffk9uu/hooseating/profile_page.php/");
        }
        else if(isset($_POST['delete_review'])){
            delete_review(REVIEW_ID, $user_id, $rest_id);
            $error_message = "";
            header("Location: https://www.cs.virginia.edu/~ffk9uu/hooseating/profile_page.php/");
        }       
        //Log Out
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
    
    <body style="background-color:#DFFFFD">
        <nav>       
            <a href="https://www.cs.virginia.edu/~nts7bcj/hooseating/main_page.php/" class="fs-3 ps-5 fw-bold">Hoos Eating</a>
            <a href="https://www.cs.virginia.edu/~nts7bcj/hooseating/add_review.php/" class="fs-4 mt-1 ps-5">Find a Restaurant</a>
            <a href="https://www.cs.virginia.edu/~nts7bcj/hooseating/view_reviews.php/" class="fs-4 mt-1 ps-5">View Other Reviews</a>
            <a href="https://www.cs.virginia.edu/~nts7bcj/hooseating/profile_page.php/" class="fs-4 mt-1 ps-5 prof">My Profile</a>
            <form method="POST">
                <input type="submit" value="Log Out" name="logout" class="fs-4 mt-1 ps-5 prof" id="logout">
            </form>
        </nav>
        <h2 class='text-center my-3'>Edit Review for: <?php echo get_restaurant_name($review_data[0]['restaurant_id'])['name']?></h2>
        <form method="post" class=" shadow w-50 mx-auto border border-secondary border-3 rounded-3 mt-2" style="background-color: white;">
            <div class='mx-3 my-4'>
                <!-- value for inputs will be whatever is currently in the database for them -->
                <div class="mb-3">
                    <label for="summary" class="form-label fw-bold">Rating</label>
                    <input type="text" class="form-control" id="rating" name="rating" value=<?php echo $review_data[0]['rating']; ?>>
                </div>

                <div class="mb-3">
                    <label for="fav_rests" class="form-label fw-bold">Summary</label>
                    <input type="text" class="form-control" id="summary" name="summary" value="<?php echo $review_data[0]['summary']; ?>">            
                </div>

                <!-- <div class="mb-3">
                    <div class='mb-3 fw-bold'>Update Your Profile Photo (jpeg, jpg, png)</div>
                    <label for="file-upload">Choose a file:</label>
                    <input type="file" id="file-upload" name="file-upload">         
                </div> -->

                <div class='w-25 mx-auto'>
                    <button type="submit" name="confirm_edit" class="btn btn-primary">Confirm</button>
                    <button style="background-color: red;margin: 10px;" type="submit" name="delete_review" class="btn btn-primary">Delete</button>
                </div>

                <!-- <form action="edit_review_db.php" method='POST'>
                    <input type='hidden' name='rec_id' value="{$_GET['review_id']}">
                    <button type="submit" name="confirm_edit" class="btn btn-primary">Confirm</button>
                </form> -->

                <div class='text-danger fw-bold mt-3'></div><?php echo $error_message;?></div>
            </div>           
        </form>
    </body>
</html>