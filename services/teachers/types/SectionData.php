<?php

  class SectionData {
    public string $className;
    public string $classCode;
    public string $sectionCode;
    public array $students;
    public array $daysOfWeek;
    public int $sectionID;


    public function __construct(string $className, string $classCode, string $sectionCode, array $students, array $daysOfWeek, int $sectionID) {
      $this->className = $className;
      $this->classCode = $classCode;
      $this->sectionCode = $sectionCode;
      $this->students = $students;
      $this->daysOfWeek = $daysOfWeek;
      $this->sectionID = $sectionID;
    }
  }