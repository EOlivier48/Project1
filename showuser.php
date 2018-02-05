<!DOCTYPE html>
<html>
<body>
<?php
  //get the user passed in from POST
  $user_id = $_POST["users"];

  //initialize the db connection and get the user's name and food
  ini_set('display_errors',1);
  include 'projectlib.php';

  $m = new ModelClass;
  $m->initModel();
    $user_info = $m->listUser($user_id);
  foreach ($user_info as $user) {
  echo "<p>" . $user[1] . " " . $user[2] . " " . $user[3] . "</p>";
  }

  //get the user's trips and display them in a table
 ?>
</body>
</html>
