<?php

session_start();
header('Content-Type: application/json');

include_once __DIR__ . '/../../../../services/auth/services/auth.service.php';
include_once __DIR__ . '/../../../../utils/classes/Logger.php';
include_once __DIR__ . '/../../../../utils/classes/Request.php';
include_once __DIR__ . '/../../../../services/students/services/Students.php';
include_once __DIR__ . '/../../../../utils/functions/jsonParse.php'; // Asegurate que esté esto

// Validar método HTTP
Request::isWrongRequestMethod('POST');

// Obtener y validar los datos del cuerpo de la petición
$request = getJsonData();

if (!$request || !isset($request->email) || !isset($request->password)) {
    http_response_code(422);
    echo json_encode([
        "status" => "failure",
        "data" => null,
        "error" => [
            "errorCode" => 422,
            "errorMessage" => "Missing email or password in request body"
        ]
    ]);
    return;
}

// Crear la solicitud de autenticación
$userAuthData = new LoginRequest($request->email, $request->password);
$userAuthServiceResponse = Auth::login($userAuthData);

// Validar si la autenticación falló
if ($userAuthServiceResponse->status === 'failure') {
    http_response_code($userAuthServiceResponse->error->errorCode);
    echo json_encode($userAuthServiceResponse);
    return;
}

// Validar roles del usuario
$sessionData = json_decode($userAuthServiceResponse->sessionData);
if (!in_array("STUDENTS", $sessionData->roles)) {
    http_response_code(403);
    echo json_encode([
        "status" => "failure",
        "data" => null,
        "error" => [
            "errorCode" => 403,
            "errorMessage" => "Forbidden"
        ]
    ]);
    return;
}

// Obtener datos del estudiante
$userId = $sessionData->user->USER_ID;
$idStudent = StudentService::getStudentId($userId);
$studentAccountNumber = StudentService::getStudentAccountNumber($userId);
//$profilePhoto = StudentService::getProfilePhoto($userId);

// Enviar respuesta exitosa
http_response_code(200);
echo json_encode([
    "status" => "success",
    "data" => [],
    "sessionData" => [
        "user" => $sessionData->user,
        "roles" => $sessionData->roles,
        "studentId" => $idStudent,
        "studentAccountNumber" => $studentAccountNumber
        //"profilePhoto" => $profilePhoto
    ],
    "error" => null
]);

// Guardar datos en la sesión
$_SESSION['FIRST_NAME'] = $sessionData->user->FIRST_NAME; 
$_SESSION['LAST_NAME'] = $sessionData->user->LAST_NAME; 
$_SESSION['DNI'] = $sessionData->user->DNI; 
$_SESSION["USER_ID"] = $sessionData->user->USER_ID;
$_SESSION['INST_EMAIL'] = $sessionData->user->INST_EMAIL; 
$_SESSION['PERSONAL_EMAIL'] = $sessionData->user->PERSONAL_EMAIL; 
$_SESSION['ROLES'] = $sessionData->roles;
$_SESSION["ID_STUDENT"] = $idStudent;
$_SESSION["STUDENT_ACCOUNT_NUMBER"] = $studentAccountNumber;
//$_SESSION["PROFILE_PHOTO"] = $profilePhoto;
