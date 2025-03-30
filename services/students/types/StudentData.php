<?php

class StudentData {
    public int $studentId;
    public int $studentAccountNumber;
    public string $firstName;
    public string $lastName;
    public string $email;
    public string $phone;
    public ?string $profilePhoto;

    public function __construct(int $studentId, int $studentAccountNumber, string $firstName, string $lastName, string $email, string $phone, ?string $profilePhoto) {
        $this->studentId = $studentId;
        $this->studentAccountNumber = $studentAccountNumber;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->email = $email;
        $this->phone = $phone;
        $this->profilePhoto = $profilePhoto;
    }

    public static function setPropertiesWithArray(array $data): self {
        $photos = isset($data['photos']) ? json_decode($data['photos'], true) : [];
        
        $profilePhoto = null;
        if (is_array($photos)) {
            foreach ($photos as $photo) {
                if ($photo['is_current'] ?? false) {
                    $profilePhoto = $photo['url'];
                    break;
                }
            }
        }

        return new self(
            $data['studentId'],
            $data['studentAccountNumber'],
            $data['firstName'],
            $data['lastName'],
            $data['institutionalEmail'],
            $data['phoneNumber'],
            $profilePhoto
        );
    }
}