<?php include '../data/session.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
   <?php include '../data/meta.php'; ?>
   <?php include '../data/scripts.php'; ?>

   <link href="../css/home.css" rel="stylesheet"/>
</head>
<body>

   <?php include '../data/navibar.php'; ?>

   <?php
   define("FOLDER_NAME", "home");
   include_once "../data/accessControl.php";

   include_once "../data/database.php";
   $db = new database();
   ?>

   <div class="w3-container">
      <div class="w3-row">
         <div class="w3-col m2 l2 hidden-sm">&nbsp;</div>
         <div class="w3-col s12 m8 l8">
            <div class="w3-container">
               <br><br><br>
               <?php
               //print_r($_SESSION);

               if ($_SESSION['role'] == 0 || $_SESSION['role'] == 4) {
                  // Admin || Data Enter

                  print "<div class='w3-row' style='padding-top: 20px;'><h3 class='home-group'>Admin Tools</h3></div>";
                  print " <div class='w3-row'>";
                  printTile("Users", "../users/", "contact.png", "w3-metro-purple");
                  printTile("Submissions", "../docsAdmin/", "developing.png", "w3-metro-dark-orange");
                   printTile("Profiles", "../profileAdmin/", "developing.png", "w3-metro-dark-green");
                  print "</div>";

                  //print " </div><br><div class='w3-row'>";
                  //printTile("Add New User", "../users/add.php", "new.png", "w3-metro-dark-orange");
                  //print "</div>";
               }

               print "<div class='w3-row' style='padding-top: 20px;'><h3 class='home-group'>User Tools</h3></div>";
               print " <div class='w3-row'>";

               printTile("Add Submission", "../docs/add.php", "new.png", "w3-metro-green");
               printTile("My Submissions", "../docs/", "contents.png", "w3-metro-blue");

               //print "<div class='w3-disabled'>";
               printTile("Profile Page", "../profile/", "contents.png", "w3-metro-dark-red ");
               printTile("Settings", "../settings/", "settings.png", "w3-metro-darken");
               //print "</div>";

               print "</div>";

               ?>


            </div>
         </div>
      </div>
      <br><br><br>
   </div>

   <script>
   function navigate(url) {
      window.location.href = url;
   }

   function notAvailable() {
      document.getElementById('id01').style.display = 'block';
   }
</script>


<?php

function newRow($c)
{
   if ($c == 4) {
      $c = 0;
      print "</div><br><br><div class='row'>";
   }
   return $c;
}

function printCourseManage($id, $title)
{

   echo "<div class='w3-col s12 m6 l6 w3-padding-8' style='padding: 4px!important;'>
   <div class='w3-card-2 w3-border w3-animate-opacity'>
   <div class='w3-container w3-padding-8'>$title<br>

   <div class='w3-right'>
   <a href='../classlist/?id=$id' class='w3-btn w3-theme w3-small' >Class List</a>
   <a href='../attendance/?id=$id' class='w3-btn w3-theme w3-small' >Attendance</a>
   </div>
   </div>
   </div>
   </div>";
}

function printCourse($title, $attendance, $courseId)
{
   if ($attendance > 85) {
      $color = "w3-green";
      $colorBg = "w3-gray";
   } else if ($attendance >= 80) {
      $color = "w3-orange";
      $colorBg = "w3-gray";
   } else {
      $color = "w3-red";
      $colorBg = "w3-gray";
   }
   echo "<a href='../student/?id=$courseId' style='text-decoration: none;'><div class='w3-col s12 m6 l6 w3-padding-8' style='padding: 4px!important;'>
   <div class='w3-card-2 w3-border w3-animate-opacity'>
   <div class='w3-container w3-padding-8'>$title<br></div>

   <div class='w3-progress-container $colorBg'>
   <div id='myBar' class='w3-progressbar $color' style='width:$attendance%'>
   <div class='w3-center w3-text-white'>$attendance%</div>
   </div>
   </div>
   </div>
   </div></a>";
}

function printTile($title, $href, $img, $color)
{
   print "
   <div class='w3-col s6 m4 l3' style='padding: 4px!important;'>
   <a href='$href' class='w3-center' style='text-decoration: none;'>
   <div class='$color w3-center homeTile'>
   <img class='w3-animate-opacity' style='width: 45%; padding: 10px 0 10px 0; ' src='../img/iconsHome/$img'>
   <div class='w3-responsive homeTileName'>$title</div>
   </div>
   </a>
   </div>";
}

?>


<div id="id01" class="w3-modal">
   <div class="w3-modal-content w3-animate-top w3-card-8">
      <header class="w3-container w3-theme">
         <span onclick="document.getElementById('id01').style.display='none'"
         class="w3-closebtn">×</span>

         <h2>Ops :-(</h2>
      </header>
      <div class="w3-container">
         <br><br>

         <p>This feature is currently not available</p>

         <p>Please try again later</p>
         <br><br><br>
      </div>
      <footer class="w3-container w3-theme">

      </footer>
   </div>
</div>

</body>
</html>
