<?php

// Move all mep of a campaign (below) to Brussells (BXL) or Strasbourg (STB)
$campaign=20;
//$to="stb";
$to="stb";
$from="bxl";

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
    $us[$from]=preg_replace("#^00#","+",$c["phone"]);
    $pho=str_replace("+","00",$us[$to]);
    $q="UPDATE lists SET phone='".$pho."',meta='".addslashes(serialize($us))."' WHERE id=".$c["id"];
    //    echo $q."\n";
    mq($q);
    if (mysql_affected_rows()) echo "."; else echo "!"; 
    flush();
  }
}
echo "\n";

/* Retournement des appels de lists ... evite de peter les stats > oui c'est crade :)
$r=mq("SELECT * FROM lists where campaign=".$campaign.";");
while ($c=mysql_fetch_array($r)) {
  $us=unserialize($c["meta"]);
  //  echo $us["bxl"];
  if (isset($us["stb"]) && isset($us["bxl"])) {
    $us["stb"]=str_replace("+","00",$us["stb"]);
    $us["bxl"]=str_replace("+","00",$us["bxl"]);
    mq("UPDATE calls SET callee='".$us["stb"]."' WHERE callee='".$us["bxl"]."' AND campaign=".$campaign);
    echo "."; flush();
  }
}
echo "\n";
*/


