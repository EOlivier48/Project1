<!DOCTYPE html>
<html>
<head>

  <title>Welcome to test project 1</title>

</head>

<body>

  <h2>Trip Logger</h2>
<!--this is the view, it should only ask the controller for information from the model -->

  <a href="http://localhost/newuser.php">Add a User</br></a>
  <a href="http://localhost/newvisit.php">Log a Visit</a>
  <p> Select a user and submit to view information</p>
  <select name="users" form="userform">
    <option value="" selected>Select a User</option>
    <?php
      //ini_set('display_errors',1);


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
      //else {

      //}
//    }

     ?>
  </select>
  <form action = "showuser.php" id = "userform" method = "POST">
    <input type = "submit">
  </form>


</body>
</html>
