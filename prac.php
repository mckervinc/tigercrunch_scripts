<?php
include('db_funcs.php');
$bb = new HungerBase();
echo $bb->getId();
$bb->addFood("Brown", "214", "pizza", "best pizza in the world", "house.jpg");
echo $bb->getId();
?>