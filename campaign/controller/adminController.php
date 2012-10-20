<?php

class adminController extends abstractController {


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
    global $viewn, $params;
	if (!isset($params[0])) not_found();
	$id=intval($params[0]);
	if (!$id) not_found();
	$campaign=mqone("SELECT * FROM campaign WHERE id=$id;");
	if (!$campaign) not_found();
	$view["title"]="Importing a CSV to populate the campaign ".$campaign["name"];
	$view["actionname"]="Replace the campaign lists with this one";
	$view["campaign"]=$campaign;
	$view["list"]=$list;
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
    
    // Validate the fields : 
    foreach($_REQUEST["callee"] as $cid=>$action) {
      $cid=intval($cid); $action=intval($action);
      if ($list[$cid]!=$action) {
	mq("UPDATE lists SET enabled='$action' WHERE campaign=$id AND id='$cid';");
	if ($action) { 
	  $view["message"].="$cid enabled. ";
	} else {
	  $view["message"].="$cid disabled. ";
	}
      }
    }

    $this->indexAction();
  } // doAction

  /* ************************************************************************ */
  /** Receive a POST to import a CSV into a campaign */
  function doimportAction() {
    global $view, $errors;

	$id=intval($_REQUEST["$id"]);
	if (!$id) not_found();
	$campaign=mqone("SELECT * FROM campaign WHERE id=$id;");
	if (!$campaign) not_found();

	// get the file. We do notwant to save it, so tmp ones are good.
	if (!$_FILES["file"]["error"]) not_found();
	$filename="/csv/".$view["campaign"]["slug"].".csv";
	if (move_uploaded_files($_FILES["file"]["tmp_name"], $filename) != True ) not_found();
	
	// Before going further, we want to trash the existing campaign
	mq("DELETE FROM lists WHERE campaign=$id;");
	// parse everything
    if (($file = fopen("$filename","r")) == False) not_found();
	$errors=array();
    $line_number=0

	foreach (fgetcsv($file, '2000', ';', '"', '\\') as $csv) {
	  // The data in the CSV are the mandatory field for the list table,in this order:
	  // "name";"url";"phone number";"country code";"score"
	  if (count($csv) < 5) {
	    $errors[$line_number] = "$csv";
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

	  // INSERT
	  mq("INSERT INTO lists SET
	    campaign='$id',
		name='$name',
		url='$url',
		phone='$phone',
		scores='$score',
		pond_scores='$score',
		country='$country',
		callcount=0, enabled=1
		;"
      );
	}
    $view["title"] = "Import status for campaign " . $campaign["name"];
	$view["campaign"] = $campaign;
	$view["lines"] = $line_number;
    render("adminlistimported");
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
