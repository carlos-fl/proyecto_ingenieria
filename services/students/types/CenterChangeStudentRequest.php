<?php

require_once "StudentRequest.php";
require_once "StudentRequestType.php";

class CenterChangeStudentRequest implements StudentRequest{
    
    private int $centerId;
    private string $content;
    private const REQUEST_TYPE = StudentRequestType::CAMPUSTRANSFER;

    public function __construct(int $centerId, string $content) {
        $this->centerId = $centerId;
        $this->content = $content;
    }

    public function getContent(): array{
        return ["CENTER_ID" => $this->centerId, "DESCRIPTION" => $this->content];
    }

    public function getRequestType(): string{
        return $this::REQUEST_TYPE;
    }
}
