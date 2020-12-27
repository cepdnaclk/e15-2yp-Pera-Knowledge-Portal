<?php

include_once '../data/session.php';
include_once '../data/database.php';
include_once '../data/communication.php';

define("MAX_FILE_SIZE", 2097152);  // 2MB
define("FOLDER_NAME", "docsAdmin");
include_once "../data/accessControl.php";

ini_set("file_uploads", "On");
//ini_set("upload_max_filesize", "10M");

$time = date("Y-m-d H:i:s");
$db = new database();
$act = $_GET['act'];

if ($act == "approve" || $act == "reject" || $act == "pending") {
   $docId = $_GET['id'];
   if ($db->existsDocId($docId)) {
      $com = new communication();
      if ($act == "reject") {
         $db->setDocData($docId, "docStatus", "REJECTED");

         // Remove from the index
         $com->deleteDocument($docId);

      } else if ($act == "approve") {
         $db->setDocData($docId, "docStatus", "APPROVED");

         // Add to the index
         $com->insertDocument($docId);

      } else if ($act == "pending") {
         $db->setDocData($docId, "docStatus", "PENDING");

         // Remove from the index
         $com->deleteDocument($docId);
      }
      // Updating elastic search contents
      echo "<script>history.go(-2);</script>";
   }
}

function uploadFile($docId, $pdfFile)
{
   if (isset($_FILES['docPDF'])) {

      $errors = array();
      $file_name = $docId . ".pdf";
      $file_size = $pdfFile['size'];
      $file_tmp = $pdfFile['tmp_name'];
      //$file_type = $pdfFile['type'];
      $file_ext = strtolower(end(explode('.', $pdfFile['name'])));
      $fileTarget = "../../uploads/" . $file_name;

      $extensions = array("pdf"); // allowed extensions

      if (in_array($file_ext, $extensions) === false) {
         $errors[] = "Extension not allowed, please choose a PDF file.";
      }

      if ($file_size > MAX_FILE_SIZE) {
         $errors[] = 'File size must be less than 50 MB';
      }

      if (empty($errors) == true) {
         if (file_exists($fileTarget)) {
            chmod($fileTarget, 0755); //Change the file permissions if allowed
            unlink($fileTarget); //remove the file
         }

         move_uploaded_file($file_tmp, $fileTarget);
         return 1;

      } else {
         print_r($errors);
         return 0;
      }
   }
}

function redirect($url)
{
   header("Location: " . $url);

}
