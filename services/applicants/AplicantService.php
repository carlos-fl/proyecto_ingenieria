<?php

  include_once __DIR__ . '/types/applicantResponse.php';
  include_once __DIR__ . '/../../config/database/Database.php';
  include_once __DIR__ . '/../../utils/classes/Regex.php';
  include_once __DIR__ . '/../../utils/types/postResponse.php';
  include_once __DIR__ . '/../../utils/classes/EmailValidator.php';
  include_once __DIR__ . '/../../services/emailNotifications/EmailService.php';
  include_once __DIR__ . '/types/EmailStatus.php';

  class ApplicantService {
    public static function getApplicantSubmittedForm(string $token): ApplicantResponse {

      if (!Regex::isValidToken($token)) {
        return new ApplicantResponse("failure", error: new ErrorResponse(400, "Invalid Token"));
      }

      $db = Database::getDatabaseInstace();
      $mysqli = $db->getConnection();

      $query = "CALL SP_GET_APPLICATION_BY_TOKEN(?)";
      try {
        $application = $db->callStoredProcedure($query, "s", [$token], $mysqli);
        if ($application->num_rows == 0) {
          return new ApplicantResponse("failure", error: new ErrorResponse(404, "Not Data Found"));
        }

        $application = $application->fetch_assoc();
        $mysqli->close();
        return new ApplicantResponse("success", $application);
      } catch (Throwable $err) {
        return new ApplicantResponse("failure", error: new ErrorResponse(500, $err->getMessage()));
      }
    }


    public static function generateResubmissionToken(): string {
      return bin2hex(random_bytes(32)); // random_bytes generate a 32 random byte which is 256 bits. 64 characters = 256 bits
    }


    private static function resubmission(string $email, string $token): EmailStatus {

      if (!EmailValidator::validate($email)) {
        return new EmailStatus(false, new ErrorResponse(400, 'Incorrect email'));
      }

      $db = Database::getDatabaseInstace();
      $mysqli = $db->getConnection();

      $query = 'CALL SP_SAVE_RESUBMISSION(?,?)';
      try {
        $result = $db->callStoredProcedure($query, "ss", [$email, $token], $mysqli);
        $result = $result->fetch_assoc();

        $mysqli->close();
        if ($result) {
          return new EmailStatus(true);
        }

        return new EmailStatus(false, new ErrorResponse(500, 'Could not save resubmission'));

      } catch(Throwable $err) {
        return new EmailStatus(false, new ErrorResponse(500, 'Server error ' . $err->getMessage()));
      }
    }


    /**
     * @param string $email
     * @param array $data
     * this is an associative array to parse the html
     */
    public static function sendResubmissionEmail(string $email, string $token, array $data): EmailStatus {
      $emailResubmission = self::resubmission($email, $token);
      if ($emailResubmission->success) {
        EmailService::sendEmail($email, "Corregir Datos de Solicitud", $data, "applicationReject.html");
        return $emailResubmission;
      }
      return $emailResubmission;
    }
  }