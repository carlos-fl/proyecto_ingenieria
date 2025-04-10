<?php

include_once __DIR__ . '/../../../../utils/classes/Request.php';
include_once __DIR__ . '/../../../../config/database/Database.php';
include_once __DIR__ . '/../../../../services/contentManagement/ContentManagement.php';

Request::isWrongRequestMethod('POST');
session_start();
header("Content-Type: application/json");

if (!isset($_FILES['file'], $_POST['title'], $_POST['author'], $_POST['idClass'])) {
    http_response_code(400);
    echo json_encode(["status" => "failure", "message" => "Missing required fields"]);
    return;
}

$roles = $_SESSION["ROLES"];

$hasTeacherAndCoordinator = in_array("TEACHERS", $roles) && in_array("COORDINATOR", $roles);
$hasTeacherAndDepartmentChair = in_array("TEACHERS", $roles) && in_array("DEPARTMENT_CHAIR", $roles);

if (!($hasTeacherAndCoordinator || $hasTeacherAndDepartmentChair)) {
    http_response_code(403);
    echo json_encode(["status" => "failure", "message" => "Access denied", "code" => 403]);
    return;
}

$title = $_POST['title'];
$author = $_POST['author'];
$idClass = (int) $_POST['idClass'];
$tags = isset($_POST['tags']) ? $_POST['tags'] : []; // array
$file = $_FILES['file'];
$teacherNumber = $_SESSION["TEACHER_NUMBER"];

$db = Database::getDatabaseInstace();
$mysqli = $db->getConnection();


$stmt = $mysqli->prepare("SELECT MAJOR_ID FROM TBL_TEACHERS WHERE TEACHER_NUMBER = ?");
$stmt->bind_param("i", $teacherNumber);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    $stmt->close();
    $mysqli->close();
    http_response_code(403);
    echo json_encode(["status" => "failure", "message" => "No se encontró una carrera asociada al docente"]);
    return;
}

$majorId = $result->fetch_assoc()['MAJOR_ID'];
$stmt->close();


$uploadUrl = ContentManagement::saveFile($file);

try {
    $bookId = null;
    $stmt = $mysqli->prepare("CALL SP_INSERT_BOOK(?, ?, ?, CURDATE(), ?, ?, @bookId)");
    $stmt->bind_param("sssii", $title, $author, $uploadUrl, $majorId, $idClass);
    $stmt->execute();
    $stmt->close();

    $res = $mysqli->query("SELECT @bookId AS bookId");
    $bookId = $res->fetch_assoc()['bookId'];

    foreach ($tags as $tagName) {
        $tagName = trim($tagName);
        if ($tagName === '') continue;

        $stmt = $mysqli->prepare("SELECT TAG_ID FROM TBL_TAGS WHERE TAG_NAME = ?");
        $stmt->bind_param("s", $tagName);
        $stmt->execute();
        $tagResult = $stmt->get_result();

        if ($tagResult->num_rows > 0) {
            $tagId = $tagResult->fetch_assoc()['TAG_ID'];
        } else {
            $insert = $mysqli->prepare("INSERT INTO TBL_TAGS (TAG_NAME) VALUES (?)");
            $insert->bind_param("s", $tagName);
            $insert->execute();
            $tagId = $insert->insert_id;
            $insert->close();
        }
        $stmt->close();

        $assoc = $mysqli->prepare("INSERT IGNORE INTO TBL_BOOKS_X_TAGS (BOOK_ID, TAG_ID) VALUES (?, ?)");
        $assoc->bind_param("ii", $bookId, $tagId);
        $assoc->execute();
        $assoc->close();
    }

    $mysqli->close();
    echo json_encode(["status" => "success", "bookId" => (int)$bookId]);

} catch (Throwable $err) {
    $mysqli->close();
    http_response_code(500);
    echo json_encode(["status" => "failure", "message" => "Error uploading book: " . $err->getMessage()]);
}