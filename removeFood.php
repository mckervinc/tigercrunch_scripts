<?php
  /****************************************************************
   * delFood.php: This script takes id number sent via query string
   *              deletes corresponding entry from database
   ***************************************************************/
include('db_funcs.php');
$db_entry = $_GET['id'];

$db = new HungerBase();
$db->removeFood($db_entry);