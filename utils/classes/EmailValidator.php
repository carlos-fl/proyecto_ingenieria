<?php

  class EmailValidator {
    private static $normalEmailPattern = "/^[a-zA-Z0-9._%-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/";
    private static $institutionalDomain = "unah.hn";

    public static function validate(string $email): bool {
      return preg_match(self::$normalEmailPattern, $email);
    }

    public static function validateInstitutionalEmail(string $email): bool {
      return (self::isInstitutionalEmail($email) && self::validate($email));
    }


    public static function isInstitutionalEmail(string $email): bool {
      $domain = strrchr($email, "@");
      return substr($domain, 1) === self::$institutionalDomain;
    }
  }