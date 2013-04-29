<?php

$ROOT="/var/www/parltrack";
ini_set("memory_limit","1024M");
header("Content-Type: text/csv");

if (!empty($_REQUEST["url"])) $url=$_REQUEST["url"];
if (!empty($argv[1])) $url=$argv[1];

if (empty($url)) {
  echo "USAGE: json2csv.php <file>\n";
  echo " transform a json file from parltrack to a csv that can be uploaded into the PIPHONE admin interface\n";
  exit(1);
}

$me=json_decode(file_get_contents($url));

/* debug */
/*
foreach($me as $k=>$v) {
  echo "K:$k V:$v\n";
}
*/

function getparltrack($url,$id) {
  global $ROOT;
  error_log( "GetParlTrack:$url $id");
  if (!is_file($ROOT."/".$id.".csv")) {
    $g=fopen(str_replace(" ","%20",$url),"r");
    if (!$g) {
      return "FAILED";
    }
    $f=fopen($ROOT."/".$id.".csv","w");
    while ($s=fgets($g,1024)) {
      fputs($f,$s);
    }
    fclose($g);
    fclose($f);
  }
  return $ROOT."/".$id.".csv";
}

$countries=array(
		 "Austria" => "AT", "Belgium" => "BE",	 "Bulgaria" => "BG",	 "Cyprus" => "CY",	 
		 "Czech Republic" => "CZ",	 "Denmark" => "DK",	 "Estonia" => "EE",
		 "Finland" => "FI", 	 "France" => "FR",	 "Germany" => "DE",	 "Greece" => "EL",
		 "Hungary" => "HU",	 "Ireland" => "IE",	 "Italy" => "IT",  "Latvia" => "LV",	
		 "Lithuania" => "LT",	 "Luxembourg" => "LU",	 "Malta" => "MT",	 "Netherlands" => "NL",
		 "Poland" => "PL",	 "Portugal" => "PT",  "Romania" => "RO",	 "Slovakia" => "SK",	 
		 "Slovenia" => "SL",	 "Spain" => "ES",	 "Sweden" => "SE",	 "United Kingdom" => "GB",	 
		 );

function Country2Short($const) {
  global $countries;
  foreach($const as $o) {
    if (!empty($countries[$o->country])) {
      return $countries[$o->country];
    }
  }
}

function GoodPhone($phone) {
  return str_replace("+","00",str_replace(" ","",$phone));
}

foreach($me->meps as $k=>$v) {
  //    echo "MEP: K:$k crole:".$v->crole."\n";
  // print_r($v); exit();
 //  if ($v->crole=="Substitute") continue; 
  // Print it as CSV : 
  // "Nom Prénom";"parltrackurl";"téléphone";"pays";"score"
  $url=getparltrack('http://parltrack.euwiki.org/mep/'.$v->Name->full.'?format=json',$v->UserID);

  echo '"'.$v->Name->full.'"'.
    ';'.
    '"'.$url.'"'.
    ';'.
    '"'.GoodPhone($v->Addresses->Brussels->Phone).'"'.
    ';'.
    '"'.Country2Short($v->Constituencies).'"'.
    ';'.
    '"1"'.
    "\n";

}


