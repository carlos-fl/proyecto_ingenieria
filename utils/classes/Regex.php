<?php

  class Regex {
    public static function isValidDNI(string $DNI): bool {
      $PATTERN = "/\d{4}-\d{4}\d{5}/";

      return preg_match($PATTERN, $DNI);
    }
  }