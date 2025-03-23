<?php

  class ApplicantFormData {
    public int $applicationID;
    public string $applicantCode;
    public int $applicantID;
    public string $firstName;
    public string $lastName;
    public string $dni;
    public int $identificationType;
    public string $phoneNumber;
    public string $email;
    public string $gender;
    public int $majorCode;
    public int $secondMajorCode;
    public string $comment;
    public int $reviewerID;
    public DateTime $createdAt;


    public function __construct(
      int $applicationID,
      string $applicantCode,
      int $applicantID,
      string $firstName,
      string $lastName,
      string $dni,
      int $identificationType,
      string $phoneNumber,
      string $email,
      string $gender,
      int $majorCode,
      int $secondMajorCode,
      string $comment,
      int $reviewerID,
      DateTime $createdAt
  ) {
      $this->applicationID = $applicationID;
      $this->applicantCode = $applicantCode;
      $this->applicantID = $applicantID;
      $this->firstName = $firstName;
      $this->lastName = $lastName;
      $this->dni = $dni;
      $this->identificationType = $identificationType;
      $this->phoneNumber = $phoneNumber;
      $this->email = $email;
      $this->gender = $gender;
      $this->majorCode = $majorCode;
      $this->secondMajorCode = $secondMajorCode;
      $this->comment = $comment;
      $this->reviewerID = $reviewerID;
      $this->createdAt = $createdAt;
    }
  }
