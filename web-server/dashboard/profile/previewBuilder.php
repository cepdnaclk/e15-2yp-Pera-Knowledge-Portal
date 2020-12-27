<?php
header("Access-Control-Allow-Origin: *");

include '../data/session.php';

define("FOLDER_NAME", "profile");
include_once "../data/accessControl.php";

include_once "../data/database.php";
include_once "../data/classes/profileClass.php";
include_once "../data/classes/userClass.php";
include_once "../data/classes/docsClass.php";

$db = new database();
$profile = new profileClass($db);
$user = new userClass($db);
$docs = new docsClass($db);

if (!isset($_GET['userId'])) {
   exit;
}

$userId = $_GET['userId'];

if ($db->existsProfile($userId)) {
   $userName = $db->getUserData($userId, "firstName") . " " . $db->getUserData($userId, "lastName");
   $userNameId = $db->getUserData($userId, "lastName") . " " . $db->getUserData($userId, "firstName");

   $profileImg = $db->getUserData($userId, "imageURL");

   $profileData = $profile->get_profile($userId);
   $position = $profileData['pos'];
   $deptId = $profileData['department'];

   $deptArray = json_decode(file_get_contents("../lists/departments.json"), true);
   $department = ($deptId == 0) ? "" : $deptArray[$deptId];

   $email = $db->getUserData($userId, "email");
   $linkedin = $profileData['linkedinProfile'];
   $github = $profileData['githubProfile'];

   $description = $profileData['description'];
   $skills = explode(',', $profileData['skills']);
} else {
   echo "<h3>Profile Page isn't enabled by this user</h3>";
   exit;
}
?>

<div class=" w3-card-8">

   <div class="w3-row">
      <div class="w3-col s12 m5 l4" style="padding:20px">
         <div class="w3-center">
            <img src="../../<?php echo $profileImg; ?>" class="w3-circle w3-card-4 w3-border"
            alt="Profile Picture" style="width:150px;">
         </div>
      </div>

      <div class="w3-col s12 m7 l8" style="padding:20px">
         <h5><b><?php echo $userName; ?></b></h5>

         <p><?php echo $position.",<br>".$department.",<br>University of Peradeniya"; ?></p>

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

            if ($email != "") {
               echo "<span><img src='../img/icons/email-128.png' alt='github' style='width:30px'>
               <span>$email</span></span><br>";
            }

            ?>
         </div>
      </div>
   </div>
</div>

<?php
// Description
if (!empty($description)) {
   echo "<br><div class='w3-card-8 w3-padding-8'><div class='w3-container'>";
   echo "<h6><b>Description</b></h6>";
   echo "<p>$description</p>"; //  (".strlen($description).")
   echo "</div></div>";
}

// Skills & interests
if (sizeof($skills) > 0) {
   echo "<br><div class='w3-card-8 w3-padding-8'><div class='w3-container'>";
   echo "<h6><b>Skills and Interests</b></h6>";
   echo "<p id='previewSkills'>";

   for ($i = 0; $i < sizeof($skills); $i++) {
      echo "<span class='icons w3-tag w3-white'>" . $skills[$i] . "</span>";
   }

   echo "</p>";
   echo "</div></div>";
}

// Portfolio
$docsList = $docs->get_DocsByUser($userNameId);

if (sizeof($docsList) > 0) {
   echo "<br><div class='w3-card-8 w3-padding-8'><div class='w3-container'>";
   echo "<h6><b>Portfolio</b></h6>";

   for ($i = 0; $i < sizeof($docsList); $i++) {
      $currentDoc = $docsList[$i];
      echo "<div id=\"Projects\" class=\"w3-container w3-animate-opacity w3-display-container tabPage\">";
      echo "<h5><a href='#'>" . $currentDoc['docTitle'] . "</a></h5>";
      echo "<p class='w3-text-gray'>" . strip_tags($currentDoc['docNotes']) . "</p>";
      echo "</div>";
   }
   echo "</div>";

} else {
   // No portfolio
}

?>

</div>
<br><br><br>
