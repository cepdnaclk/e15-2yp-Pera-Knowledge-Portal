<?php

class profileClass
{
   private $db;

   function __construct($db)
   {
      $this->mysqli = $db;

      /*if ($this->mysqli->connect_error) {
         die("Connection failed: " . $this->mysqli->connect_error);
      }*/
   }

   function __destruct()
   {

   }

   function newProfile($userId, $date)
   {
      $sql = "INSERT INTO `kh_profile_active` (`userId`, `status`, `department`, `pos`, `skills`, `description`, `linkedinProfile`,`githubProfile`,  `lastUpdate`)
      VALUES ('$userId','PENDING',  NULL, NULL, NULL, NULL, NULL, NULL, NULL, '$date');";

      return $this->db->query($sql);
   }

   function updateProfile($userId, $department, $position, $skills, $description, $linkedinProfile, $githubProfile, $lastUpdate)
   {
      $department = mysqli_real_escape_string($this->mysqli, $department);
      $position = mysqli_real_escape_string($this->mysqli, $position);
      $skills = mysqli_real_escape_string($this->mysqli, $skills);

      $description = mysqli_real_escape_string($this->mysqli, $description);
      $linkedinProfile = mysqli_real_escape_string($this->mysqli, $linkedinProfile);
      $githubProfile =  mysqli_real_escape_string($this->mysqli, $githubProfile);

      $sql = "UPDATE `kh_profile_active`
      SET `department`='$department',`pos`='$position',`skills`='$skills',`description`='$description',
      `linkedinProfile`='$linkedinProfile', `githubProfile`='$githubProfile',  `approvalNotes`='$approvalNote',`lastUpdate`='$lastUpdate'
      WHERE `userId`='$userId'";

      return $this->mysqli->query($sql);
   }

   function get_profileRequestData($userId, $field)
   {
      $sql = "SELECT * FROM `kh_profile_active` WHERE `userId` LIKE '$userId';";
      return $this->getData($sql, $field);
   }

   function existsProfile($userId)
   {
      $sql = "SELECT * FROM `kh_profile_active` WHERE `userId` = '$userId'";
      return $this->exists($sql);
   }

   function update_profileData($userId, $field, $value)
   {
      $value = mysqli_real_escape_string($this->mysqli, $value);

      $sql = "UPDATE `kh_profile_active` SET `$field` = '$value' WHERE `userId` = '$userId';";
      return $this->mysqli->query($sql);
   }

   function list_profiles($status)
   {
      $sql = "SELECT * FROM `kh_profile_active` WHERE `status` LIKE '$status';";
      return $this->listWholeRows($sql);
   }

   function get_profile($id)
   {
      $sql = "SELECT * FROM `kh_profile_active` WHERE `userId` LIKE $id;";
      return $this->getDataRow($sql);
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

   private function getData($sql, $field)
   {
      $result = $this->mysqli->query($sql);
      return $result->fetch_assoc()[$field];
   }

   private function getDataRow($sql)
   {
      $result = $this->mysqli->query($sql);
      return $result->fetch_assoc();
   }

   private function listRows($sql, $field)
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

   private function listWholeRows($sql)
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

   private function deleteRow($sql)
   {
      if ($this->mysqli->query($sql) == TRUE) {
         return true;
      } else {
         return false;
      }
   }
}
