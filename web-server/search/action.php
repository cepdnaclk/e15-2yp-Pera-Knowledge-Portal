<?php

header("Access-Control-Allow-Origin: *");
header('Content-Type: text/json');

if ($_SERVER['HTTP_HOST'] == 'localhost') {
   include_once '../dashboard/data/communication.php';
   include_once '../dashboard/data/database.php';
} else {
   include_once '../dashboard/data/communication.php';
   include_once '../dashboard/data/database.php';
}

if (!isset($_GET['keyword'])) {
   $resp = array(
      'statusCode' => "E1001",
      'statusDetails' => "Keyword is empty",
   );
   print json_encode($resp);

} else {

   $db = new database();
   $com = new communication();

   $keyword = $_GET['keyword'];
   $result = $com->searchDocument($keyword);

   $resultFinal = array();

   $authors = array();
   $advisers = array();
   $tags = array();

   if (is_array($result)) {
      try {
         for ($i = 0; $i < sizeof($result); $i++) {
            // For each search result
            $docId = $result[$i]['documentId'];
            $score = $result[$i]['score'];

            if ($db->getDocData($docId, "docStatus") == "APPROVED") {
               // Only showing the results which has the score more than 2
               if ($score > 2) {

                  $result[$i]['tags'] = array_map('trim', explode(",", $result[$i]['tags'][0]));
                  array_push($resultFinal, $result[$i]);

                  $arrayAuthors = $result[$i]['authors'];
                  $arrayAdvisers = $result[$i]['advisers'];
                  $arrayTags = array_map('trim', explode(",", $db->getDocData($docId, 'docTags')));

                  // Collect authors list and count hit rates
                  foreach ($arrayAuthors as $value) {
                     if (!array_key_exists($value, $tags)) {$authors[$value] = 1;}
                     else{$authors[$value] += 1;}
                  }

                  // Collect advisers list and count hit rates
                  foreach ($arrayAdvisers as $value) {
                     if (!array_key_exists($value, $tags)) {$advisers[$value] = 1;}
                     else{$advisers[$value] += 1;}
                  }

                  // Collect tags and count hit rates
                  foreach ($arrayTags as $value) {
                     if (!array_key_exists($value, $tags)) {$tags[$value] = 1;}
                     else{$tags[$value] += 1;}
                  }
               }
            }
         }
         $c = sizeof($resultFinal);
         $replyText = $c . " results found for the keyword, <b><i>$keyword</i></b>";

         arsort($advisers,1);
         arsort($authors,1);
         arsort($tags, 1);

         $resp = array(
            'statusCode' => "S1000",
            'statusDetails' => "Success",
            'reply' => $replyText,
            'authors' => $authors,
            'advisers' => $advisers,
            'tags' => $tags,
            'result' => $resultFinal
         );

         print json_encode($resp);

      } catch (Exception $e) {

         $resp = array(
            'statusCode' => "E1000",
            'statusDetails' => $e,
         );
         print json_encode($resp);
      }
   }
}

?>
