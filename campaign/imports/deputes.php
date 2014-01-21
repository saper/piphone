<?php

require("../config.php");

// NO( picurl = url of the picture of the deputy)  slug instead
// nom_circo, age, party(parti_ratt_financier)
// nosdeputes =  url_nosdeputes
// job = profession
 
$campaign=22;

mysql_query("DELETE FROM lists WHERE campaign=$campaign;");
$r=mysql_query("SELECT *, (YEAR(NOW()) - YEAR(date_de_naissance) - (RIGHT(CURRENT_TIMESTAMP, 5) < RIGHT(date_de_naissance, 5))) AS age FROM deputes WHERE ancien_depute =0;");

while ($c=mysql_fetch_array($r)) {
  $meta=array(
	      "slug" => $c["slug"],
	      "nom_circo" => $c["nom_circo"],
	      "age" => $c["age"],
	      "party" => $c["parti_ratt_financier"],
	      "job" => $c["profession"]
	      );
  mysql_query("INSERT INTO lists SET campaign=$campaign, name='".addslashes($c["nom"])."', url='', phone='0033".substr($c["phone"],1)."', country='FR', enabled=1, meta='".addslashes(serialize($meta))."';");
  //  file_put_contents("/root/piphone/campaign/static/deputes/".$c["slug"].".jpg",file_get_contents("http://www.nosdeputes.fr/depute/photo/".$c["slug"]."/220"));
}

