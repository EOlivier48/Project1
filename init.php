<?php
//grab all our config data from serverconfig.ini
$config = iniTest('serverconfig.ini');
$hostname = $config['hostname'];
$username = $config['username'];
$password = $config['password'];
$dbname = $config['dbname'];

//connect to the db
$dbh = db_connect($hostname,$username,$password);

//set the error mode to exception
$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
//check to see if our DB exists
$dblist = db_listDBLike($dbname,$dbh);
if(sizeof($dblist)>0) {
  //if it does exist then continue onwards
  echo "db " . $dbname . " exists\n\r";
}
else {
  //if it does not exist then attempt to create it
  echo "db " . $dbname . " does not exist ... attempting to create\n\r";
  $createresult = createDB($dbname,$dbh);
  if($createresult) {
    echo "db created!\n\r";
  }
  else {
    echo "db creation failed.\n\r";
    die();
  }
}

//now that the db is garunteed to exist connect to it and check the TABLES
$dbh->exec('USE ' . $dbname);

//check each table and see if the columns we want exist on it

//does table 'people' exist?
$tableName = 'people';
$tbllist = db_listTablesLike($tableName,$dbh);
if (sizeof($tbllist) == 0) {
  //if table does not exist create table and its specific columns
  echo "table " . $tableName . " does not exist. attempting to create.\n\r";
  createNewPeople($dbh);
}
else {
  //if so check the columns
  $columns = array
  (
  array("id", "int(6) unsigned"),
  array("first_name", "varchar(30)"),
  array("last_name", "varchar(30)"),
  array("favorite_food", "varchar(30)")
  );

  $match = testTableColumns($tableName,$columns,$dbh);
  if($match <= 0) {
    echo "error columns in table " . $tableName . " do not match! exiting!\n\r";
    die();
  }
}

//does table 'states' exist?
$tableName = 'states';
$tbllist = db_listTablesLike($tableName,$dbh);
if (sizeof($tbllist) == 0) {
  //if table does not exist create table and its specific columns
  echo "table " . $tableName . " does not exist. attempting to create.\n\r";
  createNewStates($dbh);
}
else {
  //if so check the columns
  $columns = array
  (
  array("id", "int(6) unsigned"),
  array("state_name", "varchar(30)"),
  array("state_abbreviation", "varchar(2)")
  );

  $match = testTableColumns($tableName,$columns,$dbh);
  if($match <= 0) {
    echo "error columns in table " . $tableName . " do not match! exiting!\n\r";
    die();
  }
}

//does table 'visits' exist?
$tableName = 'visits';
$tbllist = db_listTablesLike($tableName,$dbh);
if (sizeof($tbllist) == 0) {
  //if table does not exist create table and its specific columns
  echo "table " . $tableName . " does not exist. attempting to create.\n\r";
  createNewVisits($dbh);
}
else {
  //if so check the columns
  $columns = array
  (
  array("id", "int(6) unsigned"),
  array("person_id", "int(6) unsigned"),
  array("state_id", "int(6) unsigned"),
  array("date_visited","date")
  );

  $match = testTableColumns($tableName,$columns,$dbh);
  if($match <= 0) {
    echo "error columns in table " . $tableName . " do not match! exiting!\n\r";
    die();
  }
}

//see how many rows are in states
//if it's empty, initialize from states.txt
$fileName = 'states.txt';
$sql = "SELECT * FROM states";
$statement = db_tryQuery($sql,$dbh);
$result = $statement->fetchAll();
if(sizeof($result) == 0) {
  echo "table 'states' empty. attempting to fill.\n\r";
  //open file for import
  if(($fh = fopen($fileName,"r")) !== FALSE) {
    //grab each line in the file
    while(($data = fgetcsv($fh,0,",")) !== FALSE) {
      //if that line has data in it
      if(sizeof($data) > 0){
        //add line from file to database
        $sql = "INSERT INTO states (state_name, state_abbreviation)
        VALUES ( '" . $data[0] . "', '" . $data[1] . "')";
        $statement = db_tryQuery($sql,$dbh);
      }
    }
  }
  else {
    echo "error could not open file " . $fileName . "\n\r";
  }

}
elseif (sizeof($result) != 50) {
  echo "error in table 'states' size was: " . sizeof($result) . ". Please correct or empty table.\n\r";
}

//------------------------------------------------------------------------------

