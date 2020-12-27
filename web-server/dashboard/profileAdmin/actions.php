<?php

include_once '../data/session.php';
include_once '../data/database.php';
include_once '../data/classes/profileClass.php';
include_once '../data/classes/userClass.php';
//include_once '../data/communication.php';

define("FOLDER_NAME", "profileAdmin");
include_once "../data/accessControl.php";

$time = date("Y-m-d H:i:s");
ini_set("file_uploads", "On");
//ini_set("upload_max_filesize", "10M");

$db = new database();
$profile = new profileClass($db);
$users = new userClass($db);

$act = $_GET['act'];
echo $act;

if ($act == "approve") {

   $profileId = $_GET['profileId'];
   //echo $profileId;

   if($profile->existsActiveProfile == false){
      // Create a new profile record
      $profile->new_activeProfile($profileId, $time);
   }
   $pd = $profile->get_profile($profileId);

   //print_r($pd);
   $resp = $profile->update_activeProfileData($profileId, "department",  $pd['department']);
   $resp +=$profile->update_activeProfileData($profileId, "pos",  $pd['pos']);
   $resp +=$profile->update_activeProfileData($profileId, "skills",  $pd['skills']);
   $resp +=$profile->update_activeProfileData($profileId, "description",  $pd['description']);
   $resp +=$profile->update_activeProfileData($profileId, "linkedinProfile",  $pd['linkedinProfile']);
   $resp +=$profile->update_activeProfileData($profileId, "githubProfile",  $pd['githubProfile']);
   $resp +=$profile->update_activeProfileData($profileId, "referee",  $pd['referee']);
   $resp +=$profile->update_activeProfileData($profileId, "lastUpdate",  $time);

   //print_r($pd);
   //echo $resp;

   if($resp == 8){
      // Success
      $profile->update_profileData($profileId, "status", "APPROVED");
      $profUrl = "<a target='_blank' href='http://localhost/CO227-Project/web/public/profile/".$profileId."'>http://localhost/CO227-Project/web/public/profile/".$profileId."</a>";
      $to = $users->getUserData($profileId, "email");
      $body =  $text = file_get_contents("../data/emails/profApproved.html");

      $body = str_replace("{author}",$db->getName_byUserId($profileId), $body);
      $body = str_replace("{url}", $profUrl, $body);

      $subject = "Approval for the Profile Page";
      $from = "uop@ceykod.com";
      $replyto = $users->getUserData($pd['referee'], "email");

      //echo $body;
      //$res = sendMail($from, $to, $cc,"", $subject, $replyto, $body, 0);
      //echo $res;

      echo "<script>alert('Document was submitted for review successfully');</script>";
      echo "<script>window.location.href='index.php'</script>";
   }else{
      // Database Error
      echo "<script>alert('Database error occurred.');</script>";
   }

} else if ($act == "reject") {

   $profileId = $_GET['profileId'];
   $pd = $profile->get_profile($profileId);
   //echo $profileId;

   $to = $users->getUserData($profileId, "email");
   $body =  $text = file_get_contents("../data/emails/profReject.html");

   $body = str_replace("{author}",$db->getName_byUserId($profileId), $body);
   $body = str_replace("{adviser}",$db->getName_byUserId($pd['referee']), $body);
   $body = str_replace("{notes}", "Your Note:<i>".$pd['approvalNotes']."</i>", $body);
   //$body = str_replace("{notes2}", "Your Note:<i>".$pd['approvalNotes']."</i>", $body);

   $subject = "Rejection of the Profile Page";
   $from = "uop@ceykod.com";
   $replyto = $users->getUserData($pd['referee'], "email");

   //echo $body;
   $res = sendMail($from, $to, $cc,"", $subject, $replyto, $body, 0);
   //echo $res;
   $profile->update_profileData($profileId, "status", "REJECTED");
   echo "<script>window.location.href='index.php'</script>";
}

function redirect($url)
{
   header("Location: " . $url);

}

function sendMail($from, $to, $cc, $bcc, $subject, $replyTo, $body, $template)
{
   $server = "http://api.ceykod.com/email/v2/send2/?token=1d5dg478fhh953";
   //$server = "http://localhost/company/www/api/email/v2/send/?token=1d5dg478fhh953";

   $emailData = array(
      "from" => $from,
      "to" => $to,
      "cc" => $cc,
      "bcc" => $bcc,
      "subject" => $subject,
      "reply-to" => $replyTo,
      "body" => $body,
      "template" => $template
   );


   $emailData = json_encode($emailData);

   $ch = curl_init($server);
   curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
   curl_setopt($ch, CURLOPT_POST, 1);
   curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
   curl_setopt($ch, CURLOPT_POSTFIELDS, $emailData);
   curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
   $res = curl_exec($ch);

   return $res;
   /*$res =  json_decode($res,true);
   return $res['statusCode'];*/
}
