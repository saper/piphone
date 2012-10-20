<?php

class campaignController extends abstractController {


  private $countryPhoneCodes = array(
     "+43" => "Austria",     "+32" => "Belgium",     "+357" => "Chyprus",
     "+420" => "Czech",     "+45" => "Denmark",     "+372" => "Estonia",
     "+358" => "Finland",      "+33" => "France",     "+49" => "Germany",
     "+30" => "Greece",     "+36" => "Hungary",     "+353" => "Ireland",
     "+30" => "Italy",     "+371" => "Latvia",     "+370" => "Lithuania",
     "+352" => "Luxembourg",     "+356" => "Malta",     "+31" => "Netherlands",
     "+48" => "Poland",     "+351" => "Portugal",     "+421" => "Slovakia",
     "+386" => "Slovenia",     "+34" => "Spain",     "+46" => "Sweden",
     "+44" => "United Kingdom",
				     );


  /* ************************************************************************ */
  /**
  */
  function campaignController() {
  }
  

  /* ************************************************************************ */
  /** show the active campaigns */
  function indexAction() {
    render("nothing");
    exit();
  }

  private function _getRand() {
    $s="";
    for($i=0;$i<32;$i++) { $s.=chr(mt_rand(97,122)); }
    return $s;
  }
  
  private function _getCampaignCountries($id) {
    $countries=array();
    $r=mq("SELECT DISTINCT c.code,c.name FROM lists l, countries c WHERE c.code=l.country AND l.campaign='".intval($id)."' AND l.enabled=1 ORDER BY 1;");
    while ($c=mysql_fetch_assoc($r)) {
      $countries[$c["code"]]=$c["name"];
    }
    return $countries;
  }

  private function _getRandomMep($campaign_id) {
	/*
	 * Simply put, we need to find a random MEP giving is score.
	 * So we generate a 0<=x<=100 random x and then we pick one mep 
	 * among the ones who have a score greater or equal to x.
	 * We will ponderate the scores with the number of call received divided
	 * by the total number of call related to the campaign.
	 */
    if ($_REQUEST["country"]) $country=$_REQUEST["country"];
    if (isset($country)) {
        $high_score=mqonefield("SELECT max(pond_scores) from lists where campaign='".$campaign_id."' AND country='$country';");
    } else {
        $high_score=mqonefield("SELECT max(pond_scores) from lists where campaign='".$campaign_id."';");
    }

    $threshold=mt_rand(1,$high_score);
    if ($high_score == 0) $threshold = 0;

    // lists contains the mep, they have score and pond_score as fields
    $query="SELECT id FROM lists WHERE campaign='".$campaign_id."'";
    if ($_REQUEST["country"]) $country=$_REQUEST["country"];
	if (isset($country)) { $query .= " AND country='".$country."'"; }
	$query.=" AND pond_scores >= '".$threshold."'";
    $query.=" ORDER BY RAND() LIMIT 1;";
    $mep=mqonefield($query);

    return $mep;
  }

  private function _getCampaign($slug) {
    global $view;
    $campaign=mqone("SELECT *, IF(datestart<=NOW() AND datestop>=NOW(),0,1) AS expired FROM campaign WHERE slug='$slug';");
    if (!$campaign) {
      $view["error"]=_("The specified campaign has not been found, please check your link");
      $this->indexAction();
      exit();
    }
    /*
    if ($campaign["expired"]) {
      $view["error"]=_("The specified campaign is now over, next time come here before it's too late!");
      $this->indexAction();
      exit();
      }
    */
    return $campaign;
  }

  /* ************************************************************************ */
  /** Start a call for a campaign ... */
  function goAction() {
    global $view,$params;
    if (!isset($params[0])) not_found();
    $slug=addslashes(trim($params[0]));

    header("Location: /campaign/call2/".$slug);
    exit();
  }


