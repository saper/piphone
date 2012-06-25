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

    $view["campaign"]=$this->_getCampaign($slug); // Exit in case of error
    $view["countries"]=$this->_getCampaignCountries($campaign["id"]);
    
    $view["lang"]=substr($GLOBALS["lang"],0,2);
    
    render("campaigngo");
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
    if ($_REQUEST["country"]) $country=$_REQUEST["country"];
    if ($country) $sql=" AND country='$country' "; else $sql="";
    if (!isset($callee)) $callee=mqone("SELECT * FROM lists WHERE campaign='".$view["campaign"]["id"]."' AND lists.enabled=1 $sql ORDER BY callcount ASC, RAND();");
    $view["callee"]=$callee;
    //    $view["message"]=$country;

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
    }  

    // Set or reset the cookie : 
    if ($_COOKIE["piphone"] && preg_match("#^[a-z]{32}$#",$_COOKIE["piphone"])) {
      $cookie=$_COOKIE["piphone"];
    } else {
      $cookie=$this->_getRand();
    }
    setCookie("piphone",$cookie,time()+86400*365,"/");
    mq("REPLACE INTO cookies SET cookie='$cookie', country='$country', phone='$phone';");

    // If I have a phone and a callee, then, everything is ok
    if ($callee && $phone && (!isset($callid))) {
      mq("UPDATE lists SET callcount=callcount+1 WHERE id='".$callee["id"]."'");
      $realphone=preg_replace("#^\+#","00",$view["phone"]);
      $realcallee=preg_replace("#^\+#","00",$view["callee"]["phone"]);
      mq("INSERT INTO calls SET caller='$phone', callee='".$view["callee"]["phone"]."', datestart=NOW(), campaign='".$view["campaign"]["id"]."';");
      $view["callid"]=mysql_insert_id();
      $uuid=$this->_callback($realphone,$realcallee,$campaign["wavfile"],substr($GLOBALS["lang"],0,2));
      mq("UPDATE calls SET uuid='$uuid' WHERE id='".$view["callid"]."';");
      $view["phone"]=$phone;
    }

    render("campaigncall2");
  }

  /**********************************************************
   * Call people, but check their number first
   */
  function callme2Action() {
    global $view,$params;
    if (!isset($params[0])) not_found();
    $slug=addslashes(trim($params[0]));
    $view["campaign"]=$this->_getCampaign($slug);
    // Check that the phone number isvalid
    $phone=trim(str_replace(" ","",$_REQUEST["phone"]));
    if ($phone) {
      if (!preg_match("#^\+[0-9]{5,20}$#",$phone)) {
          $view["message"]=_("Your phone number look strange, please check it");
 	  $this->call2Action();
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
	  $this->call2Action();
          exit();
	}
      }
      // Check the country
      $country=trim($_REQUEST["country"]);
      if ($country && !array_key_exists($country,$view["countries"])) {
	$view["message"]=_("Country not found, please check"); 
	$this->call2Action();
	exit();
      }
    // Get the callee back
        if ($_COOKIE["callee"]) {
            $cookie=$_COOKIE["callee"];
        }
    $callee=mqone("SELECT * FROM lists WHERE id='".$cookie."';");
    mq("UPDATE lists SET callcount=callcount+1 WHERE id='".$callee["id"]."'");
    mq("INSERT INTO calls SET caller='$phone', callee='".$callee["phone"]."', datestart=NOW(), campaign='".$view["campaign"]["id"]."';");
    $view["callid"]=mysql_insert_id();
    $view["phone"]=$phone;
    //$callid=intval(trim($params[0]));
    $call=mqone("SELECT * FROM calls WHERE id=".$view["callid"]." AND uuid='';");
    $campaign=mqone("SELECT * FROM campaign WHERE id=".$call["campaign"].";");
    if (!$call || !$campaign) {
      not_found();
    }
    $realphone=preg_replace("#^\+#","00",$call["caller"]);
    $realcallee=preg_replace("#^\+#","00",$call["callee"]);

    // FORCE for PREPROD : 
    if (defined("FORCETO")) {
      $realcallee=FORCETO;
    }

    $uuid=$this->_callback($realphone,$realcallee,$campaign["wavfile"],substr($GLOBALS["lang"],0,2));
    mq("UPDATE calls SET uuid='$uuid' WHERE id='".$view["callid"]."';");
    $view["frame"]=1;
    $this->call2Action();
    //echo "OK";
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
    if ($call["feedback"]) {
      $view["error"]=_("A feedback has already been given for that call, sorry");
      $this->call2Action();
      exit();
    }
    mq("UPDATE calls SET feedback='".addslashes($_REQUEST["feedback"])."' WHERE id='".$view["callid"]."';");
    $view["message"]=_("Your feedback has been sent to us, thanks for your participation! CALLID:");
    render("campaigncall2");
  }



  /* ************************************************************************ */
  /** IFRAME to SHOW the FORM to enter your number and country 
   * 
   */
  function callAction() {
    global $view,$params;
    if (!isset($params[0])) not_found();
    $slug=addslashes(trim($params[0]));

    $view["campaign"]=$this->_getCampaign($slug); // Exit in case of error
    if ($view["campaign"]["expired"]) {
      render("campaignexpired");
      exit();
    }
    $view["countries"]=$this->_getCampaignCountries($view["campaign"]["id"]);
    switch(intval($_REQUEST["step"])) {
    case 0: // STEP 0 : show the form with phone number and country : 
      
      $preselected=false;
      
      // If we have a cookie, preselect country & phone numbers : 
      if ($_COOKIE["piphone"]) {
	$cookie=mqone("SELECT * FROM cookies WHERE cookie='".addslashes($_COOKIE["piphone"])."';");
	if (!$cookie) setCookie("piphone","",0,"/");
	else {
      	  $_REQUEST["country"]=$cookie["country"];
	  $_REQUEST["phone"]=$cookie["phone"];
	  $preselected=true;
	}
      }
      if ($_SERVER["HTTP_ACCEPT_LANGUAGE"] && !$preselected) {
	// Search for a country to preselect ...
	$test=explode(",",$_SERVER["HTTP_ACCEPT_LANGUAGE"]);
	// example : fr,fr-fr;q=0.8,en;q=0.5,en-us;q=0.3 
	foreach($test as $t) {
	  if (preg_match("#^[a-z][a-z]-([a-z][a-z])#",$t,$mat)) {
	    //	    $_REQUEST["country"]=strtoupper($mat[1]);
	    $preselected=true;
	    break;
	  }
	}
      }
      render("campaignform"); 
      exit();

    case 1: // STEP 1 : Show a phone number + MEP name AND show "calling" and CALL :) if a phone number has been given.
      // Or show a button to give feedback if needed.

      // Check the phone number 
      $phone=trim(str_replace(" ","",$_REQUEST["phone"]));
      if ($phone) {
	if (!preg_match("#^\+[0-9]{5,20}$#",$phone)) {
	  $view["message"]=_("Your phone number look strange, please check it");
	  render("campaignform"); 
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
          render("campaignform");
          exit();
	}
      }
      // Check the country
      $country=trim($_REQUEST["country"]);
      if ($country && !array_key_exists($country,$view["countries"])) {
	$view["message"]=_("Country not found, please check"); 
	$this->indexAction();
	exit();
      }
      
      // Set or reset the cookie : 
      if ($_COOKIE["piphone"] && preg_match("#^[a-z]{32}$#",$_COOKIE["piphone"])) {
	$cookie=$_COOKIE["piphone"];
      } else {
	$cookie=$this->_getRand();
      }
      setCookie("piphone",$cookie,time()+86400*365,"/");
      mq("REPLACE INTO cookies SET cookie='$cookie', country='$country', phone='$phone';");
      
      if ($country) $sql=" AND country='$country' "; else $sql="";
      // Find a MEP to call : 
      $callee=mqone("SELECT * FROM lists WHERE campaign='".$view["campaign"]["id"]."' AND lists.enabled=1 $sql ORDER BY callcount ASC;");
      mq("UPDATE lists SET callcount=callcount+1 WHERE id='".$callee["id"]."'");
      $view["callee"]=$callee;
      mq("INSERT INTO calls SET caller='$phone', callee='".$callee["phone"]."', datestart=NOW(), campaign='".$view["campaign"]["id"]."';");
      $view["callid"]=mysql_insert_id();

      $view["phone"]=$phone;
      // Now proceed to call ...
      render("campaigncall");
      break;

    case 2: // STEP 2 : call proceeding, show a button to give some feedback 
      break;
    }
  }

