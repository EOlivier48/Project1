<!DOCTYPE html>
<html>
<body>
  <h2>Trip Logger</h2>

  <a href="index.php">Home</br></a>
  <a href="newuser.php">Add a User</br></a>
  <a href="newvisit.php">Log a Visit</a>
<?php
  //get the new user info passed in from POST
  if(isset($_POST["user"]) && isset($_POST["state"]) && isset($_POST["date_visited"])) {
    $user_id = $_POST["user"];
    $state_id = $_POST["state"];
    $date_visited = $_POST["date_visited"];

  //validate the data and send it off to the model
  include 'projectlib.php';

  $m = new ModelClass;
  $m->initModel();
  $result = $m->addVisit($user_id,$state_id,$date_visited);
  if ($result) {
    echo "<p>Visit has been added successfully.</p>";
  }
  else {
    echo "<p>There was an error when trying to add your visit."
    . " Please go back and try again</p>";
  }
 }
 else {
   echo "<p>Error: Field was left blank. Please try again.</p>";
 }
?>
</body>
</html>
