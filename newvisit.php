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


<form action="addingvisit.php" method="post">
  Select User:<br>
  <select name="user"><br><br>
    <?php
      //insert list of users into the form
     ?>
  </select>

  Select State:<br>
  <select name="state"><br><br>
    <?php
      //insert list of states into the form
     ?>
  </select>

  Date Visited:<br>
  <input type="date" name="date_visited"><br><br>

  <input type="submit" name="submit" value="Submit">
</form>

</body>
</html>
