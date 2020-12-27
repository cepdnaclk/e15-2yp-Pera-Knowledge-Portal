<?php include '../data/session.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
   <?php include '../data/meta.php'; ?>
   <?php include '../data/scripts.php'; ?>

   <link href="../css/profile.css" rel="stylesheet"/>

   <link href="../css/jquery-te-1.4.0.css" rel="stylesheet"/>
   <link href="../css/amsify.suggestags.css" rel="stylesheet" type="text/css">

   <script type="application/javascript" src="../js/jquery-te-1.4.0.min.js"></script>
   <script type="text/javascript" src="../js/jquery.amsify.suggestags.js"></script>

</head>
<body>
   <a name="top"></a>
   <?php include '../data/navibar.php'; ?>

   <?php

   define("FOLDER_NAME", "profile");
   include_once "../data/accessControl.php";

   include_once "../data/database.php";
   include_once "../data/classes/profileClass.php";
   include_once "../data/classes/userClass.php";

   $db = new database();
   $profile = new profileClass($db);
   $users = new userClass($db);

   if(isset($_POST['submitted'])){
      // On data submission

      $department = $_POST['department'];
      $position = $_POST['position'];
      $interests = implode(",",array_map('trim', explode(',', $_POST['interesets'])));
      $linkedin = $_POST['linkedin'];
      $github = $_POST['github'];
      $description = $_POST['description'];
      $time = date("Y-m-d H:i:s");

      if($pd['approvalNotes']=="") $pd['approvalNotes'] = "N/A";

      if ($profile->existsProfile($userId)==false){
         // Create if it isn't exists
         $profile->newProfile($userId, $time);
      }

      // Update profile page details
      if($profile->updateProfile($userId, $department, $position,$interests, $description, $linkedin, $github, $time)){
         //echo "<script>alert('Profile page contents were updated');</script>";
         echo "<script>window.location.href='./approval.php'</script>";

      }else{
         echo "<script>alert('Database Error !');</script>";
      }
      exit;

   }else{
      // On usuall page load

      if ($profile->existsProfile($userId)){
         $pd = $profile->get_profile($userId);

         $deptId = $pd['department'];
         $position = $pd['pos'];
         $interests = $pd['skills'];
         $description = $pd['description'];
         $linkedin = $pd['linkedinProfile'];
         $github = $pd['githubProfile'];
      }
      else {
         $deptId = "";
         $position = "";
         $interests = "";
         $description = "";
         $linkedin = "";
         $refereeId = "";
      }
      $profilePageEnabled = ($users->getUserData($userId, "profilePage"))? "checked" : "";
   }
   ?>
   <div class="w3-row">
      <div class="w3-col s1 m2 l2">&nbsp;</div>
      <div class="w3-col s10 m8 l8">
         <br><br><br><br>

         <ul class="breadcrumb w3-card-2 w3-container w3-margin-8">
            <li><a href="../home">Home</a></li>
            <li class="active">Profile</a></li>
         </ul>

         <br><br>
         <span style="padding:20px;">Enable Profie Page </span>
         <label class="switch">
            <input id="enableProfilePage" type="checkbox" <?php echo $profilePageEnabled ?> onchange="toggleForm()">
            <span class="slider round"></span>
         </label>

         <br><br>
         <?php

         if($profile->existsProfile($userId)){
            $pd = $profile->get_profile($userId);

            echo "<div class='w3-container w3-card-4 w3-padding w3-khaki'>";
            echo "<p class='w3-small'>Status: ".$pd['status']."</p>";
            echo "<p class='w3-small'>Approval Notes: <i>".$pd['approvalNotes']."</i></p>";
            echo "</div>";
         }

         ?>

         <br><br>
         <div id="profilePage">

            <button id="fillIt" type="button">Auto Fill</button>
            <br>
            <form  name="profileForm" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
               <input name="submitted" type="hidden" value="1"/>
               <p class="w3-hide">
                  <label>Name</label>
                  <input type="text" class="w3-input" readonly value="" />
                  <br>
               </p>

               <p>
                  <label>Department</label>
                  <select name="department" id="department" class="w3-select" name="option" required>
                     <option value="" disabled>Choose your department</option>

                     <?php
                     $deptArray = json_decode(file_get_contents("../lists/departments.json"), true);

                     for($i=0;$i<sizeof($deptArray);$i++){
                        $selected = ($deptId == $i) ? "selected" : "";
                        echo "<option value='$i' $selected>".$deptArray[$i]."</option>";
                     }

                     ?>
                  </select>
               </p>

               <p>
                  <label>Position</label>
                  <input class="w3-input w3-border w3-round" placeholder="Ex: Undergraduate"
                  name="position" id="position" type="text" required value="<?php echo $position ?>">
               </p>

               <p>
                  <label>Interests / Skills</label>
                  <input class="w3-input w3-border w3-round" name="interesets" id="interests"
                  type="text" required value="<?php echo $interests ?>">
               </p>

               <p>
                  <label>Description</label>
                  <textarea name="description" rows="7" id="description" class="jqteText"><?php echo $description; ?></textarea>
               </p>

               <p>
                  <label>Linkedin Profile link</label>
                  <input placeholder="https://linkedin.com/in/{username}" class="w3-input w3-border w3-round" name="linkedin" id="linkedin" type="text"
                  value="<?php echo $linkedin ?>">
               </p>
               <p>
                  <label>Github Profile link</label>
                  <input placeholder="https://github.com/{username}" class="w3-input w3-border w3-round" name="github" id="github" type="text"
                  value="<?php echo $github ?>">
               </p>

               <div class="w3-right">
                  <a target="_blank" href="./preview.php" class="w3-button w3-theme-button ">Preview</a>
                  <!--<button type="submit" class="w3-button w3-disabled w3-theme-button" onclick="saveData()">Save Draft</button>-->
                  <button type="submit" class="w3-button w3-theme-button">Next</button>
               </div>

               <br>
               <br>
               <br>
            </form>
         </div>

      </div>
      <div class="w3-col s1 m2 l2">&nbsp;</div>


      <script type="text/javascript">

      $(document).ready(function () {
         $('.jqteText').jqte({formats: false});
         $("#profilePage").toggle($('#enableProfilePage').prop("checked"));

         // Tag separation for skills & interests
         $('input[name="interesets"]').amsifySuggestags({
            type : 'amsify',
         });

         // Enable the profilePage flag on user database
         $('#enableProfilePage').click(function () {
            var chk = $('#enableProfilePage').prop("checked");

            $("#profilePage").toggle(chk);

            $.ajax({
               type: "POST",
               url: "./actions.php?act=profile&enable="+chk,
               dataType: "json",
               timeout: 2000,
               success: function (data) {
                  //$("#profilePage").toggleClass(this.checked);

               },
               error: function (request, status, err) {
                  alert("Sorry, an error occurred !")
                  console.log(">>"+ request + " " + status + " " + err);
               }
            });
         });

         $('#fillIt').click(function () {
            $("#department").val("3");
            $("#position").val("Undergraduate");
            $("#interests").val("Algorathm Programming, Web Developing, Team Works");
            $("#description").val("");
            $("#linkedin").val("https://www.linkedin.com/in/dilshani-karunarathna-b81824161/");
            $("#github").val("https://github.com/DDilshani/");

         });
      });
      </script>

   </body>
   </html>
