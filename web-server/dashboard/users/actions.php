<?php

include_once '../data/session.php';
include_once '../data/database.php';
include_once '../data/classes/userClass.php';

$db = new database();
$users = new userClass($db);

$userId = $_SESSION['userId'];
$userName = $_SESSION['user'];

$action = 0;

$act = $_GET['act'];

define("FOLDER_NAME", "users");
include_once "../data/accessControl.php";


if ($act == "new") {
    $salutation = $_POST['salutation'];
    $firstName = $_POST['firstName'];
    $lastName = $_POST['lastName'];
    $email = $_POST['email'];
    $role = $_POST['role'];

    $accPassword = randomPassword();

    if ($db->existsEmail($email)) {
        echo "<script>alert('Sorry, email is already assigned to another user !');</script>";
        echo "<script>history.go(-1);</script>";

    } else if ($users->newUser($firstName, $lastName, $salutation, $email, md5($accPassword), $role, 0, $time, "../img/userIcon.png")) {

    	// Send an email with the password, $accPassword
        redirect("index.php");

    } else {
        echo "<script>alert('Sorry, an unknown error occurred !');</script>";
        echo "<script>history.go(-1);</script>";
    }

} else if ($act == "update") {

    $id = $_POST['userId'];
    $firstName = $_POST['firstName'];
    $lastName = $_POST['lastName'];
    $salutation = $_POST['salutation'];
    $email = $_POST['email'];
    $role = $_POST['role'];

    $res = 0;
    $res += $db->setUserData($id, "firstName", $firstName);
    $res += $db->setUserData($id, "lastName", $lastName);
    $res += $db->setUserData($id, "honorific", $salutation);
    $res += $db->setUserData($id, "email", $email);
    $res += $db->setUserData($id, "role", $role);

    //print_r($_POST);

    if ($res == 5) {
        redirect("index.php");

    } else {
        echo "<script>alert('Sorry, an unknown error occurred !');</script>";
        echo "<script>history.go(-1);</script>";
    }

} else if ($act == "delete") {
    $id = $_POST['userId'];

    if ($id == $_SESSION['userId']) {
        echo "<script>alert('You can not delete your account !');</script>";
        echo "<script>history.go(-1);</script>";

    } else {
        if ($db->deleteUser($id)) {
            redirect("index.php");
            // exit;
        } else {
            echo "<script>alert('Sorry, an unknown error occurred !');</script>";
            echo "<script>history.go(-1);</script>";
        }
    }
}


function redirect($url)
{
    header("Location: $url");
}

function randomPassword() {
    $alphabet = "abcdefghijklmnopqrstuwxyzABCDEFGHIJKLMNOPQRSTUWXYZ0123456789";
    for ($i = 0; $i < 8; $i++) {
        $n = rand(0, count($alphabet)-1);
        $pass[$i] = $alphabet[$n];
    }
    return $pass;
}

?>
