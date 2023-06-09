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

    // Finds first available restauraunt_id (taken from login_register.php)
    function max_rest_id(){
        global $db;
        $query = "SELECT MAX(restaurant_id) FROM Restaurant";
        $statement = $db->prepare($query);
        $statement->execute();
        $results = $statement->fetchColumn();
        $statement->closeCursor();
        return $results;
    }

    // if restaurant already exists via address & name
    function restaurant_taken($address, $name){
        global $db;
        $query = "SELECT * FROM Restaurant WHERE address=:address AND name=:name";
        $statement = $db->prepare($query);
        $statement->bindValue(':address', $address);
        $statement->bindValue(':name', $name);
        $statement->execute();
        $result = $statement->fetch();
        $statement->closeCursor();
        return $result ;
    }

    function add_restaurant($address, $name, $cuisine){
        global $db;
        $id = max_rest_id()+1;
        $query = "INSERT INTO Restaurant VALUES (:id, NULL, :cuisine, :address, :name, NULL);";
        $statement= $db->prepare($query);
        $statement->bindValue(':id', $id);
        $statement->bindValue('cuisine', $cuisine);
        $statement->bindValue('address', $address);
        $statement->bindValue('name',$name);
        $statement->execute();
        $statement->closeCursor();
    }

    if(isset($_POST['rname']) and strlen($_POST['rname']) > 0 and strlen($_POST['address']) > 0){
        $address = $_POST['address'];
        $name = $_POST['rname'];
        $cuisine = $_POST['cuisine'];
        $taken = restaurant_taken($address, $name);
        debug_to_console($taken);
        if(!$taken){
            add_restaurant($address, $name, $cuisine);
            header("Location: https://www.cs.virginia.edu/~nts7bcj/hooseating/add_review.php");
        }
        else {
            $error_message = "This restaurant already exists!";
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

        .result {
            border: 1px solid;
            padding: 10px;
            margin-bottom: 10px;
        }

        .result h3 {
        font-size: 20px;
        margin-bottom: 5px;
        }

        .result p {
        font-size: 16px;
        margin-bottom: 0;
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
            <a href="https://www.cs.virginia.edu/~nts7bcj/hooseating/main_page.php" class="fs-3 ps-5 fw-bold">Hoos Eating</a>
            <a href="https://www.cs.virginia.edu/~nts7bcj/hooseating/add_review.php" class="fs-4 mt-1 ps-5">Find a Restaurant</a>
            <a href="https://www.cs.virginia.edu/~nts7bcj/hooseating/profile_page.php" class="fs-4 mt-1 ps-5 prof">My Profile</a>
            <form method="POST">
                <input type="submit" value="Log Out" name="logout" class="fs-4 mt-1 ps-5 prof" id="logout">
            </form>
        </nav>

        <h1 class='text-center mt-4 mb-4'>Add a New Restaurant</h1>
        <div class=" shadow w-50 mx-auto border border-secondary border-3 rounded-3 mt-2" style="background-color: white;">
            <form method="post" class="p-4 w-100" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                <div class="mb-3">
                    <label for="rname" class="form-label">Restaurant Name:</label>
                    <input type="text" class="form-control" id="rname" name="rname">
                </div>
                <div class="mb-3">
                    <label for="address" class="form-label">Address:</label>
                    <input type="text" class="form-control" id="address" name="address">
                </div>
                <div class="mb-3">
                <label for="cuisine" class="form-label">Cuisine (select one):</label>
                <select class="form-control" id="cuisine" name="cuisine">
                <?php
                    $query = "SELECT DISTINCT cuisine FROM Restaurant";
                    $statement = $db->prepare($query);
                    $statement->execute();
                    $result = $statement->fetchAll(PDO::FETCH_COLUMN);
                    $statement->closeCursor();
                    foreach ($result as $option) {
                    echo "<option value='$option'>$option</option>";
                    }
                ?>
                </select>
                </div>
                <div class="w-25 mx-auto">
                    <button type="submit" class="btn btn-primary">Submit Restaurant</button>
                </div>
                <?php echo "<div class='text-danger fw-bold text-center mt-2'>".$error_message."</div>"; ?>
            </form>
        </div>
    </body>
</html>
