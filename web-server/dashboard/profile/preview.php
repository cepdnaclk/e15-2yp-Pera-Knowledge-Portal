<?php include '../data/session.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
   <?php include '../data/meta.php'; ?>
   <?php include '../data/scripts.php'; ?>

   <link href="../css/jquery-te-1.4.0.css" rel="stylesheet"/>
   <link href="../css/profile.css" rel="stylesheet"/>

   <script src="../js/jquery-te-1.4.0.min.js" type="application/javascript"></script>
</head>
<body>
   <a name="top"></a>

   <?php

   define("FOLDER_NAME", "profile");
   include_once "../data/accessControl.php";
   include_once "../data/database.php";
   include_once "../data/classes/profileClass.php";

   $db = new database();
   $profile = new profileClass($db);

   if ($profile->existsProfile($userId)) {
      include '../data/navibar.php';

   } else {
      echo "<h3>You haven't enabled your profile page yet.</h3>";
      exit;
   }

   ?>

   <div class="w3-row">
      <div class="w3-col s1 m2 l2">&nbsp;</div>
      <div class="w3-col s10 m8 l8">
         <br><br><br><br>

         <ul class="breadcrumb w3-card-2 w3-container w3-margin-8">
            <li><a href="../home">Home</a></li>
            <li><a href="./">Profile</a></li>
            <li class="active">Preview</a></li>
         </ul>

         <br>
         <div class="w3-container">
            <a class="w3-button w3-theme-button" href="./index.php">Back</a>
         </div>
         <br/>
      </div>
      <div class="w3-col s1 m2 l2">&nbsp;</div>
   </div>

   <hr/>

   <div class="w3-row">
      <div class="w3-col s1 m2 l3">&nbsp;</div>
      <div class="w3-col s10 m8 l6">
         <div id="previewDiv"></div>
      </div>
      <div class="w3-col s1 m2 l3">&nbsp;</div>
   </div>

   <script>
   $(document).ready(function(){
      $("#previewDiv").load("previewBuilder.php?userId=<?php echo $userId; ?>"); // ,data,callback
   });
   </script>

</body>
</html>
