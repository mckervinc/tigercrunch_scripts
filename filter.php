<?php
  /******************************************************
   * This script is used to filter/search for food
   * Reutrns JSON
   ****************************************************/
include('db_funcs.php');
$filter = $_GET['search'];
$db = new HungerBase();
echo  $db->searchBase($filter);
?>