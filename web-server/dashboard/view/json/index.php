<?php

header("Access-Control-Allow-Origin: *");
header('Content-Type: text/json');

error_reporting(E_ERROR);
ini_set('display_errors', 1);
date_default_timezone_set('Asia/Colombo');


include_once "../../data/database.php";
$db = new database();

$docId = $_GET['id'];

if ($db->existsDocId($docId)) {

    $data = $db->getDoc($docId);

    $title = $data["docTitle"];
    $submitBy = $data['submitBy'];
    $submitTime = $data['submitTime'];
    $status = $data['docStatus'];
    $docType = $data['docType'];
    $docTags = $data['docTags'];
    $docVisibility = $data['docVisibility'];
    $docNotes = $data['docNotes'];

    $docAuthors = $db->getRelation($docId, "AUTHOR");
    $docAdvisers = $db->getRelation($docId, "ADVISER");

    $arrayAuthors = array_map('trim', explode(",", $docAuthors));
    $arrayAdvisers = array_map('trim', explode(",", $docAdvisers));

    $docPath = $db->get_SiteData("filePath") . $docId . ".pdf";

    $row = array(
        "documentId" => $docId,
        "title" => $title,
        "documentType" => $docType,
        "userId" => $submitBy,
        "status" => $status,
        "authors" => $arrayAuthors,
        "advisers" => $arrayAdvisers,
        "tags" => $docTags,
        "visibility" => "visible",
        "content" => "",
        "submittedTime" => $submitTime
    );

    echo json_encode($row, JSON_PRETTY_PRINT);

} else {
    echo "";
}