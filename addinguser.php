<!DOCTYPE html>
<html>
<body>
  <h2>Trip Logger</h2>

  <a href="index.php">Home</br></a>
  <a href="newuser.php">Add a User</br></a>
  <a href="newvisit.php">Log a Visit</a>
<?php
  //get the new user info passed in from POST
  if(isset($_POST["first_name"]) && isset($_POST["last_name"]) && isset($_POST["fav_food"])) {
    $first_name = $_POST["first_name"];
    $last_name = $_POST["last_name"];
    $fav_food = $_POST["fav_food"];

  //validate the data and send it off to the model
  include 'projectlib.php';

  $m = new ModelClass;
  $m->initModel();
  $result = $m->addUser($first_name,$last_name,$fav_food);
  if ($result) {
    echo "<p>User " . $first_name . " " . $last_name
    . " has been added successfully.</p>";
  }
  else {
     echo "<p>There was an error when trying to add the new user."
     . " Please go back and try again</p>";
   }
 }
 else{
   echo "<p>Error: Field was left blank, please try again.</p>";
 }
?>
</body>
</html>
