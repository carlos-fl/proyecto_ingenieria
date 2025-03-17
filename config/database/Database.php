<?php

 include_once __DIR__ . '/../env/Environment.php';

 $env = Environment::getVariables();

  class Database {
    private string $host;
    private string $dbUser;
    private string $dbPassword;
    private string $dbName;
    private int $dbPort;    

    /**
     * used to return only one instance of the Database object. if not gives error when using include_once
     */
    private static Database $instance;

    /**
     * @param string $host
     * localhost for local development. add ip for production environment
     * @param string $dbName
     * database name
     * @param string $dbUser
     * @param string $dbPassword
     * @param int $dbPort
     * @return void
     */
    public function __construct(string $host = 'localhost', string $dbName, string $dbUser, string $dbPassword, int $dbPort = 3306) {
      $this->host = $host;
      $this->dbUser = $dbUser;
      $this->dbPassword = $dbPassword;
      $this->dbPort = $dbPort; 
      $this->dbName = $dbName;
    }

    public function getConnection(): mysqli {
      $mysqli = new mysqli(hostname: $this->host, username: $this->dbUser, password: $this->dbPassword, database: $this->dbName, port: $this->dbPort);

      if ($mysqli->connect_error)
        throw new Error('unable to connect to database');

      return $mysqli;
    } 

    public static function setInstance(): void {
      $env = $GLOBALS['env'];
      $database = new Database($env['DB_HOST'], $env['DB_NAME'], $env['DB_USER'], $env['DB_PASSWORD'], intval($env['DB_PORT']));
      self::$instance = $database;
    }

    public static function getDatabaseInstace(): Database {
      return self::$instance;
    }

    /**
     * @param string $query
     * this is the query to call the procedure
     * @param string $storedProcedureTypes
     * the quantity and types of stored procedure
     * @param array $parameters
     * the actual values to pass to the stored procedure
     * @param mysqli $mysqli
     * the mysqli object to make the calls to the database
     * @return array
     * 
     */
    public function callStoredProcedure(string $query, string $storedProcedureTypes = ' ', array $parameters = [], mysqli $mysqli): mysqli_result|bool {
      $stmt = $mysqli->prepare($query);
      if (count($parameters) > 0) {
        $stmt->bind_param($storedProcedureTypes, ...$parameters);
      }
      $stmt->execute();

      $result = $stmt->get_result();
      return $result;
    }
  }

  Database::setInstance();