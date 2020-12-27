<?php

include_once '../dashboard/data/communication.php';
include_once '../dashboard/data/database.php';
include_once '../dashboard/data/classes/docsClass.php';

$db = new database();
$docs = new docsClass($db);
?>
<!DOCTYPE html>
<html>
<head>
   <title>Pera Knowledge Hub</title>
   <meta name="viewport" content="width=device-width, initial-scale=1">

   <link rel="stylesheet" href="../css/w3.css">
   <link rel="stylesheet" href="../css/w3-theme.css">
   <link rel="stylesheet" href="../css/font-awesome.min.css">
   <link rel="stylesheet" href="../css/index.css">
   <link rel="shortcut icon" href="./../img/fav.ico">

   <script type="application/javascript" src="../js/jquery.min.js"></script>
   <script type="application/javascript" src="../js/index.js"></script>
</head>

<body>
   <?php include '../data/navibar.php'; ?>
   <br><br><br>
   <br>
   <div class="w3-row">
      <div class="w3-container w3-padding-jumbo w3-center">
         <div class="w3-xxxlarge"><?php  echo  $_GET['page']; ?></div>
      </div>
   </div>
   <div class="w3-row">
      <div class="w3-col s0 m2 l3">&nbsp;</div>
      <div class="w3-col s0 m8 l6">
         <br />
         <div class="w3-container w3-border-top w3-border-theme-2 " id="result" style="min-height:80vh;">
            <br>
            <?php

            if(isset($_GET['page'])){
               $page = $_GET['page'];
               $docList = $docs->get_DocsByTag($page);

               for($i=0;$i<sizeof($docList);$i++){
                  $docId =$docList[$i]['id'];
                  $docTitle =$docList[$i]['docTitle'];
                  $docTags = array_map('trim',explode(",", $docList[$i]['docTags']));

                  echo "<div class='w3-row w3-card-2 w3-animate-opacity' style='margin-bottom: 10px!important;'>
                  <div class='w3-col w3-padding-12' style='width: 84px;'><img class='w3-margin-16' style='width: 32px;' src='../img/pdf.png'></div>
                  <div class='w3-rest'>
                  <p><a href='../view/$docId' style='text-decoration: none;' target='_blank'><span class=''><b>$docTitle</b></span></a><br>
                  <span class='spanTag'>";

                  for($j=0;$j<sizeof($docTags);$j++){
                     echo "<a href='../docs/".$docTags[$j]."'>#".$docTags[$j]."</a>&nbsp;&nbsp;";
                  }

                  echo "</span></p></div></div>";
               }

            }else{

            }

            ?>
         </div>
      </div>
      <div class="w3-col s0 m2 l3">&nbsp;</div>
   </div>

</body>
</html>
