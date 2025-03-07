<?php

  $db = include '../config/database/Database.php';
  try {
    $connection = $db->getConnection();
    $query = 'SELECT * FROM user';
    $res = $connection->execute_query($query);
    
  } catch(Throwable $err) {
    echo $err;
  }
