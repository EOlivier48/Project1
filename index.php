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
  </select>
  <form action="" id="userform">
    <input type="submit">
  </form>


</body>
</html>