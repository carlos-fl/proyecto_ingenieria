<?php

require_once "StudentRequest.php";
require_once "StudentRequestType.php";

class MajorChangeStudentRequest implements StudentRequest{
    
    private int $majorId;
    private string $content;
    private string $url;
    private const REQUEST_TYPE = StudentRequestType::MAJORCHANGE;

    public function __construct(int $majorId, string $content, $backupUrl) {
        $this->majorId = $majorId;
        $this->content = $content;
        $this->url = $backupUrl;
    }

    public function getContent(): array{
        return ["MAJOR_ID" => $this->majorId, "DESCRIPTION" => $this->content, "URL" => $this->url];
    }

    public function getRequestType(): string{
        return $this::REQUEST_TYPE;
    }
}
