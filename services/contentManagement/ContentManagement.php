<?php

  define("IMAGE_PATH",  __DIR__ . "/../../uploads/images/");
  define("FILE_PATH", __DIR__ . "/../../uploads/files/");
  define("ALLOWED_IMAGE_TYPES", ["image/png", "image/jpg", "image/jpeg"]);
  define("ALLOWED_FILE_TYPES", ["application/pdf", "text/csv"]);
  define("MAX_IMAGE_SIZE", 5 * (2 ** 20)); // 5MB in bytes
  define("MAX_FILE_SIZE", 20 * (2**20)); // 20MB in bytes

  class ContentManagement {
    /**
     * @param array $image
     * this is the associative array given in $_FILE["image"] if frontend send the image with name=image
     * if not change "image" for the key sent from the frontend
     * @return string
     * this is the path to save in the database
     */
    public static function savePhoto(array $image): string {
      if ($image['error'] > 0) {
        throw new Error('image contains errors');
      }

      if (!in_array(mime_content_type($image["tmp_name"]), ALLOWED_IMAGE_TYPES)) {
        throw new Error("Not allowed image type");
      }

      if ($image["size"] > MAX_IMAGE_SIZE) {
        throw new Error("Image too big");
      }

      
      $filename = self::generateFileName($image['name'], true); // destiny directory + generated filename
      // check if directory exists
      $parentDirectory = dirname($filename);
      if (!is_dir($parentDirectory)) {
        mkdir($parentDirectory, 0777, true);
      }

      move_uploaded_file($image['tmp_name'], $filename);
      return $filename;
    }

    /**
     * @param array $file
     * this is the associative array given in $_FILE["file"] if file is the name given in the frontend
     * if not change "file" for the key sent from the frontend
     * @return string
     * this is the path to save in the database
     */
    public static function saveFile(array $file): string {
      if ($file['error'] > 0) {
        throw new Error("Errors found in file");
      }

      if (!in_array(mime_content_type($file["tmp_name"]), ALLOWED_FILE_TYPES)) {
        throw new Error("Not allowed file type");
      }

      if ($file["size"] > MAX_FILE_SIZE) {
        throw new Error("File too big");
      }

      $filename = self::generateFileName($file["name"], false);
      // check if directory exists
      $parentDirectory = dirname($filename);
      if (!is_dir($parentDirectory)) {
        mkdir($parentDirectory, 0777, true);
      }

      move_uploaded_file($file["tmp_name"], $filename);
      return $filename;
    }

    /**
     * @param string $filepath
     * this is the path saved in the database
     * @return string
     * returns the binary data to send to the frontend
     */
    public static function getFileData(string $filepath): string {
      if (!file_exists($filepath)) {
        throw new Error("Not file found");
      }

      $fileData = @file_get_contents($filepath);
      return $fileData;
    }

    private static function generateFileName(string $filename, bool $isImage): string {
      $file = explode('.', $filename, 2);
      do {
        $generatedName = $file[0] . "-" . uniqid() . $file[1];
        $destiny = $isImage ? IMAGE_PATH . $generatedName : FILE_PATH . $generatedName;
      } while(file_exists($destiny)); 
      return $destiny;
    }
  }