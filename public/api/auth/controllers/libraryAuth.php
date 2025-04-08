<?php
session_start();
header('Content-Type: application/json');

include_once __DIR__ . '/../../../../services/auth/services/auth.service.php';
include_once __DIR__ . '/../../../../utils/classes/Logger.php';
include_once __DIR__ . '/../../../../utils/classes/Request.php';
include_once __DIR__ . '/../../../../services/students/services/Students.php';
include_once __DIR__ . '/../../../../services/teachers/services/Teachers.php';

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
$roles = $sessionData->roles;

$allowedRoles = ['STUDENTS', 'TEACHERS', 'COORDINATOR', 'DEPARTMENT_CHAIR'];
$hasAllowedRole = false;
foreach ($allowedRoles as $role) {
    if (in_array($role, $roles)) {
        $hasAllowedRole = true;
        break;
    }
}
if (!$hasAllowedRole) {
    http_response_code(403);
    echo json_encode([
        "status" => "failure",
        "data" => null,
        "error" => [
            "errorCode" => 403,
            "errorMessage" => "Acceso denegado"
        ]
    ]);
    return;
}

$userId = $sessionData->user->USER_ID;
$extraData = [];

if (in_array("STUDENTS", $roles)) {
    $idStudent = StudentService::getStudentId($userId);
    $studentAccountNumber = StudentService::getStudentAccountNumber($userId);
    $extraData = [
        "studentId" => $idStudent,
        "studentAccountNumber" => $studentAccountNumber
    ];
} else if (in_array("TEACHERS", $roles) || in_array("COORDINATOR", $roles) || in_array("DEPARTMENT_CHAIR", $roles)) {
    $teacherNumber = TeacherService::getTeacherNumber($userId);
    $extraData = [
        "teacherNumber" => $teacherNumber,
    ];
}

$_SESSION['FIRST_NAME']      = $sessionData->user->FIRST_NAME;
$_SESSION['LAST_NAME']       = $sessionData->user->LAST_NAME;
$_SESSION['DNI']             = $sessionData->user->DNI;
$_SESSION["USER_ID"]         = $sessionData->user->USER_ID;
$_SESSION['INST_EMAIL']      = $sessionData->user->INST_EMAIL;
$_SESSION['PERSONAL_EMAIL']  = $sessionData->user->PERSONAL_EMAIL;
$_SESSION['ROLES']           = $sessionData->roles;

if (in_array("STUDENTS", $roles)) {
    $_SESSION["ID_STUDENT"]             = $idStudent;
    $_SESSION["STUDENT_ACCOUNT_NUMBER"] = $studentAccountNumber;
} else if (in_array("TEACHERS", $roles) || in_array("COORDINATOR", $roles) || in_array("DEPARTMENT_CHAIR", $roles)) {
    $_SESSION["TEACHER_NUMBER"] = $teacherNumber;
}

$response = [
    "status" => "success",
    "data" => [],
    "sessionData" => array_merge([
        "user" => $sessionData->user,
        "roles" => $sessionData->roles
    ], $extraData),
    "error" => null
];

http_response_code(200);
echo json_encode($response);
