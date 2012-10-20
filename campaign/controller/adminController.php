<?php

class adminController extends abstractController {


  private $countryCodes = array(
     "Austria" => "AU",     "Belgium" => "BE",     "Chyprus" => "CY",
     "Czech" => "CZ" ,     "Denmark" => "DK",     "Estonia" => "EE",
     "Finland" => "FI",      "France" => "FR",     "Germany" => "DE",
     "Greece" => "GR",     "Hungary" => "HU",     "Ireland" => "IE",
     "Italy" => "IT",     "Latvia" => "LV",     "Lithuania" => "LT",
     "Luxembourg" => "LU",     "Malta" => "MT",     "Netherlands" => "NL",
     "Poland" => "PL",     "Portugal" => "PT",     "Slovakia" => "SK",
     "Slovenia" => "SI",     "Spain" => "SP",     "Sweden" => "SE",
     "United Kingdom" => "UK",
                                     );

  /* ************************************************************************ */
  /** This entire controller requires the RIGHT_USER
   __constructor : 
  */
  function adminController() {
    check_user_identity();
    if (!is_admin()) {
      error("Permission Denied on adminController");
      not_found();
    }
  }
  

  /* ************************************************************************ */
  /** Get the list of campaigns */
  function indexAction() {
    global $view;
    $view["campaign"]=mqlist("SELECT campaign.*, IF(datestart<=NOW() AND datestop>=NOW(),0,1) AS expired FROM campaign ORDER BY enabled DESC");
    for($i=0;$i<count($view["campaign"]);$i++) {
      $view["campaign"][$i]["count"]=mqonefield("SELECT COUNT(*) FROM lists WHERE campaign=".$view["campaign"][$i]["id"]);
      $view["campaign"][$i]["calls"]=mqonefield("SELECT COUNT(*) FROM calls WHERE campaign=".$view["campaign"][$i]["id"]." AND (uuid!='' OR feedback!='')");
    }
    render("adminlist");
  }



  /* ************************************************************************ */
  /** Show the form to add a new campaigns */
  function addAction() {
    global $view;
    $view["title"]="Campaign creation";
    $view["actionname"]="Create this campaign";
    render("adminform");
  }


  /* ************************************************************************ */
  /** Show the form to edit an existing campaign account */
  function editAction() {
    global $view,$params;
    if (!isset($params[0])) not_found();
    $id=intval($params[0]);
    if (!$id) not_found();
    $campaign=mqone("SELECT * FROM campaign WHERE id=$id;");
    if (!$campaign) not_found();
    $view["title"]="Editing campaign ".$campaign["name"];
    $view["actionname"]="Edit this campaign";
    $view["campaign"]=$campaign;
    list($view["campaign"]["parentname"])=mqone("SELECT name FROM campaign WHERE id='".$view["campaign"]["parent"]."';");
    render("adminform");
  }

  /* ************************************************************************ */
  /** Show the form to edit the list of callees for this campaign */
  function listAction() {
    global $view,$params;
    if (!isset($params[0])) not_found();
    $id=intval($params[0]);
    if (!$id) not_found();
    $campaign=mqone("SELECT * FROM campaign WHERE id=$id;");
    if (!$campaign) not_found();
    $list=mqlist("SELECT * FROM lists WHERE campaign=$id;");
    $view["title"]="Editing list of callees forcampaign ".$campaign["name"];
    $view["actionname"]="Apply the changes to this campaign";
    $view["campaign"]=$campaign;
    $view["list"]=$list;
    render("adminlistcallee");
  }
  

  /* ************************************************************************ */
  /** Show the form to import a csv in this campaign */
  /** the csv is field separated by semi colon and each field is surrounded by "*/
  function importAction() {
    global $view, $params;
	if (!isset($params[0])) not_found();
	$id=intval($params[0]);
	if (!$id) not_found();
	$campaign=mqone("SELECT * FROM campaign WHERE id=$id;");
	if (!$campaign) not_found();
	$view["title"]="Importing a CSV to populate the campaign ".$campaign["name"];
	$view["actionname"]="Replace the campaign list with this one";
	$view["campaign"]=$campaign;
	render("adminlistimport");
  }

