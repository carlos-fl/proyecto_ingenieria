<?php

  class TeacherData {
    public string $firstName;
    public string $lastName;
    public int $employeeNumber;
    public string $email;
    public string $phone;
    public string $photo;
    public string $DNI;

    public function __construct(string $firstName, string $lastName, int $employeeNumber, string $email, string $phone, string $photo, string $DNI) {
      $this->firstName = $firstName;
      $this->lastName = $lastName;
      $this->employeeNumber = $employeeNumber;
      $this->email = $email;
      $this->phone = $phone;
      $this->photo = $photo;
      $this->DNI = $DNI;
    }

    public static function setPropertiesWithArray(array $data): self {
      return new self($data['FIRST_NAME'],
      $data['LAST_NAME'],
      $data['EMPLOYEE_NUMBER'],
      $data['INST_EMAIL'],
      $data['PHONE_NUMBER'],
      $data['PHOTO'],
      $data['DNI']);
    }
  }