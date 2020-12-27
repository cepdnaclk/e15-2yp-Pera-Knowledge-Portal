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
   include_once "../data/classes/userClass.php";

   $db = new database();
   $profile = new profileClass($db);
   $users = new userClass($db);

   if ($db->existsProfile($userId)) {

      if(isset($_POST['submitted'])){

         $referee = $_POST['referee'];
         $approvalNotes = $_POST['approvalNotes'];

         $resp = $profile->update_profileData($userId, "referee", $referee);
         $resp += $profile->update_profileData($userId, "approvalNotes", $approvalNotes);

         if($resp ==2 ){
            $profile->update_profileData($userId, "status", "PENDING");
            //echo "<script>alert('Success !');</script>";
            echo "<script>window.location.href='../home/'</script>";

         }else{
            echo "<script>alert('Database Error !');</script>";
         }
         exit;

      }else{
         include '../data/navibar.php';

         $referee = $profile->get_profileRequestData($userId, "referee");
         $approvalNotes = $profile->get_profileRequestData($userId, "approvalNotes");
      }

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
             <li class="active">Approval</a></li>
         </ul>

         <div class="w3-container">
            <br><br>

            <form  name="approvalForm" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
               <input name="submitted" type="hidden" value="1"/>
               <p>
                  <label><b>Please select a referee for you.</b>
                     <br>
                     <small>He or she will approve your changes after revise what you have submitted.</small>
                  </label>
                  <select name="referee" id="referee" class="w3-select" name="option">
                     <option value="" disabled>Choose your option</option>
                        <option $select value='100000'>A.J.N.M. Jaliyagoda</option>
                     <?php
                     $lecturers = $users->listLecturers();

                     for($i=0;$i<sizeof($lecturers);$i++){
                        $lecId = $lecturers[$i]['id'];
                        $lecName = $users->getName_byUserId($lecId);
                        $select = ($referee == $lecId) ? "selected" : "" ;
                        echo "<option $select value='$lecId'>".$lecName."</option>";
                     }
                     ?>

                  </select>

                  <br>
                  <br>
               </p>
               <p>
                  <label><b>Message</b>
                     <br>
                     <small>This message is not visible at the site. It is only sent to the above selected recipient
                        via an e-mail
                     </small>
                  </label>
                  <br>
                  <br>
                  <textarea class="w3-input w3-border w3-round" rows="3" name="approvalNotes" id="approvalNotes"><?php echo $approvalNotes ?></textarea>
               </p>
               <div class="w3-right">
                  <a target="_blank" href="./preview.php" class="w3-button w3-theme-button">Preview</a>
                  <button type="submit" class="w3-button w3-theme-button">Submit for Approval</button>
               </div>
               <br>

            </form>
         </div>
      </div>
      <div class="w3-col s1 m2 l3">&nbsp;</div>

   </body>
   </html>
