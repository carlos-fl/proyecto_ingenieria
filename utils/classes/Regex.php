<?php

  class Regex {
    public static function isValidDNI(string $DNI): bool {
      $FIRST_PATTERN = "/\d{4}-\d{4}-\d{5}/";
      $SECOND_PATTERN = "/^\d{13}$/";

      return (preg_match($FIRST_PATTERN, $DNI) || preg_match($SECOND_PATTERN, $DNI));
    }
  }