  /* ************************************************************************ */
  /** Receive a POST to enable or disable people in a campaign */
  function dolistAction() {
    global $view;
    $id=intval($_REQUEST["id"]);
    if ($id) {
      $old=mqone("SELECT * FROM campaign WHERE id=$id;");
      if (!$old) not_found();
    }
    $list=mqassoc("SELECT id,enabled FROM lists WHERE campaign=$id;");
    $score=mqassoc("SELECT id,pond_scores FROM lists WHERE campaign=$id;");
    
    // Validate the fields : 
    foreach($_REQUEST["callee"] as $cid=>$action) {
      $cid=intval($cid); $action=intval($action);
      if ($list[$cid]!=$action) {
	mq("UPDATE lists SET enabled='$action' WHERE campaign=$id AND id='$cid';");
	if ($action) { 
	  $view["messages"].="$cid enabled. ";
	} else {
	  $view["messages"].="$cid disabled. ";
	}
      }
    }
    foreach($_REQUEST["score"] as $cid=>$pscore) {
      $cid=intval($cid); $pscore=intval($pscore);
      if ($score[$cid]!=$pscore) {
        if ($pscore > 100) $pscore = 100;
        if ($pscore < 0) $pscore = 0;
        mq("UPDATE lists SET pond_scores='$pscore' WHERE campaign=$id AND id='$cid';");
        $view["messages"].="$cid score is $pscore. ";
      }
    }

    $this->indexAction();
  } // doAction

  /* ************************************************************************ */
  /** Receive a POST to import a CSV into a campaign */
  function doimportAction() {
    global $view;

    $id=intval($_REQUEST["id"][0]);
    if (!$id) not_found();
    $campaign=mqone("SELECT * FROM campaign WHERE id=$id;");
    if (!$campaign) not_found();

    // get the file. 
    if ($_FILES["file"]["error"] != 0 ) not_found();
    $filename="/home/okhin/pp/campaign/csv/".$campaign["slug"].".csv";
    if (!move_uploaded_file($_FILES["file"]["tmp_name"], $filename)) not_found();
	
    // Before going further, we want to trash the existing campaign
    mq("DELETE FROM lists WHERE campaign=$id;");
    // parse everything
    if (($file = fopen("$filename","r")) == False) not_found();
    $line_number=0;

    while (($csv = fgetcsv($file,0,';')) == True) {
      // The data in the CSV are the mandatory field for the list table,in this order:
      // "name";"url";"phone number";"country code";"score"
      if (count($csv) < 5) {
        $view["messages"] .= "Error on line $line_number: $csv. ";
	continue;
      }

      $name=$csv[0];
      $url=$csv[1];
      $phone=$csv[2];
      $country=$csv[3];
      $score=$csv[4];
      $line_number++;

      // Validation
	  // TODO

	  // Parse the parltrack URL
	  $parltrack=fopen("$url","r");
      while (($line = fgets($parltrack)) !== False) {
	    $json .= $line;
	  }

	  $parl_mep=json_decode($json, true);
	  foreach ($parl_mep["Mail"] as $mail) {
	    $mep["mail"][] = $mail;
	  }

	  $mep["stb"] = str_replace(' ','',$parl_mep["Addresses"]["Strasbourg"]["Phone"]);
	  $mep["bxl"] = str_replace(' ','',$parl_mep["Addresses"]["Strasbourg"]["Phone"]);
	  $mep["group"] = $parl_mep["Groups"][0]["groupid"];
	  $mep["name"] = $parl_mep["Name"]["full"];
	  $mep["url"] = $parl_mep["Homepage"];
	  $mep["country"] = $this->countryCodes[$parl_mep["Constituencies"][0]["country"]];
	  $mep["party"] = $parl_mep["Constituencies"][0]["party"];
	  foreach ($parl_mep["Committees"] as $committee){
	    $mep["committee"][] = $committee["abbr"];
	  }
          $mep["picurl"] = $parl_mep["Photo"];

	  $meta=@serialize($mep);
	  fclose($parltrack);

	  // INSERT
      mq("INSERT INTO lists SET
        campaign='$id',
	name='$name',
	url='$url',
	phone='$phone',
	scores='$score',
	pond_scores='$score',
	country='".$mep["country"]."',
	meta='$meta',
	callcount=0, enabled=1
	;"
      );

    }
    fclose($file);
    $view["messages"] .= "Imported $line_number lines from file.";
    $this->indexAction();
  }

