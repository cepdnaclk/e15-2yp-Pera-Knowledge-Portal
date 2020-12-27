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

define("FOLDER_NAME", "view");
include_once "../data/accessControl.php";

include_once "../data/database.php";
$db = new database();

if(!isset($_GET['id'])){
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

                    $row = array(
                        "documentId" => $docId,
                        "title" => $title,
                        "documentType" => "RES_PAPER",
                        "userId" => $submitBy,
                        "status" => $status,
                        "authors" => $arrayAuthors,
                        "advisers" => $arrayAdvisers,
                        "tags" => $docTags,
                        "visibility" => "visible"
                    );

                    echo "<a class='w3-button w3-theme' href='$docPath' target='_blank'>View PDF</a>";

                    echo "<br><div class='w3-container w3-padding-12'><h4>$title</h4>$docNotes</div>";

                    echo "<div class='w3-responsive'><pre>" . json_encode($row, JSON_PRETTY_PRINT) . "</pre></div><br><br>";

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
