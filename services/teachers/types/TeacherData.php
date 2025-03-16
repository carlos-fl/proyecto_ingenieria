<?php

  class TeacherData {
    private string $firstName;
    private string $lastName;
    private int $employeeNumber;
    private string $email;
    private string $phone;
    private string $photo;

    public function __construct(string $firstName, string $lastName, int $employeeNumber, string $email, string $phone, string $photo) {
      $this->firstName = $firstName;
      $this->lastName = $lastName;
      $this->employeeNumber = $employeeNumber;
      $this->email = $email;
      $this->phone = $phone;
      $this->photo = $photo;
    }
  }