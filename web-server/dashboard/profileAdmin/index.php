<?php include '../data/session.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php include '../data/meta.php'; ?>
    <?php include '../data/scripts.php'; ?>
</head>
<body>

<a name="top"></a>
<?php include '../data/navibar.php'; ?>

<?php

define("FOLDER_NAME", "profileAdmin");
include_once "../data/accessControl.php";

include_once "../data/database.php";
include_once "../data/classes/profileClass.php";
include_once "../data/classes/userClass.php";

$db = new database();
$profile = new profileClass($db);
$user = new userClass($db);
?>

<div class="w3-container">
    <div class="w3-row">
        <div class="w3-col m2 l2 hidden-sm">&nbsp;</div>

        <div class="w3-col s12 m8 l8">
            <br><br><br><br>

            <ul class="breadcrumb w3-card-2 w3-container w3-margin-8">
                <li><a href="../home">Home</a></li>
                <li class="active">Manage Profiles</a></li>
            </ul>
            <br>

            <ul class="w3-navbar w3-theme-l2" style="margin: 10px 16px;">
                <li><a href="#" class="tablink w3-theme navBarTitle">Profile Pages</a></li>
            </ul>

            <br>

            <div class="w3-container">

                <?php
                // TODO: Currently showing all the profiles. need to filter by referee too.

                $profiles = $profile->list_profiles("%"); // PENDING

                if (sizeof($profiles) == 0) {
                    echo "<p class='w3-center w3-text-gray w3-padding-24'>No any profile submitted yet.</p>";
                }

                $colors = array(
                    "PENDING" => "w3-text-orange",
                    "APPROVED" => "w3-text-green",
                    "REJECTED" => "w3-text-red"
                );

                $deptArray = json_decode(file_get_contents("../lists/departments.json"), true);

                for ($i = 0; $i < sizeof($profiles); $i++) {
                    $id = $profiles[$i]["userId"];
                    $profileName = $user->getUserData($id, "firstName") . " " . $user->getUserData($id, "lastName");
                    $position = $profile->get_profileRequestData($id, "pos");
                    $status = $profile->get_profileRequestData($id, "status");
                    $deptId = $profile->get_profileRequestData($userId, "department");
                    $department = ($deptId == 0) ? "" : $deptArray[$deptId];

                    $statusColor = $colors[$status];
                    print "<div class='w3-container w3-border-top w3-border-theme w3-padding-8' style='border-top: 1px solid'>
                                    <div class='w3-row w3-small'>
                                        <div class='w3-col s11 m11 111'>
                                            <div class='w3-container' onclick='window.location.href=\"./view.php?id=$id\"'>
                                                <div class='w3-col s10 m10 l10 w3-large'>$profileName ($id)</div>
                                                <div class='w3-col s2 m2 l2 $statusColor w3-padding-8' >$status</div>
                                               <div class='w3-row w3-small'><span class='w3-text-gray'>
                                                    $position, $department<br>
                                                    University of Peradeniya</span><br>
                                                </div>
                                            </div>
                                        </div>
                                        <div class='w3-col s1 m1 11 w3-center'>
                                            <a href='view.php?id=$id'><i class='fa fa-eye fa-2x'></i></a><br>
                                        </div></div></div>";

                }

                ?>
            </div>
        </div>

        <div class="w3-hide w3-col s12 m8 l8">
            <br><br><br><br>

            <div class="w3-container">
            </div>
        </div>
    </div>
    <br><br><br>
</div>

</body>
</html>
