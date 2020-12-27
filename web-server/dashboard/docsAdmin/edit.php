<?php include '../data/session.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php include '../data/meta.php'; ?>
    <?php include '../data/scripts.php'; ?>

    <link href="../css/jquery-te-1.4.0.css" rel="stylesheet"/>
   <link href="../css/amsify.suggestags.css" rel="stylesheet" type="text/css">
   <link href="../css/documents.css" rel="stylesheet"/>

   <script type="application/javascript" src="../js/jquery-te-1.4.0.min.js"></script>
   <script type="text/javascript" src="../js/jquery.amsify.suggestags.js"></script>

</head>
<body>

<a name="top"></a>
<?php include '../data/navibar.php'; ?>

<?php

define("FOLDER_NAME", "docsAdmin");
include_once "../data/accessControl.php";

include_once "../data/database.php";
$db = new database();
$salutation = json_decode(file_get_contents("../lists/salutations.json"), true);
?>

<div class="w3-row">
    <div class="w3-col m2 l2 hidden-sm">&nbsp;</div>
    <div class="w3-col s12 m8 l8">
        <br><br><br><br>

        <ul class="breadcrumb w3-card-2 w3-container w3-margin-8">
            <li><a href="../home">Home</a></li>
            <li><a href="../docsAdmin/">Documents Manager</a></li>
            <li class="active">Edit Document Details</a></li>
        </ul>

        <br>

        <div>
            <?php

            if (!isset($_GET['id'])) {
                echo "<h4>Invalid Access !!!</h4>";
                exit;

            } else if ($db->existsDocId($_GET['id']) == 0) {
                echo "<h4>Invalid Document Id !!!</h4>";
                exit;
            }

            $id = $_GET['id'];
            $data = $db->getDoc($id);

            $title = $data["docTitle"];
            $docType = $data['docType'];
            $docTags = $data['docTags'];
            $docVisibility = $data['docVisibility'];
            $docNotes = $data['docNotes'];

            $docAuthor = $db->getRelation($id, "AUTHOR");
            $docAdviser = $db->getRelation($id, "ADVISER");

            $docPath = $db->get_SiteData("filePath") . $id . ".pdf";

            ?>

            <div>
                <form name="newDoc" class="w3-container w3-card-4 w3-light-grey w3-padding-16 w3-margin-8"
                      method="post" action="../docs/actions.php?act=edit&id=<?php echo $id; ?>" enctype="multipart/form-data">

                    <h2>Edit Document</h2>
                    <br>

                    <p>
                        <label>Title</label>
                        <input class="w3-input w3-border w3-round" name="docTitle" id="docTitle" type="text" required
                               value="<?= $title; ?>">
                    </p>

                    <p>
                        <label>Type</label>
                        <select class="w3-select w3-border w3-round" name="docType" id="docType" required>
                            <option value="" disabled selected>Select the Document Type</option>
                            <option value="0" <?php if ($docType == 'RES_PAPER') echo " selected "; ?> >Research Paper
                            </option>
                            <option value="1" <?php if ($docType == 'REPORT') echo " selected "; ?> >Project Report
                            </option>
                            <option value="2" <?php if ($docType == 'THESIS') echo " selected "; ?> >Thesis</option>
                            <option value="3" <?php if ($docType == 'ARTICLE') echo " selected "; ?>  disabled>Article
                            </option>
                            <option value="4" <?php if ($docType == 'VIDEO') echo " selected "; ?>  disabled>Video
                            </option>
                            <option value="5" <?php if ($docType == 'POST') echo " selected "; ?>  disabled>Post
                            </option>
                        </select>
                    </p>

                    <!-- Need to add a mechanism to suggest -->

                    <p>
                        <label>Author(s)</label>
                        <input class="w3-input w3-border w3-round" name="docAuthor" id="docAuthor" type="text" required
                               placeholder="Type by comma separated" value="<?php echo $docAuthor; ?>">
                    </p>

                    <p>
                        <label>Adviser(s)</label>
                        <input class="w3-input w3-border w3-round" name="docAdviser" id="docAdviser" type="text"
                               placeholder="Type by comma separated" value="<?php echo $docAdviser; ?>">
                    </p>

                    <p>
                        <label>Tags</label>
                        <input class="w3-input w3-border w3-round" name="docTags" id="docTags" type="text"
                               placeholder="Type by comma separated" value="<?php echo $docTags; ?>">
                    </p>

                    <p>
                        <label>Description</label>
                        <textarea name="docDescription" rows="7" id="docDescription" class="jqteText"><?php echo $docNotes; ?></textarea>

                    </p>

                    <p class="w3-hide">
                        <label>Publishing Rule</label>
                        <select class="w3-select w3-border w3-round" name="docVisibility" id="docVisibility" required>
                            <option value="" disabled selected>Select a option</option>
                            <option value="0" <?php if ($docVisibility == '0') echo " selected "; ?>>Visible to
                                Everyone
                            </option>
                            <option value="1" <?php if ($docVisibility == '1') echo " selected "; ?>>Visible to Search
                                Index
                            </option>
                            <option value="2" <?php if ($docVisibility == '2') echo " selected "; ?>>( Need to discuss
                                this further more )
                            </option>
                        </select>
                    </p>


                    <p class="">
                        <label>Re-submit the File (PDF only, max 2MB)</label>
                    </p>

                    <div id="drop_file_zone" ondrop="upload_file(event)" ondragover="return false">
                        <div id="drag_upload_file">
                            <p><span id="pdfName"><?php echo $id ?>.pdf</span></p>

                            <p><input type="button" value="Select File" onclick="file_explorer();"></p>
                            <input class="w3-input w3-border w3-round" id="docPDF" name="docPDF" type="file">
                        </div>
                    </div>

                    <p>
                        <button id="submit" type="submit" class="w3-btn w3-theme w3-round">Update Now</button>
                    </p>

                </form>
            </div>
        </div>

        <br><br><br><br>

    </div>

</div>

<script>
    $(document).ready(function () {

      $('input[name="docAuthor"]').amsifySuggestags({
         type : 'amsify',
         suggestionsAction : {
            url : 'http://localhost/CO227-Project/web/public/dashboard/lists/users.php'
         }
      });
      $('input[name="docAdviser"]').amsifySuggestags({
         type : 'amsify',
         suggestionsAction : {
            url : 'http://localhost/CO227-Project/web/public/dashboard/lists/users.php'
         }
      });
      $('input[name="docTags"]').amsifySuggestags({
         type : 'amsify',
         tagLimit: 5,
      });

        $('.jqteText').jqte({formats: false});

        // settings of status
        var jqteStatus = true;
        $(".status").click(function () {
            jqteStatus = jqteStatus ? false : true;
            $('.jqte-test').jqte({"status": jqteStatus})
        });

    });

    var fileobj;
    function upload_file(e) {
        e.preventDefault();
        fileobj = e.dataTransfer.files[0];
        $("#pdfName").html(fileobj.name);
        console.log(fileobj);
        //ajax_file_upload(fileobj);
    }

    function file_explorer() {
        document.getElementById('docPDF').click();
        document.getElementById('docPDF').onchange = function () {
            fileobj = document.getElementById('docPDF').files[0];
            $("#pdfName").html(fileobj.name);
            //ajax_file_upload(fileobj);
            console.log(fileobj);
        };
    }
</script>


</body>
</html>
