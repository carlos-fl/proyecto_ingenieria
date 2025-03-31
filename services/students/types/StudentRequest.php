<?php

interface StudentRequest{
    // Interaz para las solicitudes de estudiantes

    public function getRequestType(): string;
    public function getContent(): array;
}
