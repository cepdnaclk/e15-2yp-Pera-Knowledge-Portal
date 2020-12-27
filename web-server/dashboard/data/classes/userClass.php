<?php

class userClass
{
   private $db;

   function __construct($db)
   {
      $this->db = $db;

   }

   function __destruct()
   {

   }

   function newUser($firstName, $lastName, $honorific, $email, $password, $role, $loginType, $lastAccess, $imgURL)
   {
      $firstName = $this->sqlSafe($firstName);
      $lastName = $this->sqlSafe($lastName);
      $email = $this->sqlSafe($email);

      $sql = "INSERT INTO `kh_users` (`id`, `email`, `password`, `honorific`, `firstName`, `lastName`, `role`, `loginType`, `lastAccess`, `imageURL`)
      VALUES (NULL, '$email', '$password', '$honorific', '$firstName ', '$lastName', '$role', '$loginType', '$lastAccess','$imgURL');";

      return $this->db->query($sql);
   }

   function getUserId_byEmail($email)
   {
      $sql = "SELECT `id` FROM `kh_users` WHERE `email` LIKE '$email'";
      return $this->getData($sql, 'id');
   }

   function getUserId_byName($name)
   {
      $sql = "SELECT * FROM `kh_users` WHERE CONCAT(`lastName`, ' ', `firstName`) = '$name'";
      //print json_encode($this->listWholeRows($sql)[0]);//$sql;
      $res =$this->listWholeRows($sql);
      if(sizeof($res)>0){
         return $res[0]['id'];;
      }else{
         return 0;
      }
   }


   function getUserData($userId, $field)
   {
      $sql = "SELECT * FROM `kh_users` WHERE `id` LIKE $userId";
      return   $this->listWholeRows($sql)[0][$field];
   }

   function setUserData($userId, $field, $value)
   {
      $sql = "UPDATE `kh_users` SET `$field` = '$value' WHERE `id` = '$userId';";
      return $this->db->query($sql);
   }

   function listUsers($field)
   {
      $sql = "SELECT * FROM `kh_users` WHERE 1";
      return $this->listRows($sql, $field);
   }
   function listUsers_byLastAccessTime($field)
   {
      $sql = "SELECT * FROM `kh_users` WHERE 1 ORDER BY `lastAccess` DESC";
      return $this->listRows($sql, $field);
   }
   function deleteUser($userId)
   {
      $sql = "DELETE FROM `kh_users` WHERE `id` LIKE '$userId';";
      return ($this->db->query($sql) == TRUE);
   }

   function getName_byUserId($userId)
   {
      //$salutation = json_decode(file_get_contents("../lists/salutations.json"), true);

      $sql = "SELECT * FROM `kh_users` WHERE `id` LIKE $userId";
      $result = $this->db->query($sql);
      $row = $result->fetch_assoc();
      return $row['firstName'] . " " . $row['lastName']; // $salutation[$row['honorific']] . " " .
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


   function getNameSuggestions($text){

      $sql = "SELECT `firstName`, `lastName` FROM `kh_users` WHERE `lastName` LIKE '$text%';";
      $result = $this->listWholeRows($sql);
      if (sizeof($result) > 0) {

         $arAdd = array();
         for($i=0;$i<sizeof($result);$i++){
            $arAdd[$i] = $result[$i]['lastName']." ".$result[$i]['firstName'];
         }
         return array_unique(array_map('trim',$arAdd));

      } else {
         return array();
      }
      return ;
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
