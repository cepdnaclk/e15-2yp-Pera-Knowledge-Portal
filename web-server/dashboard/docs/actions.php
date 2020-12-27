<?php

include_once '../data/session.php';
include_once '../data/database.php';
include_once '../data/classes/userClass.php';
include_once '../data/communication.php';

define("FOLDER_NAME", "docs");
include_once "../data/accessControl.php";

//define("MAX_FILE_SIZE", 52428800);
define("MAX_FILE_SIZE", 2097152);  // 2MB

$time = date("Y-m-d H:i:s");
ini_set("file_uploads", "On");
//ini_set("upload_max_filesize", "10M");

$db = new database();
$user = new userClass($db);
$com = new communication();

$act = $_GET['act'];

$docTypeArray = json_decode(file_get_contents("../lists/docTypes.json"), true);

if ($act == "new") {

   $docTitle = $_POST['docTitle'];
   $docType = $_POST['docType'];
   $docAuthor = $_POST['docAuthor'];
   $docAdviser = $_POST['docAdviser'];
   $docTags = $_POST['docTags'];
   //$docPDF = $_POST['docPDF'];
   $docDescription = $_POST['docDescription'];
   $docVisibility = $_POST['docVisibility'];

   $docId = $db->get_SiteData("nextDocId");

   if ($db->newDoc($docId, $docTitle, $docTypeArray[$docType]['id'], $userId, $time, "PENDING", $docTags, "", $docDescription, "", 0) == 1) {

      $db->set_SiteData("nextDocId", $docId + 1);

      // Clear the text
      $docAuthor = implode(",",array_map('trim',explode(",",$docAuthor)));
      $docAdviser = implode(",",array_map('trim',explode(",",$docAdviser)));

      // AUTHOR', 'ADVISER', 'EVENT
      $db->addRelation($docId, $docAuthor, "AUTHOR");
      $db->addRelation($docId, $docAdviser, "ADVISER");

      // Uploading the file
      if (isset($_FILES['docPDF'])) {
         if (uploadFile($docId, $_FILES['docPDF']) == 1) {

            echo "<script>alert('Document was submitted for review successfully');</script>";
            echo "<script>history.go(-2)</script>";

         } else {
            echo "<script>alert('Error occurred during upload.');</script>";
            echo "<script>history.go(-1)</script>";
         }
      } else {
         echo "<script>alert('No File !!!');</script>";
      }

   } else {
      echo "<script>alert('Sorry, an error occurred');</script>";
      echo "<script>history.go(-1)</script>";
   }

} else if ($act == "edit") {


   $docId = $_GET['id'];

   if (!(($db->getDocData($docId, "submitBy") == $userId) || ($_SESSION['role'] == 0) || ($_SESSION['role'] == 2))) {
      print "Unauthorized access";
      exit;
   }

   $docTitle = $_POST['docTitle'];
   $docType = $_POST['docType'];
   $docAuthor = $_POST['docAuthor'];
   $docAdviser = $_POST['docAdviser'];
   $docTags = $_POST['docTags'];
   $docDescription = str_replace("'", "\"", $_POST['docDescription']);
   $docVisibility = $_POST['docVisibility'];

   //print_r($_POST);

   $res = 0;
   $res += $db->setDocData($docId, "docTitle", $docTitle);
   $res += $db->setDocData($docId, "docType", $docTypeArray[$docType]['id']);
   $res += $db->setDocData($docId, "docTags", $docTags);
   $res += $db->setDocData($docId, "docNotes", $docDescription);
   $res += $db->setDocData($docId, "docVisibility", $docVisibility);

   $res += $db->updateRelation($docId, $docAuthor, "AUTHOR");
   $res += $db->updateRelation($docId, $docAdviser, "ADVISER");

   if ($res != 7) {
      echo "<script>alert('Sorry, an unknown error occurred ! ($res)');</script>";
      //echo "<script>history.go(-1);</script>";

   } else {
      $db->setDocData($docId, "docStatus", "PENDING");

      if ($_FILES['docPDF']['error'] == 4) {
         // No file upload
         echo "<script>alert('Update Success !!!);</script>";
         echo "<script>history.go(-2)</script>";

      } else {
         if (uploadFile($docId, $_FILES['docPDF']) == 1) {

            // Add to elastic search index

            $pdfContent = $com->insertDocument($docId);
            $db->setDocData($docId, "docText", $pdfContent);

            echo "<script>alert('Upload Success !!!);</script>";
            echo "<script>history.go(-1)</script>";


         } else {
            //print_r($_POST);
            print_r($_FILES);
            echo "<script>alert('Error occurred during upload.');</script>";
            echo "<script>history.go(-1)</script>";
         }
      }
   };

} else if ($act == "delete") {
   $docId = $_POST['docId'];

   if (!(($db->getDocData($docId, "submitBy") == $userId) || ($_SESSION['role'] == 0) || ($_SESSION['role'] == 2))) {
      print "Unauthorized access";
      exit;
   }

   if ($db->deleteDoc($docId)) {
      $com->deleteDocument($docId);

      unlink("../../uploads/$docId.pdf") or die("Couldn't delete file");
      echo "<script>history.go(-2)</script>";
      // exit;

   } else {
      echo "<script>alert('Sorry, an unknown error occurred !');</script>";
      echo "<script>history.go(-1);</script>";
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

         //print_r($_FILES);
         //print_r($errors);
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
