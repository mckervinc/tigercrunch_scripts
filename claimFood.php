<?php
  /********************************************************
   * claimFood.php: This script decrements the claim count
   * Query string format:
   * /?id=BLANK
   ********************************************************/
include('db_funcs.php');
$id = $_GET['id'];
$db = new HungerBase();
$db->claimFood($id);