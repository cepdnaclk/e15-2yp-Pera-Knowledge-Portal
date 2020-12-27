<?php

define("DB_HOST", "localhost");

define("DB_DATABASE", "ceykod_co227");

define("DB_USER", "user");
define("DB_PASS", "password");


class database
{
   public $mysqli;

   function __construct()
   {
      $this->mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_DATABASE);

      if ($this->mysqli->connect_error) {
         die("Connection failed: " . $this->mysqli->connect_error);
      }
   }

   function __destruct()
   {
      $this->mysqli->close();
   }

   function get_SiteData($key)
   {
      $sql = "SELECT * FROM `kh_data` WHERE `dKey` LIKE '$key'";

      $result = $this->mysqli->query($sql);
      $row = $result->fetch_assoc();
      return $row['dValue'];
   }

   function set_SiteData($key, $dVal)
   {
      $sql = "UPDATE `kh_data` SET `dValue` = '$dVal' WHERE `kh_data`.`dKey` LIKE '$key';";
      return $this->mysqli->query($sql);
   }

   // Login Related Functions -----------------------------------------------------------------------------------------

   function existsEmail($email)
   {
      $email = mysqli_real_escape_string($this->mysqli, $email);
      $sql = "SELECT * FROM `kh_users` WHERE `email` LIKE '$email'";

      if ($result = $this->mysqli->query($sql)) {
         return ($result->num_rows > 0);
      } else {
         return 0;
      }
   }

   function existsUser($email, $password)
   {
      $email = mysqli_real_escape_string($this->mysqli, $email);
      $pwdmd5 = md5($password);

      $sql = "SELECT * FROM `kh_users` WHERE `email` LIKE '$email' AND `password` LIKE '$pwdmd5';";

      if ($result = $this->mysqli->query($sql)) {
         return ($result->num_rows > 0);
      } else {
         return 0;
      }
   }

   function existsUserId($userId)
   {
      $userId = mysqli_real_escape_string($this->mysqli, $userId);
      $sql = "SELECT * FROM `kh_users` WHERE `id` LIKE '$userId';";

      if ($result = $this->mysqli->query($sql)) {
         return ($result->num_rows > 0);
      } else {
         return 0;
      }
   }

   // User Related Functions ------------------------------------------------------------------------------------------

   function newUser($firstName, $lastName, $honorific, $email, $password, $role, $loginType, $lastAccess, $imgURL)
   {
      $firstName = $this->sqlSafe($firstName);
      $lastName = $this->sqlSafe($lastName);
      $email = $this->sqlSafe($email);

      $sql = "INSERT INTO `kh_users` (`id`, `email`, `password`, `honorific`, `firstName`, `lastName`, `role`, `loginType`, `lastAccess`, `imageURL`)
      VALUES (NULL, '$email', '$password', '$honorific', '$firstName ', '$lastName', '$role', '$loginType', '$lastAccess', `$imgURL`);";
      return $this->mysqli->query($sql);
   }

   function getUserId_byEmail($email)
   {
      $sql = "SELECT `id` FROM `kh_users` WHERE `email` LIKE '$email'";
      $row = $this->mysqli->query($sql)->fetch_assoc();
      return $row['id'];
   }

   function getUserData($userId, $field)
   {
      $sql = "SELECT * FROM `kh_users` WHERE `id` LIKE $userId";
      $row = $this->mysqli->query($sql)->fetch_assoc();
      return $row[$field];
   }

   function setUserData($userId, $field, $value)
   {
      $sql = "UPDATE `kh_users` SET `$field` = '$value' WHERE `id` = '$userId';";
      return $this->mysqli->query($sql);
   }

   function listUsers($field)
   {
      $sql = "SELECT * FROM `kh_users` WHERE 1";
      return $this->listRows($sql, $field);
   }

   function deleteUser($userId)
   {
      $sql = "DELETE FROM `kh_users` WHERE `id` LIKE '$userId';";
      return ($this->mysqli->query($sql) == TRUE);
   }

   function getName_byUserId($userId)
   {
      //$salutation = json_decode(file_get_contents("../lists/salutations.json"), true);

      $sql = "SELECT * FROM `kh_users` WHERE `id` LIKE $userId";
      $row = $this->mysqli->query($sql)->fetch_assoc();
      //print_r($row);
      //$row = $result;
      return  $row['firstName'] . " " . $row['lastName']; // $salutation[$row['honorific']] . " " .
   }

   // Not implemented
   function listLecturers()
   {
      $sql = "SELECT * FROM `kh_users` WHERE `role` LIKE 2;";
      return $this->listWholeRows($sql);
   }

   // Not implemented
   function listInstructors()
   {
      $sql = "SELECT * FROM `kh_users` WHERE `role` LIKE 3;";
      return $this->listWholeRows($sql);
   }


   // Docs  Related Functions ---------------------------------------------------------------------------------------

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
      $sql = "SELECT * FROM `kh_doc` WHERE `submitBy` = '$userId' ORDER BY `id`; ";
      return $this->listWholeRows($sql);
   }

   function listDocs()
   {
      $sql = "SELECT * FROM `kh_doc` WHERE 1 ORDER BY `id`;";
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


   // Utility Functions -----------------------------------------------------------------------------------------------

   private function sqlSafe($text)
   {
      $text = str_replace("'", "\"", $text);
      $text = str_replace("`", "\"", $text);

      return $text;
   }

   // User Profile Functions ---------------------------------------------------------------------------------------

   function newProfile($userId, $date)
   {
      $sql = "INSERT INTO `kh_profile_request` (`userId`, `status`, `department`, `pos`, `skills`, `description`, `linkedinProfile`,`githubProfile`, `approvalNotes`, `lastUpdate`)
      VALUES ('$userId','PENDING',  NULL, NULL, NULL, NULL, NULL, NULL, NULL, '$date');";

      return $this->mysqli->query($sql);
   }

   function updateProfile($userId, $department, $position, $skills, $description, $linkedinProfile, $githubProfile, $lastUpdate)
   {
      $department = mysqli_real_escape_string($this->mysqli, $department);
      $position = mysqli_real_escape_string($this->mysqli, $position);
      $skills = mysqli_real_escape_string($this->mysqli, $skills);

      $description = mysqli_real_escape_string($this->mysqli, $description);
      $linkedinProfile = mysqli_real_escape_string($this->mysqli, $linkedinProfile);
      $githubProfile =  mysqli_real_escape_string($this->mysqli, $githubProfile);

      $sql = "UPDATE `kh_profile_request`
      SET `department`='$department',`pos`='$position',`skills`='$skills',`description`='$description',
      `linkedinProfile`='$linkedinProfile', `githubProfile`='$githubProfile',  `approvalNotes`='$approvalNote',`lastUpdate`='$lastUpdate'
      WHERE `userId`='$userId'";

      return $this->mysqli->query($sql);
   }

   function getProfileRequestData($userId, $field)
   {
      $sql = "SELECT * FROM `kh_profile_request` WHERE `userId` LIKE '$userId';";
      return $this->getData($sql, $field);
   }

   function existsProfile($userId)
   {
      $sql = "SELECT * FROM `kh_profile_request` WHERE `userId` = '$userId'";
      return $this->exists($sql);
   }

   function updateProfileData($userId, $field, $value)
   {
      $value = mysqli_real_escape_string($this->mysqli, $value);

      $sql = "UPDATE `kh_profile_request` SET `$field` = '$value' WHERE `userId` = '$userId';";
      return $this->mysqli->query($sql);
   }

   function listProfiles($status)
   {
      $sql = "SELECT * FROM `kh_profile_request` WHERE `status` LIKE '$status';";
      return $this->listWholeRows($sql);
   }

   function getProfile($id)
   {
      $sql = "SELECT * FROM `kh_profile_request` WHERE `userId` LIKE $id;";
      return $this->getDataRow($sql);
   }



   // Super Functions -----------------------------------------------------------------------------------------------

   public function query($sql){
      return $this->mysqli->query($sql);
   }
   function exists($sql)
   {
      if ($result = $this->mysqli->query($sql)) {
         if ($result->num_rows > 0) {
            return 1;
         } else {
            return 0;
         }
      } else {
         return 0;
      }
   }

   function getData($sql, $field)
   {
      $result = $this->mysqli->query($sql);
      return $result->fetch_assoc()[$field];
   }

   function getDataRow($sql)
   {
      $result = $this->mysqli->query($sql);
      return $result->fetch_assoc();
   }

   function listRows($sql, $field)
   {
      if ($result = $this->mysqli->query($sql)) {
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

   function listWholeRows($sql)
   {

      if ($result = $this->mysqli->query($sql)) {
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

   function deleteRow($sql)
   {
      if ($this->mysqli->query($sql) == TRUE) {
         return true;
      } else {
         return false;
      }
   }

   // Query Functions -----------------------------------------------------------------------------------------------

   function q_Update($table, $key, $value, $field, $new)
   {
      $sql = "UPDATE `$table` SET `$field` = '$new' WHERE `$key` = '$value';";
      if ($this->mysqli->query($sql) == TRUE) {
         return true;
      } else {
         return false;
      }
   }

   function q_Select($table, $key, $value, $field)
   {
      $sql = "SELECT * FROM `$table` WHERE `$key`.`id` = '$value';";
      $result = $this->mysqli->query($sql);
      return $result->fetch_assoc()[$field];
   }

   function q_Delete($table, $field, $value)
   {
      $sql = "DELETE FROM `$table` WHERE `$field` = '$value';";
      return ($this->mysqli->query($sql) == TRUE);
   }

   function q_Exist($table, $field, $value)
   {
      $sql = "SELECT * FROM `$table` WHERE `$field` LIKE '$value';";

      if ($result = $this->mysqli->query($sql)) {
         return ($result->num_rows > 0);
      } else {
         return 0;
      }
   }

   function q_List($table, $field, $option)
   {
      $sql = "SELECT * FROM `$table` WHERE $option";
      if ($result = $this->mysqli->query($sql)) {
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

}