function callmeAction() {
    global $view,$params;
    if (!isset($params[0])) not_found();
    $callid=intval(trim($params[0]));
    $call=mqone("SELECT * FROM calls WHERE id=$callid AND uuid='';");
    $campaign=mqone("SELECT * FROM campaign WHERE id=".$call["campaign"].";");
    if (!$call || !$campaign) {
      not_found();
    }
    $realphone=preg_replace("#^\+#","00",$call["caller"]);
    $realcallee=preg_replace("#^\+#","00",$call["callee"]);

    // FORCE for PREPROD : 
    if (defined("FORCETO")) {
      $realcallee=FORCETO;
    }

    $uuid=$this->_callback($realphone,$realcallee,$campaign["wavfile"],substr($GLOBALS["lang"],0,2));
    mq("UPDATE calls SET uuid='$uuid' WHERE id='".$callid."';");
    echo "OK";
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


  function feedbackAction() {
    global $view,$params;
    // Check the campaign
    if (!isset($params[0])) not_found();
    $slug=addslashes(trim($params[0]));
    $view["campaign"]=$this->_getCampaign($slug); // Exit in case of error
    // Check the call : 
    if (!isset($params[1])) not_found();
    $view["callid"]=$cid=intval($params[1]);
    $call=mqone("SELECT * FROM calls WHERE id='$cid';");
    if ($call["feedback"]) {
      $view["error"]=_("A feedback has already been given for that call, sorry");
      $this->goAction();
      exit();
    }
    render("feedbackform");
  }



  function feedbackdoAction() {
    global $view,$params;
    // Check the campaign
    if (!isset($params[0])) not_found();
    $slug=addslashes(trim($params[0]));
    $view["campaign"]=$this->_getCampaign($slug); // Exit in case of error
    // Check the call : 
    if (!isset($params[1])) not_found();
    $cid=intval($params[1]);
    $call=mqone("SELECT * FROM calls WHERE id='$cid';");
    if ($call["feedback"]) {
      $view["error"]=_("A feedback has already been given for that call, sorry");
      $this->goAction();
      exit();
    }
    mq("UPDATE calls SET feedback='".addslashes($_REQUEST["feedback"])."' WHERE id='$cid';");
    $view["message"]=_("Your feedback has been sent to us, thanks for your participation!");
    $this->goAction();
  }


}

