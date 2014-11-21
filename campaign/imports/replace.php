<?php

require("../config.php");

$f=fopen("LIBE.csv","rb");
while ($s=fgetcsv($f,0,";")) {
  print_r($s);
  mysql_query("UPDATE lists SET phone='".addslashes($s[2])."' WHERE url='".addslashes($s[1])."' AND campaign=20;");
  if (mysql_affected_rows()) echo "!"; else echo "."; 
  flush();
}
fclose($f);