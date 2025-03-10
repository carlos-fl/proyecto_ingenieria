<?php

  function getJsonData() {
    $rawData = @file_get_contents('php://input');
    return json_decode($rawData);
  }