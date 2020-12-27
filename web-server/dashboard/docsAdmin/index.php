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

define("FOLDER_NAME", "docsAdmin");
include_once "../data/accessControl.php";

include_once "../data/database.php";
$db = new database();

?>

<div class="w3-container">
    <div class="w3-row">
        <div class="w3-col m2 l2 hidden-sm">&nbsp;</div>
        <div class="w3-col s12 m8 l8">
            <br><br><br><br>

            <ul class="breadcrumb w3-card-2 w3-container w3-margin-8">
                <li><a href="../home">Home</a></li>
                <li class="active">Documents Manager</a></li>
            </ul>
            <br>

            <ul class="w3-navbar w3-theme-l2" style="margin: 10px 16px;">
                <li><a href="#" class="tablink w3-theme navBarTitle">Documents</a></li>
            </ul>

            <br>

            <div class="w3-container">
                <?php

                $docs = array_reverse($db->listDocs());// = $db->listCourses("id");

                if (sizeof($docs) == 0) {
                    echo "<p class='w3-center w3-text-gray w3-padding-24'>No any document submitted yet.</p>";
                }

                $colors = array(
                    "PENDING" => "w3-text-orange",
                    "APPROVED" => "w3-text-green",
                    "REJECTED" => "w3-text-red"
                );

                for ($i = 0; $i < sizeof($docs); $i++) {
                    $id = $docs[$i]["id"];
                    $title = $docs[$i]["docTitle"];
                    $status = $docs[$i]["docStatus"];
                    $tags = "#" . implode(" #", explode(",", str_replace(" ", "", $docs[$i]['docTags'])));

                    $submitOn = $docs[$i]['submitTime'];
                    $submitBy = $db->getName_byUserId($docs[$i]['submitBy']);

                    $authorName = "";//$db->getName_byUserId($lecId);
                    $adviserName = "";//$db->getName_byUserId($instId);

                    $statusColor = $colors[$status];
                    print "<div class='w3-container w3-border-top w3-border-theme w3-padding-8' style='border-top: 1px solid'>
                                    <div class='w3-row w3-small'>
                                        <div class='w3-col s11 m11 111'>
                                            <div class='w3-container' onclick='window.location.href=\"./view.php?id=$id\"'>
                                                <div class='w3-col s10 m10 l10 w3-large'>$title</div>
                                                <div class='w3-col s2 m2 l2 $statusColor w3-padding-8' >$status</div>
                                                <div class='w3-row w3-small'><span class='w3-text-gray'>$submitOn | $submitBy</span><br><span class='w3-text-blue'>$tags</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class='w3-col s1 m1 11 w3-center'>
                                            <a href='view.php?id=$id'><i class='fa fa-eye fa-2x'></i><br>
                                            <a href='edit.php?id=$id'><i class='fa fa-pencil-square-o fa-2x'></i><br>
                                            <a href='delete.php?id=$id'><i class='fa fa-2x fa-trash'></i></a>
                                        </div></div></div>";

                }

                ?>

            </div>
        </div>
    </div>
    <br><br><br>
</div>

</body>
</html>
