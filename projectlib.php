<?php

  class ModelClass
  {
    protected $dbh = NULL;

    //function initModel
    //this function should read the database ini file and then connect to the
    //database indicated therin.
    //returns TRUE if successful, kills the process on failure.
    //this should likely be changed to fail more gracefully
    function initModel() {
      //grab all our config data from serverconfig.ini
      $config = $this->iniTest('serverconfig.ini');
      $hostname = $config['hostname'];
      $username = $config['username'];
      $password = $config['password'];
      $dbname = $config['dbname'];

      //connect to the db
      $this->dbh = $this->db_connect($hostname,$username,$password);

      //set the error mode to exception
      $this->dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

      //use the correct database
      $this->dbh->exec('USE ' . $dbname);

      return TRUE;
    }

    //function db_tryQuery tries a query using a prepared statement
    //this function is private to stop execution of arbitrary queries from the
    //controller
    private function db_tryQuery($sql) {
      try {
        $statement = $this->dbh->prepare($sql);
        $result = $statement->execute();
        /*
        if($result == FALSE) {
          //echo $sql . " failed!\n\r";
        }
        */
        return $statement;

      }
      catch(PDOException $e) {
          //echo "tried: " . $sql . "\n\rerror: " . $e->getMessage() . "\n\r";
          die();
      }

    }

    //function add user
    function addUser($first_name,$last_name,$fav_food) {
      //sanitize data to make sure it fits the table structure varchar(30)
      $pattern = "/(\w)+/";
      $first_name = substr($first_name,0,30);
      $last_name = substr($last_name,0,30);
      $fav_food = substr($fav_food,0,30);
      preg_match($pattern,$first_name,$matches_fn);
      preg_match($pattern,$last_name,$matches_ln);
      preg_match($pattern,$fav_food,$matches_ff);
      if((sizeof($matches_fn) > 0) && (sizeof($matches_ln) > 0) && (sizeof($matches_ff) > 0)) {
        //do insert
        $sql = "INSERT INTO people (first_name, last_name, favorite_food)
        VALUES ('". $first_name ."', '" . $last_name . "', '" . $fav_food . "')";
        $statement = $this->db_tryQuery($sql);

      }

    }

    //function add visit
    function addVisit($person_id,$state_id,$date_visited) {

      //make sure parameters are valid
      if(validateDate($date_visited,'MM/DD/YY') && 1000000 > $person_id && $person_id > 0 && 1000000 > $state_id && $state_id > 0) {
        //date is a valid date, now format it correctly
        $tempdate = explode('/',$date_visited);
        $formattedDate = $tempdate[2] . "-" . $tempdate[0] . "-" . $tempdate[1];

        //do insert
        $sql = "INSERT INTO visits (person_id, state_id, date_visited)
        VALUES (". $person_id .", " . $state_id . ", '" . $formattedDate . "')";
        $statement = $this->db_tryQuery($sql);

      }
    }

    //function list users
    function listUsers() {
      //query to get all users
      $sql = "SELECT * FROM people";
      //return array of users user_id/first/last/food
      $statement = $this->db_tryQuery($sql);
      return $statement->fetchAll();
    }

    function listUser($user_id) {
      //query to get all users
      $sql = "SELECT * FROM people WHERE id=" . $user_id;
      //return array of users user_id/first/last/food
      $statement = $this->db_tryQuery($sql);
      return $statement->fetchAll();
    }

    //function list visits for a particular user
    function listVisitsUser($user_id) {
      //query to get all visits for the user
      $sql = "SELECT * FROM vists WHERE user_id=" . $user_id;
      //return array of visits state_id/date
      $statement = $this->db_tryQuery($sql);
      return $statement->fetchAll();
    }

    //function list states
    function listStates() {
      //query to get list of states in db
      $sql = "SELECT * FROM states";
      //return array of states state_id/state_name/state_abbreviation
      $statement = $this->db_tryQuery($sql);
      return $statement->fetchAll();
    }

    function validateDate($date, $format = 'Y-m-d H:i:s') {
        $d = DateTime::createFromFormat($format, $date);
        return $d && $d->format($format) == $date;
    }

    //function iniTest
    //tests the dbname in the ini file against a regex to check for bad characters
    //returns the object from the parsed ini file if successful
    //dies with error message if not successful
    function iniTest($ininame) {
      $config = parse_ini_file('serverconfig.ini');

        preg_match('/^[a-zA-Z_][a-zA-Z0-9_]*$/',$config['dbname'],$matches);
        if(sizeof($matches)==0){
          //echo "Error: invalid dbname " . $config['dbname'] . "\n\r";
          die();
        }
      return $config;
    }

    function db_connect($hostname, $username, $password) {
      //Defining connection as static stops you from connecting multiple times
      static $connection;

      //check if we already connected if not then try to
      if(!isset($connection)) {
        try {
          //get the credentials from the ini file
          $connection = new PDO('mysql:host=' . $hostname, $username, $password);

          //echo "connection successful\n\r";
        }
        catch(PDOException $e) {
          //echo "connection failed \n\rerror: " . $e->getMessage() . "\n\r";
          die();
        }
      }
      return $connection;
    }

  }



 ?>
