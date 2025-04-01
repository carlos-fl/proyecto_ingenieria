<?php

require_once "StudentRequest.php";
require_once "StudentRequestType.php";

class ClassCancelStudentRequest implements StudentRequest{
    
    private string $content;
    private const REQUEST_TYPE = StudentRequestType::CANCELLATION;

    public function __construct(string $content) {
        $this->content = $content;
    }

    public function getContent(): array{
        return ["URL" => $this->content];
    }

    public function getRequestType(): string{
        return $this::REQUEST_TYPE;
    }
}
