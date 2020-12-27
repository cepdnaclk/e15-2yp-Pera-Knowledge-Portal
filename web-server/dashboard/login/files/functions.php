<?php

function loginWithGoogle($data)
{
   $id = $data['id'];
   $email = $data['email'];
   $verifiedEmail = $data['verified_email'];
   $name = $data['name'];
   $picture = $data['picture'];
   $time = date("Y-m-d H:i:s");

   $isPdnEmail = endsWith($email, '@eng.pdn.ac.lk');

   if ($verifiedEmail == 0) {
      HandleError("Your email isn't a verified email. Please try with a valid email account");

   } else {

      $db = new database();
      $users = new userClass($db);

      if ($db->existsEmail($email)) {
         // User account already exists. Lets continue with usual login scheme

      } else {
         // A new user. Need to add to the database and do necessary procedure
         // img/profile/userIcon.png

         //newUser(, $name, 9, $email, $password, $role, $loginType, $userIcon, $lastAccess, $imgURL)
         if ($users->newUser("", $name, 9, $email, "", 5, "1", $time, "img/profile/userIcon.png")) {
            // New user account created
            $userId = $users->getUserId_byEmail($email);

         } else {
            HandleError("Account creation failed. Please contact System Administration.");
            return false;
         }
      }

      $userId = $db->getUserId_byEmail($email);

      // Update profile picture
      $file = $picture;
      $newfile = "../../img/profile/".$userId.".png";

      if(!file_exists ($newfile)){
         if ( copy($file, $newfile) ) {
            echo "Copy success!";
            $users->setUserData($userId, "imageURL", "img/profile/".$userId.".png");

         }else{
            echo "Copy failed.";
         }
      }else{
         echo "Already exists.";
         $users->setUserData($userId, "imageURL", "img/profile/".$userId.".png");
      }

      $sal = json_decode(file_get_contents("../lists/salutations.json"), true);
      $userNameString = $sal[$db->getUserData($userId, "salutation")] . " " . $db->getUserData($userId, "firstName") . " " . $db->getUserData($userId, "lastName");
      $userEmailString = $db->getUserData($userId, "email");

      $_SESSION['user'] = nameFormat($name);
      $_SESSION['userId'] = $userId;
      $_SESSION['userName'] = $email;
      $_SESSION['role'] = $db->getUserData($userId, "role");

      $_SESSION['userNameString'] = $userNameString;
      $_SESSION['userEmailString'] = $userEmailString;
      $_SESSION['viewMode'] = "desktop";
      $_SESSION['userAccess'] = GetLoginSessionVar();
      $_SESSION['userImage'] = $db->getUserData($userId, "imageURL");

      $_SESSION[GetLoginSessionVar()] = GetLoginSessionVar();
   }

   return true;
}

function endsWith($haystack, $needle)
{
   $length = strlen($needle);
   if ($length == 0) return true;
   return (substr($haystack, -$length) === $needle);
}

function loginToSite()
{
   // Validating section
   if (empty($_POST['username'])) {
      HandleError("UserName is empty!");
      return false;
   }

   if (empty($_POST['password'])) {
      HandleError("Password is empty!");
      return false;
   }

   $userName = trim($_POST['username']);
   $password = trim($_POST['password']);

   $db = new database();
   $result = ($db->existsUser($userName, $password));

   if ($result == 0) {
      HandleError("Error: The user name or password does not match.");
      return false;

   } else {
      $userId = $db->getUserId_byEmail($userName);

      // Check account status ------------------------------------------------------

      $role = $db->getUserData($userId, "role");

      $sal = json_decode(file_get_contents("../lists/salutations.json"), true);
      $userNameString = $sal[$db->getUserData($userId, "salutation")] . " " . $db->getUserData($userId, "firstName") . " " . $db->getUserData($userId, "lastName");
      $userEmailString = $db->getUserData($userId, "email");
      $time = date("Y-m-d H:i:s");

      $db->setUserData($userId, "lastAccess", $time);

      //session_start();
      $_SESSION['user'] = nameFormat($userName);
      $_SESSION['userId'] = $userId;
      $_SESSION['userName'] = $userName;
      $_SESSION['role'] = $db->getUserData($userId, "role");

      $_SESSION['userNameString'] = $userNameString;
      $_SESSION['userEmailString'] = $userEmailString;
      $_SESSION['viewMode'] = "desktop";
      $_SESSION['userAccess'] = GetLoginSessionVar();
      $_SESSION['userImage'] = $db->getUserData($userId, "imageURL");
      $_SESSION[GetLoginSessionVar()] = GetLoginSessionVar();

      return true;
   }
}

function logOut()
{
   session_start();
   $viewMode = $_SESSION['viewMode'];

   $sessionvar = GetLoginSessionVar();
   $_SESSION[$sessionvar] = NULL;
   unset($_SESSION[$sessionvar]);

   $params = session_get_cookie_params();
   setcookie(session_name(), '', time() - 42000,
   $params["path"],
   $params["domain"],
   $params["secure"],
   $params["httponly"]);

   session_unset();
   session_destroy();

   if ($viewMode == "mobileApp") {
      print "<script>window.close();</script>";
   } else {
      header("location: ./");
   }
   exit;
}

function HandleError($err)
{
   global $error_message;
   $error_message = $err . "\r\n";
}

function GetErrorMessage()
{
   global $error_message;

   if (empty ($error_message)) {
      return '';
   }
   $errormsg = nl2br(htmlentities($error_message));
   return $errormsg;
}

function GetLoginSessionVar()
{
   $rand_key = "0iQx5oBk66oVZep";
   $retvar = md5($rand_key);
   $retvar = 'usr_' . substr($retvar, 0, 10);
   return $retvar;
}

function nameFormat($str)
{
   if (strlen($str) > 0) {
      return $str;
   } else {
      return "Null";
   }
}

function redirectTo($url)
{
   header("location: " . $url);
}

?>
