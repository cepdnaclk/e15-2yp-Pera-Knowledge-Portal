<?php

ini_set('error_log', 'log/sms-app-error.log');
error_reporting(E_ERROR);
ini_set('display_errors', 1);
date_default_timezone_set("Asia/Colombo");

session_start();
define("MAX_LOGIN_ATTEMPTS", 5);

include_once '../data/database.php';
include_once '../data/classes/userClass.php';


include_once 'files/functions.php';
include_once 'files/gAuthSettings.php';


if (!isset($_SERVER['HTTPS']) && $_SERVER['HTTP_HOST'] != "localhost") {
   // https redirect
   // header("location: https://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
}

$login = (isset($_SESSION['userAccess']) && $_SESSION['userAccess'] == $_SESSION[GetLoginSessionVar()]) ? 1 : 0;

if (isset($_GET['page'])) {
   $page = $_GET['page'];

   if ($page == "logout") {
      logOut();

   } else if ($page == "submit") {
      if ((isset($_POST['submitted'])) && loginToSite()) { //|| $login == 1
         echo "Redirecting...";
         redirectTo("../home/");
      } else {
         // error
         include_once 'files/loginWindow.php';
      }

   } else if ($page == "gLogin") {

      header('location: https://accounts.google.com/o/oauth2/auth?scope=' .
      urlencode('https://www.googleapis.com/auth/userinfo.profile https://www.googleapis.com/auth/userinfo.email') .
      '&redirect_uri=' . urlencode(CLIENT_REDIRECT_URL) . '&response_type=code&client_id=' . CLIENT_ID . '&access_type=online ');
      exit;

   } else if ($page == "gAuth.php") {

      require_once('files/gAuthSettings.php');
      require_once('files/google-login-api.php');

      if (isset($_GET['code'])) {
         try {
            $gapi = new GoogleLoginApi();
            $data = $gapi->GetAccessToken(CLIENT_ID, CLIENT_REDIRECT_URL, CLIENT_SECRET, $_GET['code']);
            $user_info = $gapi->GetUserProfileInfo($data['access_token']);
            $resp = loginWithGoogle($user_info);

            if ($resp==true){
               // redirect
               //print_r($_SESSION);
               redirectTo("../home/welcome.php");

            }else {
               echo GetErrorMessage();
            }

         } catch (Exception $e) {
            echo $e->getMessage();
            exit();
         }
      }

      //include_once 'gAuth.php';

   } else if ($login) {
      redirectTo("../home/");

   } else {
      include_once 'files/loginWindow.php';
   }
}else if ($login) {
   redirectTo("../home/");

} else {
   include_once 'files/loginWindow.php';
}

/*
if (isset($_GET['page'])) {
$page = $_GET['page'];

if ($page == "logout") {
logOut();

} elseif ($page == "submit") {

if ((isset($_POST['submitted'])) && loginToSite()) { //|| $login == 1

echo "Redirecting...";
print_r($_SESSION);

$userId = $_SESSION['userId'];


$userStatus = $_SESSION['userStatus'];

if ($userStatus == "CREATED" || $userStatus == "PENDING") {
// need to confirm email
redirectTo("../register/pendingConfirm");
exit;
} else if ($userStatus == "CONFIRMED") {
// need to agree
redirectTo("../register/agreement");
exit;
} else if ($userStatus == "AGREE") {
// need to get user details
redirectTo("../register/details");
exit;
} else if ($userStatus == "DETAILS") {
// need to mobile connect
redirect("../register/mobile");
exit;
}
//redirectTo("../home/redirect.php");
} else {
//redirectTo("./");
}

} else {
include_once 'files/default.php';
}

} else {
//redirectTo("/");
include_once 'files/default.php';
}*/
