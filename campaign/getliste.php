<?php

// Get a liste of MEP into a campaign list table 
// download pictures if needed, and also metadata ...

// HERE is the campaign number
// Call mep_step1 with the URL of the mep LIST in memopol as parameter.
//mep_step1("MEP.html");

require_once("config.php");
mysql_query("SET NAMES UTF8;");
require_once(dirname(__FILE__)."/functions_iso.php");
require_once(dirname(__FILE__)."/functions.php");
require_once(dirname(__FILE__)."/lang.php");

$picdir=__DIR__."/static/pics/";

//mep_step1("https://memopol.lqdn.fr/europe/parliament/committee/INTA/",3);
mep_step1("https://memopol.lqdn.fr/europe/parliament/names/",14);

			 

function mep_step1($localfile,$campaign) {
  $f=fopen($localfile,"rb");
  $status=0;
  $mep=array();
  
  while ($s=fgets($f,1024)) {

    if (preg_match('#toogle-contact#',$s,$mat)) {
      // ok, we got the email, which is the last information we have on a line for a MEP.  Let's download his card ... to find his national party & photo.
      mep_step2($mep,$campaign);
      $mep=array();
    }    
    
    if (preg_match('#mailto:([^"]*)">#',$s,$mat)) {
      $mail=strtolower($mat[1]);
      $found=false;
      if (isset($mep["mail"]) && is_array($mep["mail"])) {
	foreach($mep["mail"] as $m) {
	  if ($m==$mail) { $found=true; break; }
	}
      }
      if (!$found) {
	$mep["mail"][]=$mail;
      }
    }
    
    if (preg_match('#callto://(.33[^"]*)">#',$s,$mat)) {
      $mep["stb"]=$mat[1];
    }
    
    if (preg_match('#callto://(.32[^"]*)">#',$s,$mat)) {
      $mep["bxl"]=$mat[1];
    }
    
    if (preg_match('#/europe/parliament/group/([^/]*)/">#',$s,$mat)) {
      $mep["group"]=$mat[1];
    }
    
    if (preg_match('#/europe/parliament/deputy/([^/]*)/">([^<]*)</a>#',$s,$mat)) {
      $mep["name"]=$mat[2];
      $mep["url"]=$mat[1];
    }
    
    /*
      if (preg_match('##',$s,$mat)) {
      $mep[""]=$mat[1];
      }
    */
  }
  fclose($f);
}

function mep_step2($mep,$campaign) {
  $f=fopen("https://memopol.lqdn.fr/europe/parliament/deputy/".$mep["url"]."/","rb");
  if ($f) {
    while ($s=fgets($f,1024)) {
      
      if (preg_match('#(/europe/parliament/mep/[0-9]*/picture.jpg)#',$s,$mat)) {
	$mep["picture"]="https://memopol.lqdn.fr".$mat[1];
      }
      if (preg_match('#/europe/parliament/country/([^/"]*)/#',$s,$mat)) {
	$mep["country"]=$mat[1];
      }
      if (preg_match('#/europe/parliament/party/[^/]*/">([^<]*)</a>#',$s,$mat)) {
	$mep["party"]=$mat[1];
      }
      if (preg_match('#/europe/parliament/committee/([^/"]*)/#',$s,$mat)) {
	$mep["committee"][]=$mat[1];
      }
    }
    fclose($f);
  }
  mep_step3($mep,$campaign);
}

  
function mep_step3($mep,$campaign) {
  global $picdir;
  // Now we have all information we could have on that MEP, let's download the picture (if needed) and put the metadata in the 
  if ($mep["picture"]) {
    $pd=md5($mep["picture"]);
    if (!file_exists($picdir.$pd.".jpg")) {
      copy($mep["picture"],$picdir.$pd.".jpg");
    }
    if (!file_exists($picdir.$pd.".jpg")) {
      unset($mep["picture"]);
    } else {
      $mep["picurl"]=$pd.".jpg";
    }
  }

  // set stb or bxl for the campaign location : 
  $phone=str_replace("+","00",$mep["bxl"]);
  echo "\n\n######################################## \nMEP: \n";
  print_r($mep);
  echo "phone: $phone\n";
  mq("INSERT INTO lists SET 
    campaign='$campaign', 
    name='".addslashes($mep["name"])."', 
    meta='".addslashes(serialize($mep))."', 
    callcount=0, enabled=1, 
    country='".addslashes($mep["country"])."', 
    phone='".addslashes($phone)."'
    ;");

}
