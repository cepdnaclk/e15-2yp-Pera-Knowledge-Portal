<!DOCTYPE html>
<html>
<head>
   <meta name="viewport" content="width=device-width, initial-scale=1">

   <link rel="stylesheet" href="../css/w3.css">
   <link rel="stylesheet" href="../css/w3-theme.css">
   <link rel="stylesheet" href="../css/font-awesome.min.css">
   <link rel="stylesheet" href="../css/index.css">
   <link rel="shortcut icon" href="./../img/fav.ico">

   <title>UoP Knowledge Portal</title>

   <script type="application/javascript" src="../js/jquery.min.js"></script>
   <script type="application/javascript" src="../js/index.js"></script>

   <style>
   ul.footer-menu li {display: inline;margin: 0 5px;}
   .spanTag{color:#4483bd;}
   </style>

</head>
</html>
<body>

   <?php
   include '../data/navibar.php';
   ?>

   <br>
   <br>
   <br>

   <div class="w3-container">
      <div class="w3-row">
         <!-- Side bar -------------------------------------------------------->
         <div class="w3-col m4 l3 w3-padding-8 w3-hide-small">

            <div class="w3-container w3-hide-small">
               <br>

               <div class="w3-accordion w3-light-gray w3-card-4">
                  <button onclick="accordianFunction('divTags')"
                  class="w3-btn-block w3-left-align w3-theme-d1 w3-theme-button">
                  Related tags</button>
                  <div class="divTags w3-accordion-content"></div>
               </div>
               <br>
               <div class="w3-accordion w3-light-gray w3-card-4">
                  <button onclick="accordianFunction('divAdvisers')" class="w3-btn-block w3-left-align w3-theme-d1 w3-theme-button">Advisers</button>
                  <div class="divAdvisers w3-accordion-content"></div>
               </div>
            </div>
         </div>
         <!-- Center ---------------------------------------------------------->
         <div class="w3-col s12 m8 l9">
            <div class="searchBox" style="max-width:600px; padding: 15px 10px 15px 10px;">
               <input type="text" placeholder="Search.." name="search" id="keyword" onchange="search();">
               <button onclick="search();" type="button" id="button"><i class="fa fa-search"></i></button>
            </div>

            <div id="resultNotes" class="w3-container w3-padding-12"></div>

            <div class="w3-container" id="result" style="min-height:80vh;">
               <div class="w3-bar w3-center w3-hide-small w3-hide">
                  <a href="#" class="w3-button w3-hover-black">&laquo;</a>
                  <a href="#" class="w3-button w3-hover-black">1</a>
                  <a href="#" class="w3-button w3-hover-black">2</a>
                  <a href="#" class="w3-button w3-hover-black">3</a>
                  <a href="#" class="w3-button w3-hover-black">4</a>
                  <a href="#" class="w3-button w3-hover-black">&raquo;</a>
               </div>
            </div>

            <div class="w3-bar w3-center w3-hide-medium w3-hide-large w3-hide-medium">
               <a href="#" class="w3-button w3-hover-black">&laquo;</a>
               <a href="#" class="w3-button w3-hover-black">1</a>
               <a href="#" class="w3-button w3-hover-black">2</a>
               <a href="#" class="w3-button w3-hover-black">3</a>
               <a href="#" class="w3-button w3-hover-black">4</a>
               <a href="#" class="w3-button w3-hover-black">&raquo;</a>
            </div>

            <div class="w3-container w3-hide-medium w3-hide-large">
               <div class="w3-accordion w3-gray">
                  <button onclick="accordianFunction('divTags')" class="w3-btn-block w3-left-align w3-theme-d1">
                     Related tags
                  </button>
                  <div class="divTags w3-accordion-content">
                  </div>
               </div>
               <br>
               <div class="w3-accordion w3-gray">
                  <button onclick="accordianFunction('divAdvisers')" class="w3-btn-block w3-left-align w3-theme-d1">
                     Advisers
                  </button>
                  <div class="divAdvisers w3-accordion-content">
                  </div>
               </div>
            </div>
            <br>

         </div>

      </div>
   </div>

   <!--
   <footer class="w3-container w3-padding-32 w3-black">
   <div class="w3-row-padding">
   <div class="w3-third">
   <h3 class="w3-text-yellow">Quick Links</h3>

   <ul class="">
   <li><a target="_blank" href="http://pdn.ac.lk">University of Peradeniya</a></li>
   <li><a target="_blank" href="http://eng.pdn.ac.lk/">Faculty of Engineering</a></li>

</ul>

</div>

<div class="w3-third">
<h3 class="w3-text-yellow">Contact Us</h3>

<p>Department of Computer Engineering<br>
Faculty of Engineering<br>
University of Peradeniya
</p>
</div>
<div class="w3-third">
<h3 class="w3-text-yellow">Pages</h3>

<ul class="">
<li><a target="_blank" href="../pages/support">Support</a></li>
<li><a target="_blank" href="../pages/faq">FAQs</a></li>
<li><a target="_blank" href="../pages/terms">Terms & Conditions</a></li>
<li><a target="_blank" href="../pages/privacy">Privacy Policy</a></li>

</ul>

<p class="w3-hide">
<span class="w3-tag w3-black w3-margin-bottom">Travel</span> <span
class="w3-tag w3-grey w3-small w3-margin-bottom">New York</span> <span
class="w3-tag w3-grey w3-small w3-margin-bottom">London</span>
</p>
</div>

</div>
</footer>
-->

<script>
function accordianFunction(id) {
   $("." + id).toggleClass("w3-show");
}

$.urlParam = function (name) {
   var results = new RegExp('[\?&]' + name + '=([^&#]*)').exec(window.location.href);
   if (results == null) {
      return null;
   }
   return decodeURI(results[1]) || 0;
}

$(document).ready(function () {
   $("#keyword").val($.urlParam('search'));
   search();
});

function search() {

   keyword = $("#keyword").val();
   if (keyword != "") {

      $.ajax({
         type: "GET",
         url: './action.php?keyword=' + keyword,
         dataType: "html",
         timeout: 5000,
         success: function (data) {

            var inData = JSON.parse(data);
            var tagText = "";

            if (inData.statusCode == "S1000") {
               $("#resultNotes").html(inData.reply);
               $("#result").html("");

               // Generate search results
               $.each(inData.result, function (key, val) {

                  var docId = val.documentId;
                  var title = val.title;
                  var advisers = val.advisers;
                  var authors = val.authors;
                  var score = val.score;
                  var tags = val.tags;
                  var tagText = "";

                  $.each(val.tags, function (key, val) {
                     tagText  += "<a href='../docs/"+ val + "'>#"+ val+"</a>&nbsp;&nbsp;";
                  });

                  txt = "<div class='w3-row w3-card-2 w3-animate-opacity' style='margin-bottom: 10px!important;'>";
                  txt += "<div class='w3-col w3-padding-8' style='width: 84px;'><img class='w3-margin-8' style='width: 32px;' src='../img/pdf.png'></div>";
                  txt += "<div class='w3-rest'><p><span class=''><b><a href='../view/" + docId + "' style='text-decoration: none;'>";
                  txt += title + "</b></a></span><br><span class='spanTag'>"+ tagText+"</span> | Score: " + score + "</p></div></div>";

                  $("#result").append(txt);
               });

               // Generate Tag results
               //console.log(inData.tags);
               //console.log(inData.tags.length);

               //if (inData.tags.length > 0) {
               $(".divTags").addClass("w3-show").html("");
               $.each(inData.tags, function (key, val) {
                  if(val > 1){
                     var txt = "<a href='../docs/" + key + "'>" + key + "</a>";
                     $(".divTags").append(txt);
                  }
                  //console.log(key + " " + val);

               });
               /*} else {
               var txt = "<a href='#'>No any related tag</a><br>";
               $(".divTags").html(txt);
            }*/

            // Generate Adviseer results
            //if (inData.advisers.length > 0) {
            $(".divAdvisers").addClass("w3-show").html("");
            $.each(inData.advisers, function (key, val) {
               //if(val > 1){
               if(key != ""){
                  var txt = "<a href='./?search=" + key + "'>" + key + "</a>";
                  $(".divAdvisers").append(txt);
               }
               //}
               //console.log(key + " " + val);
            });
            /*} else {
            var txt = "<a href='#'>No any related tag</a><br>";
            $(".divAdvisers").html(txt);
         }*/

         //$("#advisers").append(inData.advisers.toString());
         $("#authors").append(inData.authors.toString());
      }
   },
   error: function (request, status, err) {
      alert("Error occurred !");

      $("#result").html("<p>Sorry, an error occurred. Please try again later.</p>");
      console.log(request + " " + status + " " + err);
   }
});

}
return false;
}
</script>

</body>
</html>
