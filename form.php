<?php
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
                if($auth){
                //TODO: redirect
                echo "CORRECT USERNAME AND PASSWORD";
                }else{
                echo "INCORRECT USERNAME OR PASSWORD";
                }
            }
        }
        //  Code that checks whether or not the username currently exists when the user clicks the register button on the login page
        if (!empty($_POST['actionBtn']) && $_POST['actionBtn']=="REGISTER"){
            $res = username_taken($_POST['name']);
            if ($res){
                $username_exists_msg = "That username already exists!";
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
        <title>Bootstrap example</title> 
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
        <link rel="icon" type="image/png" href="http://www.cs.virginia.edu/~up3f/cs4750/images/db-icon.png" />
    </head>

    <body>
        <!-- User Login Form -->
        <div class="container">
            <form name="mainForm" action="form.php" method="post"> 
                <div class="row mb-3 mx-3">
                    Username:
                    <input type="text" class="form-control" name="name" required />    
                </div>  
                <div class="row mb-3 mx-3">
                    Password:
                    <input type="password" class="form-control" name="password" required />        
                </div>  

                <input type="submit" class="btn btn-primary" value="LOGIN" name="actionBtn" title="submit">
                <input type="submit" class="btn btn-primary" value="REGISTER" name="actionBtn" title="submit">
            </form>     
        </div>    
    </body>
</html>