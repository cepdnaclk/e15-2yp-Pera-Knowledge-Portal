<!DOCTYPE html>
<html>
<head>
    <title>Pera Knowledge Portal</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="stylesheet" href="css/w3.css">
    <link rel="stylesheet" href="css/w3-theme.css">
    <link rel="stylesheet" href="css/index.css">
    <link rel="stylesheet" href="css/font-awesome.min.css">
    <link rel="shortcut icon" href="img/fav.ico">

</head>

<body>

   <div class="w3-display-container w3-top w3-card-2">
       <div class="w3-bar w3-theme w3-xlarge">
           <a href="../" class="w3-bar-item w3-button w3-hover-theme w3-theme-button">
               <i class="fa fa-home"></i></a>
           <span class="w3-bar-item w3-large w3-padding-12 w3-hide-small">UoP Knowledge Portal</span>

           <div class="w3-right w3-dropdown-click">
               <div id="userPanel" class="w3-dropdown-content  w3-white w3-card-4 w3-right w3-center w3-medium"
                    style="right:0!important;top:52px!important; width: 350px;">

                   <div class="w3-container">
                       <div class="w3-row">
                           <div class="w3-col s4">
                               <?php

                               if ($_SESSION['userImage'] != "") {
                                   echo "<img src='" . $_SESSION['userImage'] . "'class='w3-circle w3-card-4 w3-margin-8' style='width: 84px; height: 84px;'>";
                               } else {
                                   echo "<img src='../img/userIcon.png' class='w3-responsive w3-padding-12' style='width: 80%;'>";
                               }
                               ?>
                           </div>
                           <div class="w3-col s8 w3-padding-16 w3-left-align">
                               <p class="w3-medium"><?php echo $_SESSION['userNameString'] ?>
                                   <span
                                       class="w3-small w3-text-gray"><?php echo $_SESSION['userEmailString'] ?></span><br>
                               </p>

                               <p>
                                   <a href="../settings/" class="app-link">Settings</a>
                                   <a href="../messages/" class="app-link">Messages</a>
                               </p>
                           </div>
                       </div>
                   </div>

                   <div class="w3-padding-0 w3-btn-group w3-center w3-light-gray">
                       <a href="../settings/" class="w3-btn w3-white w3-border w3-margin-8 w3-hide"
                          style="width: 30%; box-shadow:0 0 0 0!important;">
                           <i class="fa fa-gear" style="font-size:24px;"></i></a>

                       <a href="../login/logout" class="w3-btn w3-white w3-border w3-margin-8 w3-right"
                          style="width: 40%; box-shadow:0 0 0 0!important;">
                           <i class="fa fa-sign-out" style="font-size:24px;"></i> Logout
                       </a>
                   </div>
               </div>
           </div>

           <a href="./dashboard/login/" id="userPanelBtn"
              class="w3-bar-item w3-button w3-theme-button w3-hover-theme w3-animate-opacity w3-right"
              style="margin-right: 25px!important; border: 1px solid #000000l;" onclick="toggleUserPanel()"><i
                   class="fa fa-user"></i></a>
           <div class="w3-clear"></div>
       </div>
   </div>


<div class="w3-bar w3-theme w3-xlarge w3-hide">
    <a href="./" class="w3-bar-item w3-button w3-hover-theme w3-theme-button">
        <i class="fa fa-home"></i></a>
    <span class="w3-bar-item w3-large w3-padding-12 w3-hide-small">UoP Knowledge Portal</span>
    <span class="w3-bar-item w3-large w3-padding-12 w3-small w3-hide-medium w3-hide-large">UoP Knowledge Portal</span>

    <a href="./dashboard/login" id="userPanelBtn"
       class="w3-bar-item w3-button w3-hover-theme w3-theme-button w3-animate-opacity w3-right"
       style="margin-right: 25px!important; border: 1px solid #000000l;" onclick="toggleUserPanel()"><i
            class="fa fa-user"></i></a>
</div>

<br>
<br>
<br>

<div class="w3-row">
    <div class="w3-col s0 m3 l4">&nbsp;
    </div>

    <div class="w3-col s12 m6 l4">
        <div class="w3-center w3-animate-opacity">
            <img class="w3-responsive w3-image" src="img/pera.png" style="width: 180px;">

            <h2>UoP Knowledge Portal</h2>

            <form class="example" action="./search/" method="get">
                <div class="w3-container searchBox">
                    <input class="" type="text" placeholder="Search.." name="search">
                    <button type="submit"><i class="fa fa-search"></i></button>
                </div>
            </form>

            <br>

            <div class="w3-row w3-hide">
                <div class="w3-third">
                    <input class="w3-radio" type="radio" name="gender" value="0" checked>
                    <label class="w3-validate">Research Paper</label>
                </div>

                <div class="w3-third">
                    <input class="w3-radio" type="radio" name="gender" value="1">
                    <label class="w3-validate">Thesis</label>
                </div>

                <div class="w3-third">
                    <input class="w3-radio" type="radio" name="gender" value="2" disabled>
                    <label class="w3-validate">Articles</label>
                </div>

            </div>

        </div>
    </div>

    <div class="w3-col s0 m3 l4">&nbsp;</div>

</div>

<div class="w3-container w3-bottom w3-text-gray">
    <p>&copy; 2019 University of Peradeniya. All Rights Reserved <br class="w3-hide-large w3-hide-medium">
        <span class="w3-right">
        <a href="./pages/privacy-policy" target="_blank">Privacy Policy</a> |
        <a href="./pages/terms" target="_blank">Terms</a> |
        <a href="./pages/faq" target="_blank">FAQ</a>
        &nbsp;&nbsp;&nbsp;&nbsp;</span>
    </p>
</div>
</body>
</html>
