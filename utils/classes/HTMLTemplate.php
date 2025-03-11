<?php

  class HTMLTemplate {
    const PATTERN = '/\{\{\s*(\w+)\s*\}\}/';

    /**
     * @param string $filename
     * @param array $data
     * associative array containing variables to replace
     * @param string $path
     * @return string
     */
    public static function parse(string $filename, array $data = [], string $path = __DIR__ . '/../../src/services/emailNotifications/emailsBlueprints'): string {
      $HTML = file_get_contents($path . '/' . $filename);
      $replacedHTML = preg_replace_callback(self::PATTERN, function ($match) use ($data) {
        $key = $match[1];
        return isset($data[$key]) ? $data[$key] : '';
      }, $HTML);

      return $replacedHTML;
    }
  }
