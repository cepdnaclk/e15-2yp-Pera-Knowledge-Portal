<?php

//print_r($_SERVER);
include_once '../dashboard/data/communication.php';
include '../dashboard/data/database.php';
include_once "../dashboard/data/classes/profileClass.php";
include_once "../dashboard/data/classes/userClass.php";
include_once "../dashboard/data/classes/docsClass.php";


$db = new database();
$profile = new profileClass($db);
$user = new userClass($db);
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
   <link rel="stylesheet" href="../dashboard/css/profile.css">
   <link rel="shortcut icon" href="./../img/fav.ico">

   <script type="application/javascript" src="../js/jquery.min.js"></script>
   <script type="application/javascript" src="../js/index.js"></script>

   <style>
   .skill {
      border: 1px solid black;
      border-radius: 10px;
      margin:2px;
   }
   </style>
</head>

<body>

   <?php
   include '../data/navibar.php';
   ?>
   <br><br><br><br>

   <?php
   if(substr($_GET['user'], 0,1) =="e"){

   		$eNum = $_GET['user'];

   		if($eNum=="e15140"){
   			$userId = 100000;
   		}else if ($eNum=="e15350"){
   			$userId = 100001;
   		}else if ($eNum=="e15173"){
   			$userId = 100002;
		}else{
			echo "<h3 class='w3-container w3-center'>Sorry, user not available.</h3>";
			exit;
		}

   }else if(isset($_GET['user'])){
      $userId = $_GET['user'];

      if (!$profile->existsActiveProfile($userId)){
         // Not exists
         echo "<h3 class='w3-container w3-center'>Sorry, Content not available.</h3>";
         exit;
      } else if ( $user->getUserData($userId, "profilePage")==0){
         // User Disabled it
         echo "<h3 class='w3-container w3-center'>Sorry, Content not available.</h3>";
         exit;
      }
      // Default page, only for gmp_test.
      //$userId = 100002;//$_SESSION['userId'];
      // include_once "../403.shtml";
   }

   $userName = $user->getUserData($userId, "firstName") . " " . $user->getUserData($userId, "lastName");
   $userNameId = $user->getUserData($userId, "lastName") . " " . $user->getUserData($userId, "firstName");

   $profileImg = $user->getUserData($userId, "imageURL");

   $profileData  = $profile->get_activeProfile($userId);
   $position =$profileData['pos'];
   $deptId = $profileData['department'];

   $deptArray = json_decode(file_get_contents("../dashboard/lists/departments.json"), true);
   $department = ($deptId == 0) ? "" : $deptArray[$deptId];

   $email = $user->getUserData($userId, "email");
   $linkedin =$profileData['linkedinProfile'];
   $github = $profileData['githubProfile'];

   $description = $profileData['description'];
   $skills = explode(',', $profileData['skills']);

   ?>

   <div class="w3-row">
      <div class="w3-col s0 m2 l3">&nbsp;</div>
      <div class="w3-col s0 m8 l6">


         <div class=" w3-card-8">

            <div class="w3-row">
               <div class="w3-col s12 m5 l4" style="padding:20px">
                  <div class="w3-center">
                     <img src="../<?php echo $profileImg; ?>" class="w3-circle w3-card-4 w3-border"
                     alt="Profile Picture" style="width:150px;">
                  </div>
               </div>

               <div class="w3-col s12 m7 l8" style="padding:20px">
                  <h5><b><?php echo $userName; ?></b></h5>

                  <p><?php echo $position; ?><br>
                     <?php echo $department . "<br>" ?>
                     University of Peradeniya
                  </p>
                  <div>
                     <?php
                     if($linkedin!=""){
                        $linkedinText = str_replace("https://www.linkedin.com/in/", "", $linkedin);
                        $linkedinText = str_replace("/", "", $linkedinText);
                        echo "
                        <span>
                        <a href= '$linkedin' target='_blank'>
                        <img src='../img/icons/linkedin-128.png' alt='linkedin' style='width:30px'>
                        <span>$linkedinText</span>
                        </a>
                        </span><br>";
                     }

                     if($github!=""){
                        $githubText = str_replace("https://github.com/", "", $github);
                        $githubText = str_replace("/", "", $githubText);
                        echo "
                        <span>
                        <a href= '$github' target='_blank'>
                        <img src='../img/icons/github-128.png' alt='github' style='width:30px'>
                        <span>$githubText</span>
                        </a>
                        </span><br>";
                     }

                     if($email!=""){
                        echo "
                        <span>
                        <img src='../img/icons/email-128.png' alt='github' style='width:30px'>
                        <span>$email</span>
                        </span><br>";
                     }

                     ?>
                  </div>
               </div>
            </div>
         </div>

         <?php
         if($description!=""){
            echo "<br><div class='w3-card-8 w3-padding-8'><div class='w3-container'>";
            echo "<h6><b>Description</b></h6>";
            echo "<p>$description</p>";
            echo "</div></div>";
         }

         if(sizeof($skills)>0){
            echo "<br><div class='w3-card-8 w3-padding-8'><div class='w3-container'>";
            echo "<h6><b>Skills and Interests</b></h6>";
            echo "<p id='previewSkills'>";

            for ($i = 0; $i < sizeof($skills); $i++) {
               echo "<span class='icons w3-tag w3-white'>" . $skills[$i] . "</span>";
            }

            echo "</p>";
            echo "</div></div>";
         }
         ?>

         <?php
         $docsList = $docs->get_DocsByUser($userNameId);

         if(sizeof($docsList)>0){

            echo "<br><div class='w3-card-8 w3-padding-8'><div class='w3-container'>";
            echo "<h6><b>Portfolio</b></h6>";

            for($i=0; $i<sizeof($docsList);$i++){
               $currentDoc = $docsList[$i];
               echo "<div id=\"Projects\" class=\"w3-container w3-animate-opacity w3-display-container tabPage\">";

               echo "<h5><a href='../view/".$currentDoc['id']."'>".$currentDoc['docTitle']."</a></h5>";
               echo "<p class='w3-text-gray'>".strip_tags($currentDoc['docNotes'])."</p>";
               echo "";
               echo "";
               echo "";
               echo "</div>";
            }
            echo "</div>";

         }else{
            // No portfolio
         }
         ?>
      </div>
      <div class="w3-col s0 m2 l3">&nbsp;</div>

      <br><br><br>

      <script>
      function openType(name) {
         var i;
         var x = document.getElementsByClassName("city");
         for (i = 0; i < x.length; i++) {
            x[i].style.display = "none";
         }
         document.getElementById(name).style.display = "block";
      }
      </script>
   </body>
   </html>
