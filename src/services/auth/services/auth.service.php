<?php

  include_once __DIR__ . '/../../../../config/database/Database.php';
  include_once __DIR__ . '/../../../../config/env/Environment.php';
  include_once __DIR__ . '/../../../../utils/classes/Response.php';
  include_once __DIR__ . "/../types/loginRequest.php";
  include_once __DIR__ . "/../../../../utils/classes/EmailValidator.php";
  include_once __DIR__ . "/../../../../utils/classes/PasswordValidator.php";

  $env = Environment::getVariables();
  $db = Database::getDatabaseInstace();

  $mysqli = $db->getConnection();

  class Auth {
    /**
     * @param LoginRequest 
     * object that contains email and password
     * @return PostResponse
     */
    public static function loginAdministrator(LoginRequest $request): PostResponse {
      $mysqli = $GLOBALS['mysqli'];
      $db = $GLOBALS['db'];
      $env = $GLOBALS['env'];

      $email = $request->email;
      $password = $request->password;
      if (!EmailValidator::validateInstitutionalEmail($email)) {
        return Response::returnPostResponse(401, 'failure', 'Incorrect email or password', true);
      }


      $query = "CALL SP_GET_USER(?)";
      $user = $db->callStoredProcedure($query, "s", [$email], $mysqli); 
      /**
       * user not found
       */
      if ($user->num_rows == 0) {
        return Response::returnPostResponse(401, 'failure', 'Incorrect email or password', true);
      }
      // if users exists check if password match
      $hashedPassword = hash($env['HASH_ALGORITHM'], $password);
      /**
       * returns data instead of mysqli_result object
       */
      $userData = $user->fetch_assoc();
      if (strcmp($userData['PASSWORD'], $hashedPassword) != 0) {
        return Response::returnPostResponse(401, 'failure', 'Incorrect email or password', true);
      }
      // if password match check if it is a admin[role] ROLE: ADMIN
      $query = "CALL SP_GET_ROLES_BY_USER(?)";
      $roles = $db->callStoredProcedure($query, "i", [$userData["USER_ID"]], $mysqli);

      if ($roles->num_rows == 0) {
        return Response::returnPostResponse(403, 'failure', 'Access denied', true);
      }

      $ROLE = "ADMIN";
      if (!in_array($ROLE, $roles->fetch_assoc())) {
        return Response::returnPostResponse(403, 'failure', 'Access denied', true);
      }

      $_SESSION['USER_ID'] = $userData['USER_ID'];
      $_SESSION['INST_EMAIL'] = $userData['INST_EMAIL'];
      $_SESSION['FIRST_NAME'] = $userData['FIRST_NAME'];
      $_SESSION['LAST_NAME'] = $userData['LAST_NAME'];
      return Response::returnPostResponse(status: "success", isBadResponse: false);
    } 
  }
