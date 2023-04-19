<?php

    // THIS GOES AT THE TOP OF EACH PHP WEB PAGE SUPERGLOBAL AREA: $_SESSION is created
    session_start();
	// Require code for other files needed for the user login form
	// Code that connects the database
	require("connect-db.php");
	// file containing functions relating to registering and logging in users
	require("login_register.php");
	// function that has some nice utility functions
	require("utilities.php");

    // Code that executes upon a POST command need to check the value of the button to proceed with logic
    $authAttempt = 0;
    if($_SERVER['REQUEST_METHOD']=='POST'){
        // Code to validate if the username and password are legit upon login button being clicked
        if(!empty($_POST['actionBtn']) && $_POST['actionBtn']=="LOGIN"){
            $auth = checkLogin($_POST['name'], $_POST['password']);
            $authAttempt = 1;
            if($authAttempt){
                // If the user is authenticated
                if($auth){
                    // Stores user in the session variable
                    $_SESSION['username'] = $_POST['name']; 
                    // Updates the error message 
                    $wrong_credential_msg = "";
                    // Redirect the user to the main page
                    // change the redirect location for local testing with your computing ID
                    header("Location: https://www.cs.virginia.edu/~ffk9uu/hooseating/main_page.php/");
                    // header("Location: main_page.php/");
                // User entered the wrong info
                }else{
                    $wrong_credential_msg = "Invalid Credentials!";
                }
            }
        }
        //  Code that checks whether or not the username currently exists when the user clicks the register button on the login page
        if (!empty($_POST['actionBtn']) && $_POST['actionBtn']=="REGISTER"){
            $res = username_taken($_POST['name']);
            // If there is another user in the database with that name
            if ($res){
                $username_exists_msg = "That username already exists!";
            //  otherwise clear that msg to the user
            }
            elseif(strlen($_POST['name']) > 30){
                $username_exists_msg = "Username is too long (max: 30)!";
            }
            else{
                // Stores user in the session variable
                $_SESSION[$res['name']] = $res['name'];

                $username_exists_msg = "";
                // Code that assigns each user a new unique ID
                $userid = max_user_id() + 1;
                add_user($userid, $_POST['name'], $_POST['password']);
                $active_user = username_taken($_POST['name']);
                // Redirect the user to the main page
                header("Location: https://www.cs.virginia.edu/~ffk9uu/hooseating/main_page.php/");
                // header("Location: main_page.php/");
            }
        }
    }
?>

<!-- Standard Header that should be included before every HTML page that we have (includes Bootstrap URL and metadata info) -->
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
    </head>

    <!-- Should align the login box in the middle of the page vertically and mx-auto does horizontally -->
    <body style="background-color:lightskyblue">
        <!-- User Login Form -->
        <div class="shadow rounded-3 mx-auto w-25" style="background-color:white; margin-top:10%;">
            <h2 class="mt-3 pt-4 text-center">Hoos Eating Login</h2>
            <form name="mainForm" action="form.php" method="post" class="mt-5 px-2"> 
                <div class="row mb-3 mx-3" style="color:lightskyblue">
                    Username:
                    <input type="text" class="form-control" name="name" required />    
                </div>  
                <div class="row mb-3 mx-3" style="color:lightskyblue">
                    Password:
                    <input type="password" class="form-control" name="password" required />        
                </div>  
                <div class="mt-5 pb-4 mx-auto w-75 d-flex justify-content-evenly">
                    <input type="submit" class="btn btn-primary" value="LOGIN" name="actionBtn" title="Login">
                    <input type="submit" class="btn btn-primary" value="REGISTER" name="actionBtn" title="Register">
                </div>

                <p class="text-center fw-bold pb-3" style="color:red">
                    <?php if($wrong_credential_msg != "") echo $wrong_credential_msg; ?>
                    <?php if($username_exists_msg != "") echo $username_exists_msg; ?>   
                </p> 
            </form>     
        </div>    
    </body>
</html>