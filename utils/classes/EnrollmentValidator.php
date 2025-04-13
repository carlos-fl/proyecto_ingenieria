<?php

require_once __DIR__ . '/../classes/Request.php';
require_once __DIR__ . '/../types/ErrorResponse.php';
require_once __DIR__ . '/../../config/database/Database.php';

class EnrollmentValidator
{
    public static function validateEligibility(mysqli $mysqli, int $studentId, int $majorId, int $pacCode, string $processTypeAbbr): ?ErrorResponse
    {
        //Obtener proceso activo por PAC y tipo
        $stmt = $mysqli->prepare("CALL SP_GET_ACTIVE_PROCESS_BY_PAC_AND_TYPE(?, ?)");
        $stmt->bind_param("is", $pacCode, $processTypeAbbr);
        $stmt->execute();
        $result = $stmt->get_result();
        $process = $result->fetch_assoc();
        $stmt->close();
        $mysqli->next_result();

        if (!$process) {
            return new ErrorResponse(403, "No hay un proceso activo de tipo $processTypeAbbr para este PAC");
        }

        $processCode = $process["PROCESS_CODE"];

        //Validar si el estudiante pertenece a la carrera
        $stmt = $mysqli->prepare("CALL SP_VALIDATE_STUDENT_BELONGS_TO_MAJOR(?, ?)");
        $stmt->bind_param("ii", $studentId, $majorId);
        $stmt->execute();
        $result = $stmt->get_result();
        $belongsToMajor = $result->fetch_assoc()["belongs"];
        $stmt->close();
        $mysqli->next_result();

        if (!$belongsToMajor) {
            return new ErrorResponse(403, "El estudiante no pertenece a la carrera especificada");
        }

        // Ejecutar la validación completa contra las reglas del proceso (índice, día, pago, etc. menos las primeras validaciones que se hicieron arriba)
        try {
            $stmt = $mysqli->prepare("CALL SP_VALIDATE_STUDENT_ENROLLMENT_ELIGIBILITY(?, ?)");
            $stmt->bind_param("ii", $studentId, $processCode);
            $stmt->execute();
            $stmt->close();
            $mysqli->next_result();
        } catch (Throwable $e) {
            return new ErrorResponse(403, $e->getMessage());
        }

        return null; // Elegible
    }
}