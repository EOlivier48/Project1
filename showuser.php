<!DOCTYPE html>
<html>
<head>
    <style>
        table, th, td {
            border: 1px solid black;
        }
    </style>
</head>
<body>
  <h2>Trip Logger</h2>

  <a href="http://localhost/">Home</br></a>
  <a href="http://localhost/newuser.php">Add a User</br></a>
  <a href="http://localhost/newvisit.php">Log a Visit</a>
<?php
  //get the user passed in from POST
  $user_id = $_POST["users"];

  //initialize the db connection and get the user's name and food
  ini_set('display_errors',1);
  include 'projectlib.php';

  $m = new ModelClass;
  $m->initModel();
  $user_info = $m->listUser($user_id);
  if(sizeof($user_info) > 0) {
    echo "<p>Your selected user is: " . $user_info[0][1] . " " . $user_info[0][2] . "</p>";
    echo "<p>Their favorite food is: " . $user_info[0][3] . "</p>";

  //get the user's trips and display them in a table

    $visit_table = $m->listVisitsUser($user_id);
    $state_table = $m->listStates();

    if(sizeof($visit_table) >0) {
      echo "<table >";
      echo "<tr>";
      echo "<th>State</th>";
      echo "<th>Date Visited</th>";
      echo "</tr>";
      foreach ($visit_table as $visit) {
        echo "<tr>";
        foreach ($state_table as $state) {
          if($state[0] == $visit[2]) {
            echo "<td>" . $state[1] . " (" . $state[2] . ")</td>";
          }

        }
        $tempdate = explode("-",$visit[3]);
        echo "<td>" . $tempdate[1] . "-" . $tempdate[2] . "-" . $tempdate[0] . "</td>";
        echo "</tr>";
     }
     echo "</table>";
   }
   else {
     echo "<p>The selected user has not visited any states.</p>";
   }
 }
  else {
    echo "<p>No user selected. Please return home and select a user.</p>";
  }
 ?>
</body>
</html>
