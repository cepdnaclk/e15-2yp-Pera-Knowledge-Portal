<?php include '../data/session.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
   <?php include '../data/meta.php'; ?>
   <?php include '../data/scripts.php'; ?>

   <style>
      .error {color: red;font-size: small;}
      .tabBody {display: none;}
      label {padding-top: 10px !important;}
   </style>

</head>
<body>

   <a name="top"></a>
   <?php include '../data/navibar.php'; ?>

   <?php
   include_once "../data/database.php";
   include_once "../data/classes/userClass.php";

   $db = new database();
   $users = new userClass($db);

   ?>


   <?php

   $id = $userId;
   $firstName = $users->getUserData($id, "firstName");
   $lastName = $users->getUserData($id, "lastName");
   $salutation = $users->getUserData($id, "honorific");
   $email = $users->getUserData($id, "email");
   $role = $users->getUserData($id, "role");

   ?>

   <div class="w3-container">
      <div class="w3-row">
         <div class="w3-col m2 l2 hidden-sm">&nbsp;</div>
         <div class="w3-col s12 m8 l8">
            <br><br><br><br>

            <ul class="breadcrumb">
               <li><a href="../home">Home</a></li>
               <li class="active">Settings</a></li>
            </ul>

            <br>

            <ul class="w3-navbar w3-theme-l2">
               <li><a href="#" class="tablink w3-theme" onclick="openTab(event, 'GeneralTab');">General</a></li>
               <li><a href="#" class="tablink" onclick="openTab(event, 'Password');">Password</a></li>
            </ul>


            <div>
               <div id="GeneralTab" class="w3-container tabBody" style="display: block;">

                  <form name="newStudent" role="form" class="w3-container w3-card-4 w3-light-grey w3-padding-16 w3-margin-8" method="post" action="actions.php?act=update">

                     <h2>Edit User</h2>
                     <br>

                     <p><input name="userId" type="hidden" value="<?php echo $id; ?>"></p>

                     <p>
                        <label>Salutation</label>
                        <select class="w3-select w3-border w3-round" name="salutation" required>
                           <option value="" disabled>Select the Salutation</option>
                           <?php
                           $list = json_decode(file_get_contents("../lists/salutations.json"), true);

                           for ($i = 0; $i < sizeof($list); $i++) {
                              $sel = ($i == $salutation) ? "selected" : "";
                              echo "<option value='$i' $sel >$list[$i]</option>";
                           }
                           ?>
                        </select>
                     </p>
                     <p>
                        <label>First Name (with initials)</label>
                        <input class="w3-input w3-border w3-round" name="firstName" type="text" required
                        value="<?php echo $firstName; ?>"/>
                     </p>

                     <p>
                        <label>Last Name</label>
                        <input class="w3-input w3-border w3-round" name="lastName" type="text" required
                        value="<?php echo $lastName; ?>"/>
                     </p>

                     <p>
                        <button type="submit" class="w3-btn w3-theme w3-round">Update User Settings</button>
                     </p>

                  </form>

               </div>

               <div id="Password" class="w3-container tabBody">
                  <form name="newPassword" role="form" class="w3-container w3-card-4 w3-light-grey w3-padding-16 w3-margin-8"
                  method="post" action="actions.php?act=update">

                  <h2>Change Password</h2>
                  <br>

                  <p><input name="userId" type="hidden" value="<?php echo $id; ?>"></p>


                  <p>
                     <label>Current Password</label>
                     <input class="w3-input w3-border w3-round" name="currentPassword" type="text" required>
                  </p>

                  <p>
                     <label>New Password</label>
                     <input class="w3-input w3-border w3-round" name="newPassword" type="text" required>
                  </p>

                  <p>
                     <label>Confirm New Password</label>
                     <input class="w3-input w3-border w3-round" name="confirmPassword" type="text" required>
                  </p>

                  <p>
                     <button type="submit" class="w3-btn w3-theme w3-round">Update Password</button>
                  </p>

               </form>
            </div>
         </div>

         <br><br><br><br>

      </div>
   </div>
</div>


<script>
function openTab(evt, name) {
   var i, x, tablinks;
   x = document.getElementsByClassName("tabBody");
   for (i = 0; i < x.length; i++) {
      x[i].style.display = "none";
   }
   tablinks = document.getElementsByClassName("tablink");
   for (i = 0; i < x.length; i++) {
      tablinks[i].className = tablinks[i].className.replace(" w3-red", "");
   }
   document.getElementById(name).style.display = "block";
   evt.currentTarget.className += " w3-red";
}
</script>

<script>
$(document).ready(function () {
   $("#er-close").click(function () {
      window.location = "index.php";
   });
});
</script>
</body>
</html>
