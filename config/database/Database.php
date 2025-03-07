<?php

  $env = include __DIR__ . '/../env/Environment.php';

  class Database {
    private string $host;
    private string $dbUser;
    private string $dbPassword;
    private string $dbName;
    private int $dbPort;    

    /**
     * @param string $host
     * localhost for local development. add ip for production environment
     * @param string $dbName
     * @param string $dbUser
     * @param string $dbPassword
     * @param int $dbPort
     * @return void
     */
    public function __construct(string $host = 'localhost', string $dbName, string $dbUser, string $dbPassword, int $dbPort = 3306) {
      $this->host = $host;
      $this->dbName = $dbName;
      $this->dbUser = $dbUser;
      $this->dbPassword = $dbPassword;
      $this->dbPort = $dbPort; 
    }

    public function getConnection(): mysqli {
      $mysqli = new mysqli(hostname: $this->host, username: $this->dbUser, password: $this->dbPassword, database: $this->dbName, port: $this->dbPort);

      if ($mysqli->connect_error)
        throw new Error('unable to connect to database');

      return $mysqli;
    } 
  }

  
  return new Database($env['DB_HOST'], $env['DB_NAME'], $env['DB_USER'], $env['DB_PASSWORD'], intval($env['DB_PORT']));