  /* ************************************************************************ */
  /* Changing everything /o/
   */
  function call2Action() {
    global $view,$params;
    if (!isset($params[0])) not_found();
    $slug=addslashes(trim($params[0]));

    $view["campaign"]=$this->_getCampaign($slug); // Exit in case of error
    if ($view["campaign"]["expired"]) {
      render("campaignexpired");
      exit();
    }
    $view["countries"]=$this->_getCampaignCountries($view["campaign"]["id"]);
    $view["lang"]=substr($GLOBALS["lang"],0,2);

    // If a mep is already set, choose it
    if ($params[1]) $callee=mqone("SELECT * FROM lists WHERE id='".trim($params[1])."';");

    // Find a MEP to call if none has been chosen already
    $mep_id=$this->_getRandomMep($view["campaign"]["id"]);
    if (!isset($callee)) $callee=mqone("SELECT * FROM lists WHERE campaign='".$view["campaign"]["id"]."' AND lists.enabled=1 AND lists.id='".$mep_id."';");
    $view["callee"]=$callee;

    // If I have a callid, it means call has been done
    if ($params[2]) $callid=trim($params[2]);
    if ($callid) $view["callid"]=$callid;

    // If I have a callee but no callid, then check things
    if ($callee && (!isset($callid))) {
      // Check that the phone number is valid
      $phone=trim(str_replace(" ","",$_REQUEST["phone"]));
      if ($phone) {
        if (!preg_match("#^\+[0-9]{5,20}$#",$phone)) {
            $view["message"]=_("Your phone number look strange, please check it");
            render("campaigncall2");
	    exit();
	}
	$found="";
	foreach($this->countryPhoneCodes as $c=>$v) {
          if (substr($phone,0,strlen($c))==$c) {
	    $found=$v;
	    break;
	  }
	}
	if (!$found) {
	  $view["message"]=_("Your phone number is from an unsupported country, sorry for that");
	  render("campaigncall2");
          exit();
	}
      }
    }

    // So, I have a callee AND a callid. SO it's feedback time
    if ($callee && $callid) {
      $view["call"]=mq("SELECT * FROM calls WHERE id='".$callid."';");
      if ($call["feedback"]) {
        $view["error"]=_("A feedback has already been given for that call, sorry");
        $this->call2Action();
        exit();
      }
      mq("UPDATE calls SET feedback='".addslashes($_REQUEST["feedback"])."',dateend=NOW() WHERE id='".$callid."';");
      $view["message"]=_("Your feedback has been sent to us, thanks for your participation!"); 
      unset($view["callid"]);
      unset($view["callee"]);
      header("Location: /campaign/call2/".$view["campaign"]["slug"]."/#mep");
    }  

    // Set or reset the cookie : 
    //if ($_COOKIE["piphone"] && preg_match("#^[a-z]{32}$#",$_COOKIE["piphone"])) {
    //  $cookie=$_COOKIE["piphone"];
    //} else {
    //  $cookie=$this->_getRand();
    //}
    //setCookie("piphone",$cookie,time()+86400*365,"/");

    // store the phone and country into the cookie
    if ($phone) {setCookie("piphone-phone",$phone,time()+86400*365,"/");}
    if ($country) {setCookie("piphone-country",$country,time()+86400*365,"/");}
    //mq("REPLACE INTO cookies SET cookie='$cookie', country='$country', phone='$phone';");

    // If I have a phone and a callee, then, everything is ok
    if ($callee && $phone && (!isset($callid))) {
      // mq("UPDATE lists SET callcount=callcount+1 WHERE id='".$callee["id"]."'");
      $realphone=preg_replace("#^\+#","00",$phone);
      $realcallee=preg_replace("#^\+#","00",$view["callee"]["phone"]);
      mq("INSERT INTO calls SET caller='$phone', callee='".$view["callee"]["phone"]."', datestart=NOW(), campaign='".$view["campaign"]["id"]."';");
      $view["callid"]=mysql_insert_id();
      $uuid=$this->_callback($realphone,$realcallee,$view["campaign"]["wavfile"],substr($GLOBALS["lang"],0,2));
      mq("UPDATE calls SET uuid='$uuid' WHERE id='".$view["callid"]."';");
      $view["phone"]=$phone;
    }

    render("campaigncall2");
  }