function testTableColumns($table,$columns,$dbh) {
    $sql = "DESCRIBE " . $table;
    $query = db_tryQuery($sql,$dbh);
    $result = $query->fetchAll();

    $match = sizeof($result);

    if (sizeof($result)>0) {
      $i = 0;
      foreach ($result as $row) {
        if($row['Field'] != $columns[$i][0] || $row['Type'] != $columns[$i][1]){
          echo "error in table: " . $table . " db column: " . $row['Field'] . " | " . $row['Type'] . " does not match expected " . $columns[$i][0] . " | " . $columns[$i][1] . "\n\r";
          $match = -1;
        }
        $i++;
      }
    }
    else{
      echo "error in table: " . $table . " table is empty.";
    }
    return $match;
}


//tries to safely execute any sql query you pass it
//returns the PDOStatement object
function db_tryQuery($sql,$dbh) {
  try {
    $statement = $dbh->prepare($sql);
    $result = $statement->execute();

    if($result == FALSE) {
      echo $sql . " failed!\n\r";
    }

    return $statement;

  }
  catch(PDOException $e) {
      echo "tried: " . $sql . "\n\rerror: " . $e->getMessage() . "\n\r";
      die();
  }

}

//people - id, first_name, last_name, favorite_food
function createNewPeople($dbh){

  $sql = "CREATE TABLE IF NOT EXISTS people (
  id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  first_name VARCHAR(30) NOT NULL,
  last_name VARCHAR(30) NOT NULL,
  favorite_food VARCHAR(30) NOT NULL
  )";
  $query = db_tryQuery($sql,$dbh);
}

//states - id, state_name, state_abbreviation
function createNewStates($dbh){

  $sql = "CREATE TABLE IF NOT EXISTS states (
  id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  state_name VARCHAR(30) NOT NULL,
  state_abbreviation VARCHAR(2) NOT NULL
  )";
  $query = db_tryQuery($sql,$dbh);
}

//visits - id, person_id (FKEY), state_id (FKEY), date_visited
function createNewVisits($dbh) {

  $sql = "CREATE TABLE IF NOT EXISTS visits (
  id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  person_id INT(6) UNSIGNED NOT NULL,
  state_id INT(6) UNSIGNED NOT NULL,
  date_visited DATE NOT NULL,
  FOREIGN KEY (person_id) REFERENCES people(id),
  FOREIGN KEY (state_id) REFERENCES states(id)
  )";
  $query = db_tryQuery($sql,$dbh);
}

//function iniTest
//tests the dbname in the ini file against a regex to check for bad characters
//returns the object from the parsed ini file if successful
//dies with error message if not successful
function iniTest($ininame) {
  $config = parse_ini_file('serverconfig.ini');

    preg_match('/^[a-zA-Z_][a-zA-Z0-9_]*$/',$config['dbname'],$matches);
    if(sizeof($matches)==0){
      echo "Error: invalid dbname " . $config['dbname'] . "\n\r";
      die();
    }
  return $config;
}

//function db_listDBLike
//returns the list of databases like the requested db using the db handle provided
//requires the db to be connected
function db_listDBLike($dbName,$dbh) {
  $sql = "SHOW DATABASES LIKE '" . $dbName . "'";

  $query = db_tryQuery($sql,$dbh);
  return $query->fetchAll(PDO::FETCH_COLUMN);
}

//function db_listColumns
//returns the columns in the requested table using the db handle provided
//requires the db to be connected
function db_listTablesLike($table,$dbh) {
  $sql = "SHOW TABLES LIKE '" . $table . "'";

  $query = db_tryQuery($sql,$dbh);
  return $query->fetchAll(PDO::FETCH_COLUMN);
}

//function db_createDB
//attempts to create a db using the name passed and the databas handle provided
function createDB($dbname,$dbh) {
  $sql = "CREATE DATABASE IF NOT EXISTS " . $dbname;
  $query = db_tryQuery($sql,$dbh);
}


//attempts to connect to the database using the creds in serverconfig.ini
//if successful it returns the PDO object
//on failure it throws an error message and dies
function db_connect($hostname, $username, $password) {
  //Defining connection as static stops you from connecting multiple times
  static $connection;

  //check if we already connected if not then try to
  if(!isset($connection)) {
    try {
      //get the credentials from the ini file
      $connection = new PDO('mysql:host=' . $hostname, $username, $password);

      echo "connection successful\n\r";
    }
    catch(PDOException $e) {
      echo "connection failed \n\rerror: " . $e->getMessage() . "\n\r";
      die();
    }
  }
  return $connection;
}

 ?>
