<?php

  class PasswordValidator {
    private static $MIN_LENGTH = 8;
    private static $REQUIRES_UPPERCASE = true;
    private static $REQUIRES_LOWERCASE = true;
    private static $REQUIRES_DIGITS = true;
    private static $REQUIRES_SPECIAL_CHARACTERS = true;
    private static $SPECIAL_CHARS = '!@#$%^&*()_-+=<>?/';

    /**
     * @param string $password
     * @return bool
     * return true if it is valid otherwise returns false
     */
    public static function validate(string $password): bool {
      if (strlen($password) < self::$MIN_LENGTH) {
        return false;
      } 

      if (self::$REQUIRES_UPPERCASE && !preg_match('/[A-Z]/', $password)) {
        return false;
      }

      if (self::$REQUIRES_LOWERCASE && !preg_match('/[a-z]/', $password)) {
        return false;
      }

      if (self::$REQUIRES_DIGITS && !preg_match('/\d/', $password)) {
        return false;
      }

      if (self::$REQUIRES_SPECIAL_CHARACTERS && !preg_match('/[' . preg_quote(self::$SPECIAL_CHARS, '/') .']/', $password)) {
        return false;
      }


      return true;
    }
  }