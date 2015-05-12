<?php
 date_default_timezone_set('America/New_York');
$datetime1 = new DateTime('2015-05-04 01:36:03');
$datetime2 = new DateTime('2015-05-04 02:51:27');
/*$interval = $datetime1->diff($datetime2);
$formated = $interval->format('%H:%I:%S');
$nn = new DateTime('0-0-0 0:0:0');
$yy = new DateTime($formated);
if ($yy <= $nn)
  echo 'greater';
else
  echo 'not';
  */
$datetime1->modify('+15 minutes');
echo $datetime1->format('g:i:s');
if ($datetime2 > $datetime1)
  echo 'yes';
  /*$time = strtotime('2015-05-04 01:10:00');
$startTime = date("H:i", strtotime('-30 minutes', $time));
$endTime = date("H:i", strtotime('+30 minutes', $time));
echo $time . "\r\n";
echo $startTime . "\r\n";
echo $endTime . "\r\n";*/
?>