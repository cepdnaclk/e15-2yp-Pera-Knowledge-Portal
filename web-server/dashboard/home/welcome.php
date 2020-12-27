<?php include '../data/session.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
   <?php include '../data/meta.php'; ?>
   <?php include '../data/scripts.php'; ?>

   <style>
   label {
      margin: 5px !important;
   }
   </style>
</head>
<body>

   <a name="top"></a>
   <?php include '../data/navibar.php'; ?>

   <?php

   define("FOLDER_NAME", "public");
   include_once "../data/accessControl.php";

   include_once "../data/database.php";
   $db = new database();

   // Check the user is new or not
   if ($db->getUserData($userId, "honorific") != 9) {
      // Existing user
      header("location: index.php");
   }

   // After form data submission
   if (isset($_POST['submitted'])) {

      $id = $userId;
      $firstName = $_POST['firstName'];
      $lastName = $_POST['lastName'];
      $salutation = $_POST['salutation'];

      $res = 0;
      $res += $db->setUserData($id, "firstName", $firstName);
      $res += $db->setUserData($id, "lastName", $lastName);
      $res += $db->setUserData($id, "honorific", $salutation);

      print_r($_POST);

      if ($res == 3) {

         $sal = json_decode(file_get_contents("../lists/salutations.json"), true);
         $userNameString = $sal[$db->getUserData($userId, "salutation")] . " " . $db->getUserData($userId, "firstName") . " " . $db->getUserData($userId, "lastName");
         $userEmailString = $db->getUserData($userId, "email");

         $_SESSION['userNameString'] = $userNameString;
         $_SESSION['userEmailString'] = $userEmailString;

         header("location: index.php");

      } else {
         echo "<script>alert('Sorry, an unknown error occurred !');</script>";
         echo "<script>history.go(-1);</script>";
      }
      exit;
   }



   ?>

   <div class="w3-row">
      <div class="w3-col m2 l2 hidden-sm">&nbsp;</div>
      <div class="w3-col s12 m8 l8">
         <br><br><br><br>

         <ul class="breadcrumb w3-card-2 w3-container w3-margin-8">
            <li><a href="../home">Home</a></li>
            <li class="active">Welcome</a></li>
         </ul>

         <br>

         <div>
            <?php

            $id = $userId;

            $firstName = $db->getUserData($id, "firstName");
            $lastName = $db->getUserData($id, "lastName");
            $salutation = $db->getUserData($id, "salutation");
            $email = $db->getUserData($id, "email");
            $role = $db->getUserData($id, "role");

            $eNum = "";
            $dept = "";

            ?>

            <div id="welcomeScreen" class="w3-container w3-card-4 w3-light-grey w3-padding-16 w3-margin-8 w3-center">
               <h3>Welcome to Pera Knowledge Hub !</h3>

               <img src="../img/pera.png" class="w3-responsive">
               <br>
               <button id="btnContinue" type="submit" class="w3-btn w3-theme w3-round w3-right">Continue</button>

            </div>

            <div id="dataScreen" class="w3-container w3-card-4 w3-light-grey w3-padding-16 w3-margin-8"
            style="display:none;">
            <button id="fillIt" type="button">Auto Fill</button>

            <h4>Please Fill Following:</h4>

            <form name="newStudent" role="form"
            method="post" action="welcome.php?act=update">
            <input type="hidden" name="submitted" id="submitted" value="1">

            <p><input name="userId" type="hidden" value="<?php echo $id; ?>"></p>

            <p>
               <label>Salutation</label>
               <select class="w3-select w3-border w3-round" id="salutation" name="salutation" required>
                  <option value="" selected disabled>Select the Salutation</option>
                  <?php
                  $list = json_decode(file_get_contents("../lists/salutations.json"), true);

                  for ($i = 0; $i < sizeof($list); $i++) {
                     //$sel = ($i == $salutation) ? "selected" : "";
                     echo "<option value='$i' $sel >$list[$i]</option>";
                  }
                  ?>
               </select>

               <p>
                  <label>First Name (initials)</label>
                  <input class="w3-input w3-border w3-round" name="firstName" id="firstName" type="text" required
                  value="<?php echo $firstName; ?>"/></p>

                  <p>
                     <label>Last Name</label>
                     <input class="w3-input w3-border w3-round" name="lastName" id="lastName" type="text" required
                     value="<?php echo $lastName; ?>"/></p>

                     <p>
                        <button type="submit" class="w3-btn w3-theme w3-round">Update and Continue</button>
                     </p>

                  </form>
               </div>
            </div>

            <br><br><br><br>

         </div>

      </div>


      <script>
      $(document).ready(function () {

         $('#fillIt').click(function () {
            $("#salutation").val("2");
            $("#firstName").val("S.D.D.D.");
            $("#lastName").val("Karunarathne");

         });

         $("#btnContinue").click(function () {

            $("#welcomeScreen").hide();
            $("#dataScreen").fadeIn();

         })
      });
   </script>

</body>
</html>
