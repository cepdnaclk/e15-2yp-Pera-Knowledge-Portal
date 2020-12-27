<?php

include_once '../data/session.php';
include_once '../data/database.php';
include_once "../data/classes/userClass.php";

define("FOLDER_NAME", "profile");
include_once "../data/accessControl.php";

$time = date("Y-m-d H:i:s");

$db = new database();
$users = new userClass($db);

$act = $_GET['act'];
$userId = $_SESSION['userId'];

if ($act="profile"){
   // Enable or disable profile page
   $status = ($_GET['enable'] == "true") ? 1 : 0;
   echo $users->setUserData($userId, "profilePage", $status);

}
