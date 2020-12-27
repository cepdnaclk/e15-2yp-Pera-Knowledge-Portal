<?php

include '../data/session.php';

define("FOLDER_NAME", "public");
include_once "../data/accessControl.php";

include_once "../data/database.php";
include_once "../data/classes/userClass.php";
$db = new database();
$users = new userClass($db);

$post = json_decode(file_get_contents('php://input'), true); // array('term'=>"Jal");//
$filter = $post['term'];// "Jal";
$data = $users->getNameSuggestions($filter);

/*
$suggestions 	= ['India', 'Pakistan', 'Nepal', 'UAE', 'Iran', 'Bangladesh'];
$data 			= [];
foreach($suggestions as $suggestion) {
	if(strpos(strtolower($suggestion), strtolower($post['term'])) !== false) {
		$data[] = $suggestion;
	}
}
*/

header('Content-Type: application/json');
echo json_encode(['suggestions' => $data]);
