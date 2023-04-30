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

    function get_user_info($name){
        global $db;
        $query = "SELECT user_id, summary FROM User WHERE name=:name";
        $statement = $db->prepare($query);
        $statement->bindValue(':name', $name);
        $statement->execute();
        $result = $statement->fetch();
        $statement->closeCursor();
        return $result;
    }

    function get_fav_rests($user_id){
        global $db;
        $query = "SELECT * FROM User_fav_restaurant WHERE user_id=:user_id;";
        $statement = $db->prepare($query);
        $statement->bindValue(':user_id', $user_id);
        $statement->execute();
        $result = $statement->fetchAll();
        $statement->closeCursor();
        return $result;
    }

    $error_message = "";

    // getting the original information for the user so that they can edit it
    $user_info = get_user_info($active_user);
    $user_id = $user_info['user_id'];
    $summary = $user_info['summary'];
    $old_fav_rests1 = "";
    $old_fav_rests2 = "";
    $old_fav_rests3 = "";

    $all_fav_rests = get_fav_rests($user_id);
    if(!empty($all_fav_rests[0])){
        $old_fav_rests1 = $all_fav_rests[0]['favorite_restaurant'];
    }
    if(!empty($all_fav_rests[1])){
        $old_fav_rests2 = $all_fav_rests[1]['favorite_restaurant'];
    }
    if(!empty($all_fav_rests[2])){
        $old_fav_rests3 = $all_fav_rests[2]['favorite_restaurant'];
    }
    

    function update_profile_user($active_user, $summary){
        global $db;
        $query = "UPDATE User SET summary=:summary WHERE name=:name;"; 
        $statement = $db->prepare($query);
        $statement->bindValue(':name', $active_user);
        $statement->bindValue(':summary', $summary);
        $statement->execute();
        $statement->closeCursor();
    }

    // need to think about how to incorporate/edit the user_fav_table so it is actually useful
    function update_fav_rest($user_id, $fav_rest, $old_fav_rests){
        global $db;
        $query = "UPDATE User_fav_restaurant SET favorite_restaurant=:fav_rest WHERE user_id=:user_id AND favorite_restaurant=:old_fav_rests;"; 
        $statement = $db->prepare($query);
        $statement->bindValue(':user_id', $user_id);
        $statement->bindValue(':fav_rest', $fav_rest);
        $statement->bindValue(':old_fav_rests', $old_fav_rests);
        $statement->execute();
        $statement->closeCursor();
    }

    if($_SERVER['REQUEST_METHOD'] == 'POST'){
        if(isset($_POST['edit_prof'])){
            $summary = $_POST['summary'];
            $fav_rests1 = $_POST['fav_rests1'];
            $fav_rests2 = $_POST['fav_rests2'];
            $fav_rests3 = $_POST['fav_rests3'];

            // Update the profile and the saved restaurants
            update_profile_user($active_user, $summary);
            // Updates the old valies with the new ones
            update_fav_rest($user_id, $fav_rests1, $old_fav_rests1);
            update_fav_rest($user_id, $fav_rests2, $old_fav_rests2);
            update_fav_rest($user_id, $fav_rests3, $old_fav_rests3);

            $error_message = "";
            header("Location: https://www.cs.virginia.edu/~nts7bcj/hooseating/profile_page.php/");
            }
        }
        //Log Out
        if(isset($_POST['logout'])){
            session_destroy();
            header("Location: https://www.cs.virginia.edu/~nts7bcj/hooseating/form.php/");
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
        nav a.prof{
            float:right;
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
            <a href="https://www.cs.virginia.edu/~nts7bcj/hooseating/profile_page.php/" class="fs-4 mt-1 ps-5 prof">My Profile</a>
            <form method="POST">
                <input type="submit" value="Log Out" name="logout" class="fs-4 mt-1 ps-5 prof" id="logout">
            </form>
        </nav>
        <h2 class='text-center my-3'>Update Your Profile</h2>
        <form method="post" class="shadow w-50 mx-auto border border-secondary border-3 rounded-3 mt-2" style="background-color: white;" action="edit_profile.php">
            <div class='mx-3 my-4'>
                <!-- value for inputs will be whatever is currently in the database for them -->
                <div class="mb-3">
                    <label for="summary" class="form-label fw-bold">User Summary (max 255)</label>
                    <textarea type="text" class="form-control" id="summary" name="summary"><?php echo $summary; ?></textarea>
                </div>

                <div class="mb-3">
                    <label for="fav_rests" class="form-label fw-bold">Favorite Restaurants #1</label>
                    <input type="text" class="form-control" id="fav_rests1" name="fav_rests1" value="<?php echo $old_fav_rests1; ?>">            
                </div>

                <div class="mb-3">
                    <label for="fav_rests" class="form-label fw-bold">Favorite Restaurants #2</label>
                    <input type="text" class="form-control" id="fav_rests2" name="fav_rests2" value="<?php echo $old_fav_rests2; ?>">            
                </div>

                <div class="mb-3">
                    <label for="fav_rests" class="form-label fw-bold">Favorite Restaurants #3</label>
                    <input type="text" class="form-control" id="fav_rests3" name="fav_rests3" value="<?php echo $old_fav_rests3; ?>">            
                </div>

                <div class='w-25 mx-auto'>
                    <button type="submit" name="edit_prof" class="btn btn-primary">Update Profile</button>
                </div>

                <div class='text-danger fw-bold mt-3'></div><?php echo $error_message;?></div>
            </div>           
        </form>
    </body>
</html>