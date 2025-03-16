<?php

  class AddVideoRequest {
    private int $sectionID;
    private string $URL;

    public function __construct(int $sectionID, string $URL) {
      $this->sectionID = $sectionID;
      $this->URL = $URL;
    }


    public function getSectionID() {
      return $this->sectionID;
    }

    public function getURL() {
      return $this->URL;
    }
  }