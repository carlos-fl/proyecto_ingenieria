<?php

  class Regex {
    public static function isValidDNI(string $DNI): bool {
      $FIRST_PATTERN = "/\d{4}-\d{4}-\d{5}/";
      $SECOND_PATTERN = "/^\d{13}$/";

      return (preg_match($FIRST_PATTERN, $DNI) || preg_match($SECOND_PATTERN, $DNI));
    }

    public static function isValidApplicantCode(string $applicantCode): bool {
      $PATTERN = "/^\d{4}[a-zA-Z0-9_.-]{23}$/";
      return preg_match($PATTERN, $applicantCode);
    }

    public static function isValidToken(string $token): bool {
      $PATTERN = "/^[a-f0-9]{64}$/";
      return preg_match($PATTERN, $token);
    }
  }