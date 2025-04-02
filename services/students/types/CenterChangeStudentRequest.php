<?php

require_once "StudentRequest.php";
require_once "StudentRequestType.php";

class CenterChangeStudentRequest implements StudentRequest{
    
    private int $centerId;
    private string $content;
    private string $url;
    private const REQUEST_TYPE = StudentRequestType::CAMPUSTRANSFER;

    public function __construct(int $centerId, string $content, $backupUrl) {
        $this->centerId = $centerId;
        $this->content = $content;
        $this->url = $backupUrl;
    }

    public function getContent(): array{
        return ["CENTER_ID" => $this->centerId, "DESCRIPTION" => $this->content, "URL" => $this->url];
    }

    public function getRequestType(): string{
        return $this::REQUEST_TYPE;
    }
}