  /* ************************************************************************ */
  /** Receive a POST to add or edit a campaign */
  function doAction() {
    global $view;
    $id=intval($_REQUEST["id"]);
    if ($id) {
      $old=mqone("SELECT * FROM campaign WHERE id=$id;");
      if (!$old) not_found();
    }

    // Validate the fields : 
    $fields=array("id","name","name-fr","slug","longname", "longname-fr", "description","description-fr","datestart","datestop");
    foreach($fields as $f) $view["campaign"][$f]=$_REQUEST[$f]; 

    if (!$tstart = strtotime($_REQUEST["datestart"])) {
      $view["error"]="Start date is not valid";
      $this->_cancelform($id,$old["name"]);
      return;
    }
    if (!$tstop = strtotime($_REQUEST["datestop"])) {
      $view["error"]="End date is not valid";
      $this->_cancelform($id,$old["name"]);
      return;
    }
    if ($tstart >= $tstop) {
      $view["error"]="Start date is after end date";
      $this->_cancelform($id,$old["name"]);
      return;
    }
	
    $already=mqone("SELECT * FROM campaign WHERE slug='".addslashes($view["campaign"]["slug"])."' AND id!='$id';");
    if ($already) {
      $view["error"]="That slug already exists, please use another one.";
      $this->_cancelform($id,$old["name"]);
      return;      
    }

    $name = addslashes($view["campaign"]["name"]);
    $namefr = addslashes($view["campaign"]["name-fr"]);
    $slug = addslashes($view["campaign"]["slug"]);
    $desc = addslashes($view["campaign"]["description"]);
    $descfr = addslashes($view["campaign"]["description-fr"]);
    $datestart = addslashes($view["campaign"]["datestart"]);
    $datestop = addslashes($view["campaign"]["datestop"]);
    // $sql = "SET `name`='$name', `slug`='$slug', `description`='$desc', `description-fr`='$descfr', `datestart`='$datestart', `datestop`='$datestop';";
    $sqls = array();
    foreach($view["campaign"] as $fname => $fdata) {
      $fdata = addslashes($fdata);
      $sqls[] = "`$fname`='$fdata'";
    }
    $sql = "SET " . implode(", ", $sqls);

    if ($id) {
      // Update campaign: 
      mq("UPDATE campaign $sql WHERE id='$id' ;");
      $view["message"]="The campaign has been edited successfully";
    } else {
      // Create campaign:
      mq("INSERT INTO campaign $sql;");
      $view["message"]="The campaign has been created successfully";
    }
    $this->indexAction();
  } // doAction

  // Go back to the form, having the right params : 
  private function _cancelform($id,$name="") {
    global $view;
    if ($id) {
      $view["title"]="Editing campaign ".$name;
      $view["actionname"]="Edit this campaign";
    } else {
      $view["title"]="Campaign creation";
      $view["actionname"]="Create this campaign";
    }
    render("adminform"); 
  }

  /* ************************************************************************ */
  /** Show the form to confirm when deleting a campaign */
  function delAction() {
    global $view,$params;
    if (!isset($params[0])) not_found();
    $id=intval($params[0]);
    $campaign=mqone("SELECT * FROM campaign WHERE id=$id;");
    if (!$campaign) not_found();
    $view["title"]="Deleting campaign ".$campaign["name"];
    $view["campaign"]=$campaign;
    render("admindel");
  }

  /* ************************************************************************ */
  /** Receive a POST to del a campaign */
  function dodelAction() {
    global $view;
    $id=intval($_REQUEST["id"]);
    $campaign=mqone("SELECT * FROM campaign WHERE id=$id;");
    if (!$campaign) not_found();
    mq("DELETE FROM campaign WHERE id=$id;");    
    $view["message"]="The campaign has been deleted successfully";
    $this->indexAction();
  } // dodelAction

  /* ************************************************************************ */
  /** Receive a URL to disable a campaign */
  function disableAction() {
    global $view,$params;
    if (!isset($params[0])) not_found();
    $id=intval($params[0]);
    $campaign=mqone("SELECT * FROM campaign WHERE id=$id;");
    if (!$campaign) not_found();
    mq("UPDATE campaign SET enabled=0 WHERE id=$id;");    
    $view["message"]="The campaign has been disabled successfully";
    $this->indexAction();
  }

  /* ************************************************************************ */
  /** Receive a URL to enable a campaign */
  function enableAction() {
    global $view,$params;
    if (!isset($params[0])) not_found();
    $id=intval($params[0]);
    $campaign=mqone("SELECT * FROM campaign WHERE id=$id;");
    if (!$campaign) not_found();
    mq("UPDATE campaign SET enabled=1 WHERE id=$id;");    
    $view["message"]="The campaign has been enabled successfully";
    $this->indexAction();
  }

  /* ************************************************************************ */
  /** Display stats for a given campaign */
  function statsAction() {
    global $view,$params;
    if(!isset($params[0])) not_found();
    $id=intval($params[0]);
    $campaign=mqone("SELECT name FROM campaign WHERE id=$id;");
    if (!$campaign) not_found();
    $view["title"]="Showing stats for campaign ".$campaign["name"];
    $view["rawstats"]=mqlist("SELECT calls.*, CONCAT(lists.name, ' ', calls.callee) as callee2 FROM calls INNER JOIN lists ON calls.callee = lists.phone WHERE calls.campaign=$id and lists.campaign=$id ORDER BY datestart DESC");
    $view["withuuid"]=array_filter($view["rawstats"],function($a) {return($a["uuid"]!="");});
    $view["withfeedback"]=array_filter($view["rawstats"],function($a) {return($a["feedback"]!="");});
    render("adminstats");
  }
}
