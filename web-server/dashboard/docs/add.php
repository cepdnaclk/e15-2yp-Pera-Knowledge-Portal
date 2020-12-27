<?php include '../data/session.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
   <?php include '../data/meta.php'; ?>
   <?php include '../data/scripts.php'; ?>

   <link href="../css/jquery-te-1.4.0.css" rel="stylesheet"/>
   <link href="../css/amsify.suggestags.css" rel="stylesheet" type="text/css">
   <link href="../css/documents.css" rel="stylesheet"/>

   <script type="application/javascript" src="../js/jquery-te-1.4.0.min.js"></script>
   <script type="text/javascript" src="../js/jquery.amsify.suggestags.js"></script>

   <style>

   label {
      margin: 5px !important;
   }

   #drop_file_zone {
      background-color: #EEE;
      border: #999 4px dashed;
      width: 360px;
      height: 140px;
      padding: 8px;
      font-size: 14px;
   }

   #drag_upload_file {
      width: 50%;
      margin: 0 auto;
   }

   #drag_upload_file p {
      text-align: center;
   }

   #drag_upload_file #docPDF {
      display: none;
   }

   </style>
</head>
<body>

   <a name="top"></a>
   <?php include '../data/navibar.php'; ?>

   <?php
   define("FOLDER_NAME", "docs");
   include_once "../data/accessControl.php";

   include_once "../data/database.php";
   $db = new database();
   $salutation = json_decode(file_get_contents("../lists/salutations.json"), true);
   ?>

   <div class="w3-row">
      <div class="w3-col m2 l2 hidden-sm">&nbsp;</div>
      <div class="w3-col s12 m8 l8">
         <br><br><br><br>

         <ul class="breadcrumb w3-card-2 w3-container w3-margin-8">
            <li><a href="../home">Home</a></li>
            <li><a href="../docs/">Documents</a></li>
            <li class="active">Add New Document</a></li>
         </ul>

         <br>

         <div>
            <form name="newDoc" class="w3-container w3-card-4 w3-light-grey w3-padding-16 w3-margin-8"
            method="post" action="actions.php?act=new" enctype="multipart/form-data">

            <h2>New Document</h2>
            <br>
            <button id="fillIt" type="button">Auto Fill</button>

            <p>
               <label>Title</label>
               <input class="w3-input w3-border w3-round" name="docTitle" id="docTitle" type="text" required>
            </p>

            <p>
               <label>Type</label>
               <select class="w3-select w3-border w3-round" name="docType" id="docType" required>
                  <option value="" disabled selected>Select the Document Type</option>

                  <?php
                  $docTypeArray = json_decode(file_get_contents("../lists/docTypes.json"), true);

                  for($i=0;$i<sizeof($docTypeArray);$i++){
                     if($i>2){
                        echo "<option value='$i' disabled>".$docTypeArray[$i]['name']."</option>";
                     }else{
                        echo "<option value='$i'>".$docTypeArray[$i]['name']."</option>";
                     }
                  }
                  ?>
               </select>
            </p>


            <p>
               <label>Author(s) <small class="w3-text-gray">Ex: Perera A.B.C.</small></label>
               <input class="w3-input w3-border w3-round" name="docAuthor" id="docAuthor" type="text" required
               placeholder="">
            </p>

            <p>
               <label>Adviser(s) <small class="w3-text-gray">Ex: Perera A.B.C.</small></label>
               <input class="w3-input w3-border w3-round" name="docAdviser" id="docAdviser" type="text"
               placeholder="">
            </p>

            <p>
               <label>Tags <small class="w3-text-gray">Add document category, Related Course etc</small></label>
               <input class="w3-input w3-border w3-round" name="docTags" id="docTags" type="text"
               placeholder="Type by comma separated">
            </p>

            <p>
               <label>Description</label>
               <textarea name="docDescription" rows="7" id="docDescription" class="jqteText" ></textarea>

            </p>

            <p class="w3-hide">
               <label>Publishing Rule</label>
               <select class="w3-select w3-border w3-round" name="docVisibility" id="docVisibility" required>
                  <option value="" disabled >Select a option</option>
                  <option value="0">Visible to Everyone</option>
                  <option value="1" selected>Visible to Search Index</option>
                  <option value="2">( Need to discuss this further more )</option>
               </select>
            </p>

            <p class="">
               <label>Upload the File (PDF only, max 2MB)</label>
            </p>

            <div id="drop_file_zone" ondrop="upload_file(event)" ondragover="return false">
               <div id="drag_upload_file">
                  <p><span id="pdfName">Drop file here or </span></p>

                  <p><input type="button" value="Select File" onclick="file_explorer();"></p>
                  <input class="w3-input w3-border w3-round" id="docPDF" name="docPDF" type="file" required>
               </div>
            </div>

            <p>
               <button id="submit" type="submit" class="w3-btn w3-theme w3-round">Submit Now</button>
            </p>

         </form>
      </div>

      <br><br><br><br>

   </div>

</div>

<script type="text/javascript">
$(document).ready(function () {

   $('input[name="docAuthor"]').amsifySuggestags({
      type : 'amsify',
      suggestionsAction : {
         url : 'http://localhost/CO227-Project/web/public/dashboard/lists/users.php'
      }
   });
   $('input[name="docAdviser"]').amsifySuggestags({
      type : 'amsify',
      suggestionsAction : {
         url : 'http://localhost/CO227-Project/web/public/dashboard/lists/users.php'
      }
   });
   $('input[name="docTags"]').amsifySuggestags({
      type : 'amsify',
		tagLimit: 5,
   });

   $('.jqteText').jqte({formats: false});

   // settings of status
   var jqteStatus = true;
   $(".status").click(function () {
      jqteStatus = jqteStatus ? false : true;
      $('.jqte-test').jqte({"status": jqteStatus})
   });

   $('#fillIt').click(function () {
      $("#docTitle").val("Pera Knowledge Portal");
      $("#docType").val(1);
      //$("#docAuthor").val("");
      //$("#docAdviser").val("Roshan Ragel");
      //$("#docTags").val("ESCaPe 2016, High Performance Computing, GPU");
      $("#docDescription").html("Our project aims to create a database of Research papers, Project Reports, Thesis’ done in University of Peradeniya. Since there is no current database where we keep the project reports other than the hard copies themselves, it is not possible for a person outside the university to access these reports. We created a platform where anyone in the university can upload their respective reports into a database which can be used by anyone; inside or outside the university, to access and read through.");
      $("#docVisibility").val(0);
   });

});

var fileobj;
function upload_file(e) {
   e.preventDefault();
   fileobj = e.dataTransfer.files[0];
   $("#pdfName").html(fileobj.name);
}

function file_explorer() {
   document.getElementById('docPDF').click();
   document.getElementById('docPDF').onchange = function () {
      fileobj = document.getElementById('docPDF').files[0];
      $("#pdfName").html(fileobj.name);
      console.log(fileobj);
   };
}
</script>


</body>
</html>
