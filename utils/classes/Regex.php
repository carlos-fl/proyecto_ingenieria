<?php

  class Regex {
    public static function isValidDNI(string $DNI): bool {
      $FIRST_PATTERN = "/\d{4}-\d{4}-\d{5}/";
      $SECOND_PATTERN = "/^\d{13}$/";

      return (preg_match($FIRST_PATTERN, $DNI) || preg_match($SECOND_PATTERN, $DNI));
    }

    public static function isValidPassport(string $passport) {
      $PATTERN = "/^[A-Z]{1,2}[0-9]{6,9}$/";
      return preg_match($PATTERN, $passport);
    }

    public static function isValidIdentification($identification) {
      return self::isValidDNI($identification) || self::isValidPassport($identification);
    }

    public static function isValidApplicantCode(string $applicantCode): bool {
      $PATTERN = "/^\d{4}[a-zA-Z0-9_.-]{23}$/";
      return preg_match($PATTERN, $applicantCode);
    }

    public static function isValidToken(string $token): bool {
      $PATTERN = "/^[a-f0-9]{64}$/";
      return preg_match($PATTERN, $token);
    }

    public static function isValidExamName(string $name) {
      $PATTERN = "/[A-Za-z0-9]$/";
      return preg_match($PATTERN, $name);
    }

    public static function isValidScore(string $score) {
      $PATTERN = "/^(?:1[0-7][0-9]{2}|[1-9][0-9]{2}|1800)$/";
      return preg_match($PATTERN, $score);
    }
  }