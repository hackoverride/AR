<?php
// Om vi går "live" så må denne txt filen enkrypteres!

$brukerid = 0;
$logdetails = "";
$temp_user_ip = $_SERVER['REMOTE_ADDR'];

$logfile = "logginputfile.txt";
date_default_timezone_set('Europe/Oslo');
$date = date ("d-m-Y H:i:s A");
if (isset($_SESSION["DesId"])){
  $brukerid = $_SESSION["DesId"];
  $logdetails = "new upload by User: " . $brukerid . " on ip: " . $temp_user_ip . " at: " . $date . "\n";
} else {
  $logdetails = "new upload by ip: " . $temp_user_ip . " at: " . $date . "\n";
}
$temp = fopen($logfile, "r+");
fwrite($temp, $logdetails);
fclose($temp);

?>