<?php

// Get a liste of MEP into a campaign list table 
// download pictures if needed, and also metadata ...

// HERE is the campaign number
// Call mep_step1 with the URL of the mep LIST in memopol as parameter.
//mep_step1("MEP.html");

$campaign=14;
$to="stb";
//$to="bxl";

require_once("config.php");
mysql_query("SET NAMES UTF8;");
require_once(dirname(__FILE__)."/functions_iso.php");
require_once(dirname(__FILE__)."/functions.php");
require_once(dirname(__FILE__)."/lang.php");

$r=mq("SELECT * FROM lists where campaign=".$campaign.";");
while ($c=mysql_fetch_array($r)) {
  $us=unserialize($c["meta"]);
  //  echo $us["bxl"];
  if (isset($us[$to])) {
    $us[$to]=str_replace("+","00",$us[$to]);
    mq("UPDATE lists SET phone='".$us[$to]."' WHERE id=".$c["id"]);
    echo "."; flush();
  }
}
echo "\n";


