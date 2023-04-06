<?php 
  require("connect-db.php");

?>
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
<div class="container">

<form name="mainForm" action="form.php" method="post"> 
  <div class="row mb-3 mx-3">
    Username:
    <input type="text" class="form-control" name="name" required 
    />    

  </div>  
  <div class="row mb-3 mx-3">
    Password:
    <input type="text" class="form-control" name="major" required 
    />        
  </div>  

  <input type="submit" class="btn btn-primary" value="LOGIN" name="actionBtn" title="submit">
  
</form>     



  
</div>    
</body>
</html>