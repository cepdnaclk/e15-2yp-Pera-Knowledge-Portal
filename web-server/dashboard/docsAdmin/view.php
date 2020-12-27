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

if (!isset($_GET['id'])) {
    include_once '../403.shtml';
    exit;
}
$docId = $_GET['id'];
?>

<div class="w3-container">
    <div class="w3-row">
        <div class="w3-col m2 l2 hidden-sm">&nbsp;</div>
        <div class="w3-col s12 m8 l8">
            <br><br><br><br>

            <ul class="breadcrumb w3-card-2 w3-container w3-margin-8">
                <li><a href="../home">Home</a></li>
                <li><a href="../docsAdmin">Documents Manager</a></li>
                <li class="active">View</a></li>
                <li class="active"><?php echo $docId ?></a></li>
            </ul>

            <br>

            <div class="w3-container">
                <?php

                if ($db->existsDocId($docId)) {

                    $data = $db->getDoc($docId);

                    $title = $data["docTitle"];
                    $submitBy = $data['submitBy'];
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
                    $submitByName = $db->getName_byUserId($submitBy);


                    echo "<table class='w3-table '>";
                    echo "<tr><td width='150px;'>Title</td><td>$title</td></tr>";
                    echo "<tr><td>Type</td><td>$docType</td></tr>";

                    echo "<tr><td>Submitted By</td><td>$submitByName</td></tr>";
                    echo "<tr><td>Authors</td><td>" . implode("<br>", $arrayAuthors) . "</td></tr>";
                    echo "<tr><td>Advisers</td><td>" . implode("<br>", $arrayAdvisers) . "</td></tr>";
                    echo "<tr><td>Tags</td><td>$docTags</td></tr>";

                    echo "<tr><td>&nbsp;</td><td><a class='w3-theme-button w3-button' href='$docPath' target='_blank'>View PDF</a></td></tr>";

                    echo "<tr><td>Description</td><td>$docNotes</td></tr>";

                    echo "<tr><td>Change the status to:</td><td>";

                    if ($status == "PENDING") {
                        echo "<a class='w3-button w3-green'  href='./actions.php?act=approve&id=$docId' >Approve</a> ";
                        echo "<a class='w3-button w3-red' href='./actions.php?act=reject&id=$docId'>Reject</a>";

                    } else if ($status == "APPROVED") {
                        echo "<a class='w3-button w3-orange' href='./actions.php?act=pending&id=$docId'>Pending</a> ";
                        echo "<a class='w3-button w3-red' href='./actions.php?act=reject&id=$docId'>Reject</a>";

                    } else if ($status == "REJECTED") {
                        echo "<a class='w3-button w3-orange' href='./actions.php?act=pending&id=$docId'>Pending</a> ";
                        echo "<a class='w3-button w3-green'  href='./actions.php?act=approve&id=$docId' >Approve</a>";
                    }

                    echo "</td></tr>";

                    echo "<tr><td> </td><td> </td></tr>";

                    echo "</table>";

                } else {
                    echo "<h3>Invalid Document Id</h3>";
                }

                ?>
            </div>
        </div>
    </div>
    <br><br><br>
</div>

</body>
</html>
