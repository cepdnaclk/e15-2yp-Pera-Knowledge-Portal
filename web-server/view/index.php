<?php

include '../dashboard/data/database.php';
include '../dashboard/data/classes/userClass.php';
include '../dashboard/data/classes/profileClass.php';

if(isset($_GET['page'])){

   // TODO: exit if the doc isn't exists
   $db = new database();
   $users = new userClass($db);
   $profile = new profileClass($db);

   $docId = $_GET['page'];
   $url = "../uploads/$docId.pdf";

   $data = $db->getDoc($docId);

   $title = $data["docTitle"];

   $description = $db->getDocData($docId, "docNotes");
   $docTags =  array_map('trim', explode(",", $data['docTags']));

   $arrayAuthors = array_map('trim', explode(",", $db->getRelation($docId, "AUTHOR")));
   $arrayAdvisers = array_map('trim', explode(",", $db->getRelation($docId, "ADVISER")));

}else{
   include_once "../403.shtml";
   exit;
}

?>

<!DOCTYPE html>
<html>
<head>
   <title><?php echo $title; ?> | Pera Knowledge Portal</title>
   <meta name="viewport" content="width=device-width, initial-scale=1">
   <meta name="description" content="">
   <meta name="keywords" content="">
   <meta name="author" content="">

   <link rel="stylesheet" href="../css/w3.css">
   <link rel="stylesheet" href="../css/w3-theme.css">
   <link rel="stylesheet" href="../css/index.css">
   <link rel="stylesheet" href="../css/font-awesome.min.css">
   <link rel="stylesheet" href="../dashboard/css/profile.css">

   <link rel="shortcut icon" href="../img/fav.ico">

   <script src="../js/jquery.min.js"></script>

   <style>
   a {
      color: #0b4c90!important;
      text-decoration: none!important;
   }
   html {scroll-behavior: smooth;}
   </style>
</head>

<body>

   <?php include '../data/navibar.php'; ?>
   <br><br><br>

   <div class="w3-container" style="padding: 0!important; margin: 0!important">
      <h2 class="w3-container w3-center"><?php echo $title; ?></h2>

      <div class="w3-row">
         <div class="w3-col s1 m2 l3">&nbsp;</div>
         <div class="w3-col s10 m8 l6 w3-padding-8" style="min-height:80vh;">

            <div class="w3-center">
               <?php echo $description; ?>
               <br><br>
               <div class="w3-right">
                  <a href="#document" class='w3-button w3-theme-button'>View More</a>
               </div>
            </div>
            <br />
            <br />
            <?php

            echo "<div><h4>Authors:</h4>";
            for ($i=0;$i<sizeof($arrayAuthors);$i++){
               $usersProfile = $users->getUserId_byName($arrayAuthors[$i]);

               if($usersProfile!=0 && $profile->existsActiveProfile($usersProfile)){
                  // User has a profile page
                  $href = "../profile/$usersProfile";
                  echo "<span class='w3-tag w3-white'><a href='$href'>" . $arrayAuthors[$i] ."</a></span>";
               }else{
                  echo "<span class='w3-tag w3-white'>".$arrayAuthors[$i]."</span>";
               }

            }
            echo "</div><br>";

            echo "<div><h4>Advisers:</h4>";
            for ($i=0;$i<sizeof($arrayAdvisers);$i++){
               echo "<span class='w3-tag w3-white'><a href='#'>" . $arrayAdvisers[$i] . "</a></span>";
            }
            echo "</div><br>";

            echo "<div><h4>Related Tags:</h4>";
            for ($i=0;$i<sizeof($docTags);$i++){
               echo "<span class='w3-tag w3-white'><a href='../docs/$docTags[$i]'>" . $docTags[$i] . "</a></span>";
            }
            echo "</div><br>";

            ?>

         </div>
         <div class="w3-col s1 m2 l3">&nbsp;</div>

      </div>

      <center>
         <div class="">
            <a name="document" class="document"></a>
            <iframe class="pdfif" name="document" src="<?php echo $url;?>" title="DOC Title" scrolling="no" border="none">
               Please use a browser which supports iframe feature in order to view this PDF.
            </iframe>
         </div>
      </center>
   </div>

   <div class="w3-container w3-hide w3-bottom w3-text-gray">
      <p>&copy; 2019 University of Peradeniya. All Rights Reserved <br class="w3-hide-large w3-hide-medium">
         <span class="w3-right">
            <a href="#" target="_blank">Privacy Policy</a> |
            <a href="#" target="_blank">Terms</a> |
            <a href="#" target="_blank">FAQ</a>
            &nbsp;&nbsp;&nbsp;&nbsp;</span>
         </p>
      </div>
   </body>

   <script type="text/javascript">
   $(document).ready(function(){
      // NOTE: A bug found in mobile view.

      $(".pdfif").height($(window).height() - $("#navBar").height() - 5);
      $(".pdfif").width($(window).width());

      $(window).resize(function(){
         $(".pdfif").height($(window).height() - $("#navBar").height() - 5);
         $(".pdfif").width($(window).width());
      });

   });

   /*window.scroll({
      top: 2500,
      left: 0,
      behavior: 'smooth'
   });*/

   // Scroll certain amounts from current position
   /*window.scrollBy({
      top: 100, // could be negative value
      left: 0,
      behavior: 'smooth'
   });*/

   // Scroll to a certain element
   /*document.querySelector('.document').scrollIntoView({
      behavior: 'smooth'
   });*/
</script>
</html>
