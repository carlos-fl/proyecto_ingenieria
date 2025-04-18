<?php

require_once __DIR__ . '/../classes/Request.php';
require_once __DIR__ . '/../types/ErrorResponse.php';
require_once __DIR__ . '/../../config/database/Database.php';

class EnrollmentValidator
{
    public static function validateEligibility(mysqli $mysqli, int $studentId, int $majorId, string $processTypeAbbr): ?ErrorResponse
    {
        // Obtener proceso activo de tipo específico
        $stmt = $mysqli->prepare("CALL SP_GET_ACTIVE_PROCESS_BY_PAC_AND_TYPE(?)");
        $stmt->bind_param("s", $processTypeAbbr);
        $stmt->execute();
        $result = $stmt->get_result();
        $process = $result->fetch_assoc();
        $stmt->close();
        $mysqli->next_result();

        if (!$process) {
            return new ErrorResponse(403, "No hay un proceso activo de tipo $processTypeAbbr");
        }

        $processCode = $process["PROCESS_CODE"];
        $pacCode = $process["PAC_CODE"];

        // Validar que el estudiante pertenezca a la carrera
        $stmt = $mysqli->prepare("CALL SP_VALIDATE_STUDENT_BELONGS_TO_MAJOR(?, ?)");
        $stmt->bind_param("ii", $studentId, $majorId);
        $stmt->execute();
        $result = $stmt->get_result();
        $belongsToMajor = $result->fetch_assoc()["belongs"] ?? null;
        $stmt->close();
        $mysqli->next_result();

        if (!$belongsToMajor) {
            return new ErrorResponse(403, "El estudiante no pertenece a la carrera especificada");
        }

        // Validar condiciones del proceso (pago, índice, reglas)
        try {
            $stmt = $mysqli->prepare("CALL SP_VALIDATE_STUDENT_ENROLLMENT_ELIGIBILITY(?, ?)");
            $stmt->bind_param("is", $studentId, $processTypeAbbr);
            $stmt->execute();
            $stmt->close();
            $mysqli->next_result();
        } catch (Throwable $e) {
            return new ErrorResponse(403, $e->getMessage());
        }

        return null;
    }
}