  /**********************************************************
   * Fancy widget are fancies
   */

  function widget2Action() {
    global $view,$params;
    if (!isset($params[0])) not_found();
    $slug=addslashes(trim($params[0]));
    $view["campaign"]=$this->_getCampaign($slug);

    if (isset($params[1]) and (strcmp($params[1], "horiz") == 0)) $view["orientation"]="horiz"; else $view["orientation"]="vert";
    if (isset($params[2]) and (strcmp($params[2], "fr") == 0)) $view["lang"]="fr"; else $view["lang"]="en";

    if ($view["campaign"]["expired"]) {
      render("campaignwidgetexpired2");
      exit();
    }
    // Now, we need a mep
    $mep_id=$this->_getRandomMep($view["campaign"]["id"]);
    $view["callee"]=mqone("SELECT * FROM lists WHERE campaign='".$view["campaign"]["id"]."' AND lists.enabled=1 AND lists.id='".$mep_id."';");
    render("campaignwidget2");
    exit();
   }

  function addwidget2Action() {
    global $view,$params;
    if (!isset($params[0])) not_found();
    $slug=addslashes(trim($params[0]));
    $view["campaign"]=$this->_getCampaign($slug);
    render("campaignaddwidget2");
    exit();
  }

/********
 * Feedback
 */
function feedback2Action() {
    global $view,$params;
    // Check the campaign
    if (!isset($params[0])) not_found();
    $slug=addslashes(trim($params[0]));
    $view["campaign"]=$this->_getCampaign($slug); // Exit in case of error
    // Check the call : 
    if (!isset($params[1])) not_found();
    $view["callid"]=$cid=intval($params[1]);
    $call=mqone("SELECT * FROM calls WHERE id='".$view["callid"]."';");
    $callee=mqone("SELECT * FROM lists WHERE phone='(".$call["callee"].")';");
    if ($call["feedback"]) {
      $view["error"]=_("A feedback has already been given for that call, sorry");
      $this->call2Action();
      exit();
    }
    mq("UPDATE calls SET feedback='".addslashes($_REQUEST["feedback"])."' WHERE id='".$view["callid"]."';");
    mq("UPDATE lists SET callcount=callcount+1 WHERE id='".$callee["id"]."');");
    $view["message"]=_("Your feedback has been sent to us, thanks for your participation! CALLID:");
    render("campaigncall2");
  }

  /** This function uses plivo API to make the callback
   */
  private function _callback($from,$to,$wavfile,$lang) {
    //## Code plivo pour le controle de Freeswitch
    require_once("plivo/plivohelper.php");
    
    //instanciation du client plivo
    $client = new PlivoRestClient(REST_API_URL, AccountSid, AuthToken, ApiVersion);
    
    //cree un nouvel uuid dans freeswitch
    $params = array();
    $response = $client->create_uuid($params);
    $bridge_id = $response->Response->Message;
    
    // Search for the wav file for this campaign : 
    if (!$wavfile || !file_exists("/usr/local/freeswitch/sounds/".$wavfile)) {
      $wavfile="lqdn_ann.wav";
    }
    // Search for a local language wav file
    if (file_exists("/usr/local/freeswitch/sounds/".str_replace(".wav","-".$lang.".wav",$wavfile))) {
      $wavfile=str_replace(".wav","-".$lang.".wav",$wavfile);
    }
    //    $this->log($bridge_id."/".$from."/".$to."/".$wavfile);
    // WARNING : I don't use wintermew bridge.lua anymore, modified for SIP trunk on nnx ...
    $params = array( 'command' => "luarun bridge.lua $bridge_id $from $to $wavfile",'bg' => 'true');
    try {
      // Initiate bridge
      $response = $client->command($params);
      return $bridge_id;
    } catch (Exception $e) {
      echo 'Caught exception: ',  $e->getMessage(), "\n";
      return false;
    }
    //##

  } // function _callback


  function log($str) {
    $f=fopen("/tmp/log","ab");
    fputs($f,date("Y/m/d H:i:s")." ".$str."\n");
    fclose($f);
  }
}
