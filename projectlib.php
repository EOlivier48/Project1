<?php

  class model {
    private $dbh;
    private $hostname;
    private $username;
    private $password;
    private $dbname;

    //function initModel
    //this function should read the database ini file and then connect to the
    //database indicated therin.
    //returns TRUE if successful, kills the process on failure.
    //this should likely be changed
    function initModel() {
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

      //use the correct database
      $dbh->exec('USE ' . $dbname);

      return TRUE;
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
