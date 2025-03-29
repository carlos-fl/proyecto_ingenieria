<?php

class StudentData {
    public string $fullName;
    public int $studentAccountNumber;
    public string $email;
    public string $phone;

    public function __construct(string $fullName, int $studentAccountNumber, string $email, string $phone) {
        $this->fullName = $fullName;
        $this->studentAccountNumber = $studentAccountNumber;
        $this->email = $email;
        $this->phone = $phone;
    }

    public static function setPropertiesWithArray(array $data): self {
        return new self(
            "{$data['FIRST_NAME']} {$data['LAST_NAME']}",
            $data['STUDENT_ACCOUNT_NUMBER'],
            $data['INST_EMAIL'],
            $data['PHONE_NUMBER']
        );
    }
}