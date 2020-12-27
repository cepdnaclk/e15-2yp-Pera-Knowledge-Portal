<?php

class docsClass
{
   private $db;

   function __construct($db)
   {
      $this->db = $db;

      /*if ($this->mysqli->connect_error) {
      die("Connection failed: " . $this->mysqli->connect_error);
   }*/
}

function __destruct()
{

}

function newDoc($docId, $title, $type, $submitBy, $submitTime, $status, $tags, $text, $notes, $thumb, $visibility)
{
   $title = $this->sqlSafe($title);
   $tags = $this->sqlSafe($tags);
   $notes = $this->sqlSafe($notes);

   $sql = "INSERT INTO `kh_doc` (`id`, `docTitle`, `docType`, `submitBy`, `submitTime`, `docStatus`, `docTags`, `docText`, `docNotes`, `hits`, `docThumb`, `docVisibility`)
   VALUES ($docId, '$title', '$type', '$submitBy', '$submitTime', '$status', '$tags', '$text', '$notes', 0, '$thumb', '$visibility');";
   //echo $sql;
   return $this->mysqli->query($sql);
}

function getDoc($id)
{
   $sql = "SELECT * FROM `kh_doc` WHERE `id` LIKE $id;";
   return $this->getDataRow($sql);
}

function getDocData($id, $field)
{
   $sql = "SELECT * FROM `kh_doc` WHERE `id` LIKE $id;";
   return $this->getData($sql, $field);
}

function setDocData($id, $field, $value)
{
   $value = $this->sqlSafe($value);
   $sql = "UPDATE `kh_doc` SET `$field` = '$value' WHERE `id` = '$id';";
   return $this->mysqli->query($sql);
}

function deleteDoc($id)
{
   $sql = "DELETE FROM `kh_doc` WHERE `id` = '$id';";
   return $this->deleteRow($sql);
}

function listDocsByUserId($userId)
{
   $sql = "SELECT * FROM `kh_doc` WHERE `submitBy` = $userId;";
   return $this->listWholeRows($sql);
}

function listDocs()
{
   $sql = "SELECT * FROM `kh_doc` WHERE 1;";
   return $this->listWholeRows($sql);
}

function listDocsWithRelations()
{
   $sql = "SELECT d.`id`,`docTitle`,`docType`,`submitBy`,`submitTime`,`docStatus`,`docTags`, `docText`,`docNotes`,`hits`,`docThumb`,`docVisibility`, au.`relation` as 'authors', ad.`relation` as 'advisers'
   FROM kh_doc as d , kh_doc_relations as au , kh_doc_relations as ad
   WHERE (d.id LIKE au.docId AND au.relationType LIKE 'AUTHOR')
   AND (d.id LIKE ad.docId AND ad.relationType LIKE 'ADVISER')
   ORDER BY d.id;";
   return $this->listWholeRows($sql);
}

function existsDocId($id)
{
   if (is_numeric($id)) {
      $docId = mysqli_real_escape_string($this->mysqli, $id);
      $sql = "SELECT * FROM `kh_doc` WHERE `id` = $docId";
      return $this->exists($sql);
   } else {
      return 0;
   }
}

function addRelation($docId, $relation, $type)
{
   $relation = $this->sqlSafe($relation);

   $sql = "INSERT INTO `kh_doc_relations` (`id`, `docId`, `relation`, `relationType`)
   VALUES (NULL, '$docId', '$relation', '$type');";
   return $this->mysqli->query($sql);
}

function updateRelation($docId, $relation, $type)
{
   $relation = $this->sqlSafe($relation);

   $sql = "UPDATE `kh_doc_relations` SET `relation` = '$relation' WHERE (`docId` = $docId) AND (`relationType` LIKE '$type')";
   return $this->mysqli->query($sql);
}

function getRelation($docId, $type)
{
   $sql = "SELECT * FROM `kh_doc_relations` WHERE (`docId` LIKE $docId) AND (`relationType` LIKE '$type')";
   return $this->getData($sql, "relation");
}

function get_DocsByUser($userName){
   // Removed :  `docText`,
   $userName = trim($userName);

   $sql = "SELECT d.`id`,`docTitle`,`docType`,`submitBy`,`submitTime`,`docStatus`,`docTags`, `docText`,`docNotes`,`hits`,`docThumb`,`docVisibility`, au.`relation` as 'authors', ad.`relation` as 'advisers'
   FROM kh_doc as d , kh_doc_relations as au , kh_doc_relations as ad
   WHERE (d.id LIKE au.docId AND au.relationType LIKE 'AUTHOR')
   AND (d.id LIKE ad.docId AND ad.relationType LIKE 'ADVISER')
   AND (`docStatus` LIKE 'APPROVED') AND (au.`relation` LIKE '%$userName%')
   ORDER BY d.id;";

   return $this->listWholeRows($sql);
}


function get_DocsByTag($tag){
   $tag = trim($tag);
   $sql = "SELECT * FROM `kh_doc` WHERE (LOWER(`docTags`)  LIKE '%$tag%') AND (`docStatus` LIKE 'APPROVED');";
   //echo $sql;
   return $this->listWholeRows($sql);
}

// Utility Functions -----------------------------------------------------------------------------------------------

private function sqlSafe($text)
{
   $text = str_replace("'", "\"", $text);
   $text = str_replace("`", "\"", $text);

   return $text;
}

// Super Functions -----------------------------------------------------------------------------------------------

private function exists($sql)
{
   if ($result = $this->db->query($sql)) {
      if ($result->num_rows > 0) {
         return 1;
      } else {
         return 0;
      }
   } else {
      return 0;
   }
}

private function getData($sql, $field)
{
   $result = $this->db->query($sql);
   return $result->fetch_assoc()[$field];
}

private function getDataRow($sql)
{
   $result = $this->db->query($sql);
   return $result->fetch_assoc();
}

private function listRows($sql, $field)
{
   if ($result = $this->db->query($sql)) {
      $j = 0;
      $arAdd = array();

      while ($row = mysqli_fetch_array($result)) {
         $arAdd[$j] = $row[$field];
         $j++;
      }
      return $arAdd;
   } else {
      return 0;
   }
}

private function listWholeRows($sql)
{

   if ($result = $this->db->query($sql)) {
      $j = 0;
      $arAdd = array();

      while ($row = mysqli_fetch_array($result)) {
         $arAdd[$j] = $row;
         $j++;
      }
      return $arAdd;
   } else {
      return 0;
   }
}

private function deleteRow($sql)
{
   if ($this->db->query($sql) == TRUE) {
      return true;
   } else {
      return false;
   }
}

}

?>
