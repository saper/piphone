<?php

require("../config.php");

mysql_query("DELETE FROM lists WHERE campaign=22;"); 
$f=fopen("mxsenate2.csv","rb");
$s=fgetcsv($f,0,","); // skip first line ;) 
while ($s=fgetcsv($f,0,",")) {
      // fields are 0: ID  1: URL  2: Name  3: Party  4: Estado  5: Commission  6: Tel  7: ext (,)  8: email  9: twitter
//  print_r($s);
  $phone=explode(",",$s[7]);
  $phone=trim($phone[0]);
  $meta=serialize(array("id" => $s[0], "party" => $s[3], "estado"=>$s[4], "comision" => $s[5], "email" => $s[8], "twitter"=>$s[9]));
  mysql_query("INSERT INTO lists SET campaign=22, 
  phone='".addslashes($phone)."', url='".addslashes($s[1])."', name='".addslashes($s[2])."', 
  country='".addslashes($s[4])."', enabled=1, scores=1, pond_scores=10, meta='".addslashes($meta)."'
   ;");
  if (mysql_affected_rows()) echo "."; else echo "!"; 
  flush();
}
fclose($f);