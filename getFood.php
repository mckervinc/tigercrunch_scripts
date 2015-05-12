<?php
/********************************************************
* This script simply calls our getFood() function to
* return a JSON file of everything in the MYSQL Database.
*********************************************************/
	include 'db_funcs.php';

	$hb = new HungerBase();
        echo $hb->getFood();
        return $hb->getFood();
?>