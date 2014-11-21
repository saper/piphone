<?php

mysql_connect("localhost","root","poipoi");
mysql_select_db("piphone");
mysql_query("SET NAMES UTF8;");

/*
+-------+------+------------+--------------------+--------------+-------+
| begin | end  | role       | mep_id             | committee_id | id    |
+-------+------+------------+--------------------+--------------+-------+
| NULL  | NULL | Member     | CarmenFragaEstevez |          247 | 81500 |
| NULL  | NULL | Member     | PascalCanfin       |          253 | 81501 |
*/

/*
+------+----------+----------------------------+-----------------------------------------------------------------------+--------------+---------+-----------+--------------+---------+
| id   | campaign | name                       | url                                                                   | phone        | country | callcount | callduration | enabled |
+------+----------+----------------------------+-----------------------------------------------------------------------+--------------+---------+-----------+--------------+---------+
| 2857 |        8 | ZIOBRO Zbigniew (EFD)      | https://memopol.lqdn.fr/europe/parliament/deputy/ZbigniewZiobro/      | 003222847699 | PL      |         0 |            1 |       1 |
| 2856 |        8 | ZIJLSTRA Auke (NI)         | https://memopol.lqdn.fr/europe/parliament/deputy/AukeZijlstra/        | 003222845812 | NL      |         0 |            1 |       1 |
| 2855 |        8 | ŽDANOKA Tatjana (Greens)  | https://memopol.lqdn.fr/europe/parliament/deputy/TatjanaZdanoka/      | 003222845912 | LV      |         0 |            1 |       1 |
*/
$co=array(
	  237 => 5,
	  241 => 8,
	  246 => 7,
	  249 => 6,
	  251 => 4,
	  );

foreach($co as $old => $new) {
  $r=mysql_query("SELECT * FROM meps_committeerole WHERE committee_id=$old;");
  echo "old:$old new:$new "; flush();
  while ($c=mysql_fetch_array($r)) {
    mysql_query("UPDATE lists SET name=CONCAT(name,' (role: ".$c["role"].")') WHERE campaign=$new AND url LIKE '%/".$c["mep_id"]."/%';");
    echo ".";
  }
  echo "\n";
}
