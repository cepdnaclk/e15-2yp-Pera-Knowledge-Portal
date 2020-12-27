<?php include '../data/session.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
   <?php include '../data/meta.php'; ?>
   <?php include '../data/scripts.php'; ?>

   <link href="../css/profile.css" rel="stylesheet"/>

</head>
<body>

   <a name="top"></a>
   <?php include '../data/navibar.php'; ?>

   <?php

   define("FOLDER_NAME", "profileAdmin");
   include_once "../data/accessControl.php";

   include_once "../data/database.php";
   $db = new database();

   if (!isset($_GET['id'])) {
      include_once '../403.shtml';
      exit;
   }
   $profileId = $_GET['id'];
   ?>

   <div class="w3-container">
      <div class="w3-row">
         <div class="w3-col m2 l2 hidden-sm">&nbsp;</div>
         <div class="w3-col s12 m8 l8">
            <br><br><br><br>

            <ul class="breadcrumb w3-card-2 w3-container w3-margin-8">
               <li><a href="../home">Home</a></li>
               <li><a href="../profileAdmin">Profile Manager</a></li>
               <li class="active">View</a></li>
               <li class="active"><?php echo $profileId ?></a></li>
            </ul>

            <br>

            <div class="w3-container">
               <?php

               if ($db->existsProfile($profileId)) {

                  $data = $db->getProfile($profileId);
                  $approvalNote = $data['approvalNotes'];
                  $profileName =$db->getUserData($profileId, "firstName") . " " . $db->getUserData($profileId, "lastName");

               } else {
                  echo "<h3>Profile Id not exists</h3>";
               }

               ?>
            </div>

            <div class="w3-panel w3-card-4">

               <?php
               if($approvalNote != ""){
                  echo "<p>Message from $profileName</p>";
                  echo "<div class='w3-container w3-text-gray' style='/*border:1px solid gray;*/padding:12px;'>$approvalNote";
                  echo "</div><br>";
               }
               ?>

               <div class="w3-container">
                  <button onclick="document.getElementById('modalReject').style.display='block'"
                  class="w3-button w3-theme-button w3-right">Reject
               </button>
               <a href="./actions.php?act=approve&profileId=<?php echo $profileId ?>" type="button" class="w3-button w3-theme-button w3-right">Approve</a>
            </div>
            <br>

         </div>

         <div class="w3-panel ">
            <h3 class="w3-container w3-center"><b>Preview</b></h3>

            <div id="previewDiv"></div>
         </div>
         <br>
         <br>


      </div>
   </div>


   <br><br><br>
</div>

<div class="w3-container">
   <div id="modalReject" class="w3-modal">
      <div class="w3-modal-content w3-card-4">
         <header class="w3-container w3-theme">
            <h5>Reject Message</h5>
         </header>
         <div class="w3-container">
            <br>
            <lable>Please enter the reason to reject the request</lable>
            <textarea class="w3-input w3-border w3-round" rows="3" name="rejectMsg" id="rejectMsg" type="text" required></textarea>
            <br>

            <div class="w3-right">
               <button onclick="closeModal()" type="button" class="w3-button">Cancel</button>
               <a href="./actions.php?act=reject&profileId=<?php echo $profileId ?>" type="button" class="w3-button w3-theme-button">Send</a>

               </div>
               <br>
            </div>
         </div>
      </div>
   </div>

   <script>

   $(document).ready()
   {
      loadPreview();

      setTimeout( function(){
         closeModal();
      }, 5000);

   }
   function closeModal(){
      document.getElementById('modalReject').style.display='none';
   }
   function loadPreview() {
      $("#previewDiv").load("../profile/previewBuilder.php?userId=<?php echo $profileId; ?>"); // ,data,callback
   }

</script>

</body>
</html>
