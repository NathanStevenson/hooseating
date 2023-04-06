<?php 
  require("connect-db.php");
  require("friend-db.php");

  $all=getAll();
  $updatefriend=null;
  if($_SERVER['REQUEST_METHOD']=='POST'){
    if (!empty($_POST['actionBtn']) && ($_POST['actionBtn'] == "Update")) {
      $updatefriend = getFriendByName($_POST['id']);
      var_dump($updatefriend);
      // editContact($_POST['name'], $_POST['major'], $_POST['year']);
    } else if (!empty($_POST['actionBtn']) && ($_POST['actionBtn'] == "add")) {
      addFriend($_POST['name'], $_POST['major'], $_POST['year']);
    }  
    
    else if (!empty($_POST['actionBtn']) && ($_POST['actionBtn'] == "delete")) {
      deleteContact($_POST['id']);
      $all = getAll();
    }  

    if (!empty($_POST['actionBtn']) && ($_POST['actionBtn'] == "confirmupdate")){
      update($_POST['name'], $_POST['major'], $_POST['year']);
      $all=getAll();
    }
  }
// var_dump($all);



?>


<!-- 1. create HTML5 doctype -->
<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">  
  
  <!-- 2. include meta tag to ensure proper rendering and touch zooming -->
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- 
  Bootstrap is designed to be responsive to mobile.
  Mobile-first styles are part of the core framework.
   
  width=device-width sets the width of the page to follow the screen-width
  initial-scale=1 sets the initial zoom level when the page is first loaded   
  -->
  
  <meta name="author" content="your name">
  <meta name="description" content="include some description about your page">  
    
  <title>Bootstrap example</title>
  
  <!-- 3. link bootstrap -->
  <!-- if you choose to use CDN for CSS bootstrap -->  
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
  
  <!-- you may also use W3's formats -->
  <!-- <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css"> -->
  
  <!-- 
  Use a link tag to link an external resource.
  A rel (relationship) specifies relationship between the current document and the linked resource. 
  -->
  
  <!-- If you choose to use a favicon, specify the destination of the resource in href -->
  <link rel="icon" type="image/png" href="http://www.cs.virginia.edu/~up3f/cs4750/images/db-icon.png" />
  
  <!-- if you choose to download bootstrap and host it locally -->
  <!-- <link rel="stylesheet" href="path-to-your-file/bootstrap.min.css" /> --> 
  
  <!-- include your CSS -->
  <!-- <link rel="stylesheet" href="custom.css" />  -->
       
</head>

<body>
<div class="container">

<form name="mainForm" action="form.php" method="post"> 
  <div class="row mb-3 mx-3">
    Name:
    <input type="text" class="form-control" name="name" required 
    value="<?php if ($updatefriend!=null) echo $updatefriend['name']; ?>"
    />    

  </div>  
  <div class="row mb-3 mx-3">
    Major:
    <input type="text" class="form-control" name="major" required 
    value="<?php if ($updatefriend!=null) echo $updatefriend['major']; ?>"
    />        
  </div>  
  <div class="row mb-3 mx-3">
    Year:
    <input type="text" class="form-control" name="year" required 
    value="<?php if ($updatefriend!=null) echo $updatefriend['year']; ?>"
    />       
  </div>  
  <input type="submit" class="btn btn-primary" value="add" name="actionBtn" title="submit">
  <input type="submit" class="btn btn-primary" value="confirmupdate" name="actionBtn" title="submit">
  
</form>     







<div class="row justify-content-center">  
<table class="w3-table w3-bordered w3-card-4 center" style="width:70%">
  <thead>
  <tr style="background-color:#B0B0B0">
    <th width="30%">Name        
    <th width="30%">Major        
    <th width="30%">Year 
    <th width="30%">  
    <th width="30%">  

  </tr>
  </thead>
<?php foreach ($all as $i): ?>
  <tr>
     <td><?php echo $i['name']; ?></td>
     <td><?php echo $i['major']; ?></td>        
     <td><?php echo $i['year']; ?></td>    
     <!-- <td><input type="submit" class="btn btn-primary" value="delete" name="actionBtn" title="delete"></td> -->
     <td>
      <form action="form.php" class="btn btn-primary" method="post">
        <input type="submit" name="actionBtn" value="Update" />
        <input type="hidden" name="id" 
        value="<?php echo $i['name']; ?>"/>
      </form>
     </td>
     <td>
      <form action="form.php" class="btn btn-primary" method="post">
        <input type="submit" name="actionBtn" value="delete" />
        <input type="hidden" name="id" 
        value="<?php echo $i['name']; ?>"/>
      </form>
     </td>
  </tr>
<?php endforeach; ?>
</table>
</div>   

  
</div>    
</body>
</html>