<?php
/***********************************************************************
* addFood.php: This script takes information sent via query string and
*				inputs it into the database. Also implements file upload
*				function. 
* Query string format:
*	/?building=BLANK&room_info=BLANK&food=BLANK&description=BLANK&
	image=BLANK
************************************************************************/
include('db_funcs.php');
$building = $_GET['building'];
$room_info = $_GET['room_info'];
$food = $_GET['food'];
$descr = $_GET['description'];
$portion = $_GET['claim'];
$expiration = $_GET['expiration'];

$db = new HungerBase();
$db->addFood($building, $room_info, $food, $descr, $portion, $expiration);
?>