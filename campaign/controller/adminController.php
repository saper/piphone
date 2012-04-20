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
    $view["campaign"]=mqlist("SELECT campaign.* FROM campaign ORDER BY enabled DESC");
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
  /** Receive a POST to add or edit a campaign */
  function doAction() {
    global $view;
    $id=intval($_REQUEST["id"]);
    if ($id) {
      $old=mqone("SELECT * FROM campaign WHERE id=$id;");
      if (!$old) not_found();
    }

    // Validate the fields : 
    $fields=array("id","name","slug","description","description-fr","datestart","datestop");
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
    $slug = addslashes($view["campaign"]["slug"]);
    $desc = addslashes($view["campaign"]["description"]);
    $descfr = addslashes($view["campaign"]["description-fr"]);
    $datestart = $view["campaign"]["datestart"];
    $datestop = $view["campaign"]["datestop"];
    $sql = "SET `name`='$name', `slug`='$slug', `description`='$desc', `description-fr`='$descfr', `datestart`='$datestart', `datestop`='$datestop'";
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


}

