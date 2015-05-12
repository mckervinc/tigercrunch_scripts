<?php
/*
	This is a PHP script to clean the database
*/
include 'db_funcs.php';
$hb = new HungerBase();
$hb->cleanDatabase();

?>