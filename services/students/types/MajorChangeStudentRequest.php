<?php

require_once "StudentRequest.php";
require_once "StudentRequestType.php";

class MajorChangeStudentRequest implements StudentRequest{
    
    private int $majorId;
    private string $content;
    private const REQUEST_TYPE = StudentRequestType::MAJORCHANGE;

    public function __construct(int $majorId, string $content) {
        $this->majorId = $majorId;
        $this->content = $content;
    }

    public function getContent(): array{
        return ["MAJOR_ID" => $this->majorId, "DESCRIPTION" => $this->content];
    }

    public function getRequestType(): string{
        return $this::REQUEST_TYPE;
    }
}
