<?php

  include_once __DIR__ . '/../../../config/database/Database.php';
  include_once __DIR__ . '/../../../config/env/Environment.php';
  include_once __DIR__ . '/../../../utils/classes/Response.php';
  include_once __DIR__ . '/../types/loginRequest.php';
  include_once __DIR__ . '/../types/logApplicant.php';
  include_once __DIR__ . '/../../../utils/classes/EmailValidator.php';
  include_once __DIR__ . '/../../../utils/classes/PasswordValidator.php';


  $env = Environment::getVariables();
  $db = Database::getDatabaseInstace();


  class Auth {
    /**
     * @param LoginRequest 
     * object that contains email and password
     * @return PostResponse
     */
    public static function login(LoginRequest $request): PostResponse {
      $db = $GLOBALS['db'];
      $env = $GLOBALS['env'];
      $mysqli = $db->getConnection();
      
      $email = $request->getEmail();
      $password = $request->getPassword();
      if (!EmailValidator::validateInstitutionalEmail($email)) {
        return Response::returnPostResponse(true, "failure", 401, "Incorrect email or password");
      }


      $query = "CALL SP_GET_USER_BY_INST_EMAIL(?)";
      $user = $db->callStoredProcedure($query, "s", [$email], $mysqli); 
      /**
       * user not found
       */
      if ($user->num_rows == 0) {
        return Response::returnPostResponse(true, 'failure', 401, 'Incorrect email or password');
      }
      // if users exists check if password match
      $hashedPassword = hash($env['HASH_ALGORITHM'], $password);
      /**
       * returns data instead of mysqli_result object
       */
      $userData = $user->fetch_assoc();
      if (strcmp($userData['PASSWORD'], $hashedPassword) != 0) {
        return Response::returnPostResponse(true, 'failure', 401, 'Incorrect email or password');
      }
      // if password match check if it is a admin[role] ROLE: ADMIN
      $query = "CALL SP_GET_ROLES_BY_USER(?)";
      $roles = $db->callStoredProcedure($query, "s", [$userData["DNI"]], $mysqli);

      if ($roles->num_rows == 0) {
        return Response::returnPostResponse(true, 'failure', 403, 'Access denied');
      }

      /**
       * close connection to database
       */
      $mysqli->close();

      $data = json_encode([
        "user" => $userData,
        "roles" => array_map(fn($el) => $el[0], $roles->fetch_all())
      ]);

      return Response::returnPostResponse(status: "success", isBadResponse: false, data: [], sessionData: $data);
    } 


    /**
     * @param LogApplicant $request
     * object containing applicantCode and email
     * object containing status(string), data(array) and error(ErrorResponse)
     */
    public static function logApplicant(LogApplicant $request): PostResponse {

      $db = $GLOBALS['db'];

      if (!EmailValidator::validate($request->getApplicantEmail())) {
        return Response::returnPostResponse(true, 'failure', 401, 'Incorrect email or password');
      }
      // check if applicant exists
      $query = "CALL SP_GET_APPLICANT_BY_EMAIL(?)";
      $mysqli = $db->getConnection();
      $applicant = $db->callStoredProcedure($query, 's', [$request->getApplicantEmail()], $mysqli);
      if ($applicant->num_rows == 0) {
        return Response::returnPostResponse(true, 'failure', 401, 'Incorrect email or application code');
      }

      // check match with applicantCode
      $applicationData = $applicant->fetch_assoc();
      if ($applicationData['APPLICATION_CODE'] !== $request->getApplicantCode()) {
        return Response::returnPostResponse(true, 'failure', 401, 'Incorrect email or application code');
      } 

      // take results data
      $applicantResultsQuery = "CALL SP_GET_APPLICANT_CALIFICATIONS(?)";
      $applicantExamsResults = $db->callStoredProcedure($applicantResultsQuery, 'i', [$applicationData['APPLICATION_CODE']], $mysqli);
      if ($applicantExamsResults->num_rows == 0) {
        return Response::returnPostResponse(false, status: 'success', data: []);
      }

      $data = $applicantExamsResults->fetch_all(1);

      $sessionData = json_encode([
        "user" => $applicant->fetch_assoc(),
        "roles" => []
      ]);

      $mysqli->close();
      return Response::returnPostResponse(false, 'success', 200, data: $data, sessionData: $sessionData);
    } 
  }
