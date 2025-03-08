<?php

  class Environment {
    private static array $variables;
    /**
     * @param string $path
     * location of .env file
     */
    public static function read($path = __DIR__ . "/../../.env"): void {
      $result = [];

      if (!file_exists($path) || !is_readable($path)) {
        return;
      }

      $lines = file($path);
      if ($lines == false) {
        return;
      }

      foreach($lines as $line) {
        $line = trim($line);
        if (empty($line)) {
          continue;
        }

        [$key, $value] = explode("=", $line, 2);

        $key = trim($key);
        $value = trim($value);

        $result[$key] = $value;
      }
      self::$variables = $result;
    }

    public static function getVariables(): array {
      return self::$variables;
    }
  }

  Environment::read();

