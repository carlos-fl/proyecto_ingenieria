<?php

session_start();
header('Content-Type: application/json');

include_once __DIR__ .'/../../../../services/auth/services/auth.service.php';
include_once __DIR__ . '/../../../../utils/classes/Logger.php';
include_once __DIR__ . '/../../../../utils/classes/Request.php';
include_once __DIR__ . '/../../../../services/students/services/Students.php';

header("Content-Type: application/json");

Request::isWrongRequestMethod('POST');  

$request = getJsonData();
$userAuthData = new LoginRequest($request->email, $request->password);

$userAuthServiceResponse = Auth::login($userAuthData);

if ($userAuthServiceResponse->status === 'failure') {
    http_response_code($userAuthServiceResponse->error->errorCode);
    echo json_encode($userAuthServiceResponse);
    return;
}

$sessionData = json_decode($userAuthServiceResponse->sessionData);
if (!in_array("STUDENTS", $sessionData->roles)) {
    http_response_code(403);
    echo json_encode([
        "status" => "failure",
        "data" => null,
        "error" => [
            "errorCode" => 403,
            "errorMessage" => "forbidden"
        ]
    ]);
    return;
}

$userId = $sessionData->user->USER_ID;
$idStudent = StudentService::getStudentId($userId);
$accountNumber = StudentService::getStudentAccountNumber($userId);
$profilePhoto = StudentService::getProfilePhoto($studentId);

http_response_code(200);
echo json_encode([
    "status" => "success",
    "data" => [],
    "sessionData" => [
        "user" => $sessionData->user,
        "roles" => $sessionData->roles,
        "studentId" => $studentId,
        "studentAccountNumber" => $studentAccountNumber,
        "profilePhoto" => $profilePhoto
    ],
    "error" => null
]);

$_SESSION['FIRST_NAME'] = $sessionData->user->FIRST_NAME; 
$_SESSION['LAST_NAME'] = $sessionData->user->LAST_NAME; 
$_SESSION['DNI'] = $sessionData->user->DNI; 
$_SESSION["USER_ID"] = $sessionData->user->USER_ID;
$_SESSION['INST_EMAIL'] = $sessionData->user->INST_EMAIL; 
$_SESSION['PERSONAL_EMAIL'] = $sessionData->user->PERSONAL_EMAIL; 
$_SESSION['ROLES'] = $sessionData->roles;
$_SESSION["ID_STUDENT"] = $idStudent;
$_SESSION["STUDENT_ACCOUNT_NUMBER"] = $studentAccountNumber;
$_SESSION["PROFILE_PHOTO"] = $profilePhoto;