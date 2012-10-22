<?php

class loginController extends abstractController {


  /* ************************************************************************ */
  /** This entire controller requires the RIGHT_login
   __constructor : 
  */
  function loginController() {
    $GLOBALS["me"] = mqone("SELECT * FROM user WHERE login='".mquote($_SERVER['PHP_AUTH_USER'])."' AND pass=PASSWORD('".mquote($_SERVER['PHP_AUTH_PW'])."') AND enabled=1;");
  }
  

  /* ************************************************************************ */
  /** Get the deatils of the currently logged person */
  function indexAction() {
    global $view;
    if (isset($GLOBALS["me"])) {
      $view["login"]=mqlist("SELECT login.* FROM login WHERE login.login=".$GLOBALS["me"]["login"].";");
	  $view["campaigns"]=mqlist("SELECT campaign.name, count(1) FROM campaign, lists WHERE lists.uuid=".$GLOBALS["me"]["id"]." AND lists.campaign = campaign.id GROUP BY lists.campaign;");
	  $view["calls"]=mqlist("SELECT lists.campaign, lists.name FROM lists, calls WHERE lists.phone = calls.callee AND calls.uuid = ".$GLOBALS["me"]["id"]. ";");
      render("logindetail");
    } else {
      render("loginregister");
  }

  /* ************************************************************************ */
  /** Logout */
  function logoutAction() {
    header("Location: ".$_SERVER("HTTP_HOST")."/");
  }

  /* ************************************************************************ */
  /** Show the form to add a new login account */
  function addAction() {
    global $view;
    $view["title"]="Create an account for the piphone";
    $view["actionname"]="Create this account";
    render("loginform");
  }


  /* ************************************************************************ */
  /** Show the form to edit an existing login account */
  function editAction() {
    global $view,$params;
    if (!isset($params[0])) not_found();
    $id=intval($params[0]);
    if (!$id) not_found();
    $login=mqone("SELECT * FROM login WHERE id=$id;");
    if (!$login) not_found();
    $view["title"]="Editing login account ".$login["login"];
    $view["actionname"]="Edit this login account";
    $view["login"]=$login;
    list($view["login"]["parentlogin"])=mqone("SELECT login FROM login WHERE id='".$view["login"]["parent"]."';");
    unset($view["login"]["pass"]);
    render("loginform");
  }
  

  /* ************************************************************************ */
  /** Receive a POST to add or edit a login account */
  function doAction() {
    global $view;
    $id=intval($_REQUEST["id"]);
    if ($id) {
      $old=mqone("SELECT * FROM login WHERE id=$id;");
      if (!$old) not_found();
    }

    // Validate the fields : 
    $fields=array("id","login","pass","email","enabled","admin");
    foreach($fields as $f) $view["login"][$f]=$_REQUEST[$f]; 

    if ($_REQUEST["pass"]!=$_REQUEST["pass2"]) {
      $view["error"]="Passwords do not match, please check";
      $this->_cancelform($id,$old["login"]);
      return;
    }
    $already=mqone("SELECT * FROM login WHERE login='".addslashes($view["login"]["login"])."' AND id!='$id';");
    if ($already) {
      $view["error"]="That login already exists, please use another one.";
      $this->_cancelform($id,$old["login"]);
      return;      
    }

    $sqlpass="";
    if ($view["login"]["pass"]) {
      $sqlpass=" pass=PASSWORD('".addslashes($view["login"]["pass"])."'), ";
    }
    $sql="          login='".addslashes($view["login"]["login"])."',
          email='".addslashes($view["login"]["email"])."',
          enabled='".addslashes($view["login"]["enabled"])."',
          admin='".addslashes($view["login"]["admin"])."'";

    if ($id) {
      // Update login: 
      mq("UPDATE login SET $sqlpass $sql WHERE id='$id' ;");
      $view["message"]="The login has been edited successfully";
    } else {
      // Create login:
      mq("INSERT INTO login SET $sqlpass $sql;");
      $view["message"]="The login has been created successfully";
    }
    $this->indexAction();
  } // doAction


  // Go back to the form, having the right params : 
  private function _cancelform($id,$login="") {
    global $view;
    if ($id) {
      $view["title"]="Editing login account ".$login;
      $view["actionname"]="Edit this login account";
    } else {
      $view["title"]="login account creation";
      $view["actionname"]="Create this login account";
    }
    render("loginform");
  }


  /* ************************************************************************ */
  /** Show the form to confirm when deleting a login */
  function delAction() {
    global $view,$params;
    if (!isset($params[0])) not_found();
    $id=intval($params[0]);
    $login=mqone("SELECT * FROM login WHERE id=$id;");
    if (!$login) not_found();
    $view["title"]="Deleting login account ".$login["login"];
    $view["login"]=$login;
    unset($view["login"]["pass"]);
    render("logindel");
  }


  /* ************************************************************************ */
  /** Receive a POST to del a login account */
  function dodelAction() {
    global $view;
    $id=intval($_REQUEST["id"]);
    $login=mqone("SELECT * FROM login WHERE id=$id;");
    if (!$login) not_found();
    mq("DELETE FROM login WHERE id=$id;");    
    $view["message"]="The login has been deleted successfully";
    $this->indexAction();
  } // dodelAction





  /* ************************************************************************ */
  /** Receive a URL to disable a login account */
  function disableAction() {
    global $view,$params;
    if (!isset($params[0])) not_found();
    $id=intval($params[0]);
    $login=mqone("SELECT * FROM login WHERE id=$id;");
    if (!$login) not_found();
    mq("UPDATE login SET enabled=0 WHERE id=$id;");    
    $view["message"]="The login has been disabled successfully";
    $this->indexAction();
  }


  /* ************************************************************************ */
  /** Receive a URL to enable a login account */
  function enableAction() {
    global $view,$params;
    if (!isset($params[0])) not_found();
    $id=intval($params[0]);
    $login=mqone("SELECT * FROM login WHERE id=$id;");
    if (!$login) not_found();
    mq("UPDATE login SET enabled=1 WHERE id=$id;");    
    $view["message"]="The login has been enabled successfully";
    $this->indexAction();
  }


}

