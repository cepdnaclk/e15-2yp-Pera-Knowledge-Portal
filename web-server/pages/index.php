<?php

include_once '../dashboard/data/communication.php';
include_once '../dashboard/data/database.php';

$db = new database();

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
      <div class="w3-col s0 m2 l3">&nbsp;</div>
      <div class="w3-col s0 m8 l6">

         <?php

         if(isset($_GET['page'])){
            $page = $_GET['page'];

            if($page == "privacy-policy"){
               include_once "pages/privacy.html";

            } else if($page == "terms"){
               include_once "pages/terms.html";

            } else if($page == "faq"){
               include_once "pages/faq.html";

            }else if($page == "support"){
               include_once "pages/support.html";

            }
         }else{

         }



         ?>

      </div>
      <div class="w3-col s0 m2 l3">&nbsp;</div>
   </div>

</body>
</html>
