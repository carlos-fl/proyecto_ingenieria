<?php

class StudentData {
    public int $studentId;
    public string $firstName;
    public string $lastName;
    public int $studentAccountNumber;
    public string $email;
    public string $phone;

    public function __construct(int $studentId, string $firstName, string $lastName, int $studentAccountNumber, string $email, string $phone) {
        $this->studentId = $studentId;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->studentAccountNumber = $studentAccountNumber;
        $this->email = $email;
        $this->phone = $phone;
    }

    public static function setPropertiesWithArray(array $data): self {
        return new self(
            $data['ID_STUDENT'],
            $data['FIRST_NAME'],
            $data['LAST_NAME'],
            $data['STUDENT_ACCOUNT_NUMBER'],
            $data['INST_EMAIL'],
            $data['PHONE_NUMBER']
        );
    }
}