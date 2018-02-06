<!DOCTYPE html>
<html>
<body>

<?php
  //get list of users and states from the server
 ?>

<h2>Trip Logger</h2>
<a href="http://localhost/">Home</br></a>
<a href="http://localhost/newuser.php">Add A User</a>
<h4>Log A Visit.</h4>


<form action="addingvisit.php" method="POST">
  Select User:<br>
  <select name="user"><br><br>
    <option value="" selected>Select a User</option>
    <?php
      //insert list of users into the form
      include 'projectlib.php';


      $m = new ModelClass;
      $m->initModel();
      $userTable = $m->listUsers();
      if(sizeof($userTable) > 0) {
      //if there are users then add them to the table
        foreach ($userTable as &$user) {
          echo "<option value ='" . $user[0] . "'>" . $user[1] . " " . $user[2] . "</option>";
        }
      }

     ?>
  </select>

  Select State:<br>
  <select name="state"><br><br>
        <option value="" selected>Select a State</option>
    <?php
      //insert list of states into the form
      $stateTable = $m->listStates();
      foreach ($stateTable as &$state) {
        echo "<option value ='" . $state[0] . "'>" . $state[1] . " (" . $state[2] . ")</option>";
      }
     ?>
  </select>

  Date Visited:<br>
  <input type="date" name="date_visited"><br><br>

  <input type="submit" name="submit" value="Submit">
</form>

</body>
</html>
