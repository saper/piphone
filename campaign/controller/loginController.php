<?php

class loginController extends abstractController {

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
  /** This entire controller requires the RIGHT_login
   __constructor : 
  */
  function loginController() {
  }
  

  /* ************************************************************************ */
  /** Get the details of the currently logged person */
  function indexAction() {
    global $view;
    if (isset($_SESSION["id"])) {
      $view["login"]=mqlist("SELECT user.* FROM user WHERE user.login=".$_SESSION["id"]["login"].";");
      $view["campaigns"]=mqlist("SELECT campaign.name, count(1) FROM campaign, calls WHERE calls.uuid=".$_SESSION["id"]["login"]." AND calls.campaign = campaign.id;");
      $view["calls"]=mqlist("SELECT lists.campaign, lists.name FROM lists, calls WHERE lists.phone = calls.callee AND calls.uuid = ".$_SESSION["id"]["login"]. ";");
      render("logindetail");
    } else {
      render("loginregister");
    }
  }

  /* ************************************************************************ */
  /** Login */
  function authAction() {
    global $view;
    if (!isset($_SESSION)) {
      render("loginauth");
    } else {
      $view["warning"] .= "Already logged in.";
      render("logindetail");
    }
  }

  function doauthAction() {
    global $view;
    if (isset($_SESSION["id"])) {
      $view["warning"] .= "Already logged in.";
      render("logindetail");
    } else {
      $id=mqone("SELECT user.* FROM user WHERE user.login = '".$_REQUEST["user"]."' AND PASSWORD('".$_REQUEST["password"]."') = user.pass;");
      if (!$id) {
        $view["error"] .= "Incorrect login or password.";
        render("loginauth");
      }

     $_SESSION["id"] = $id;
     session_write_close();
     render("logindetail");
    }
  }
  /* ************************************************************************ */
  /** Logout */
  function logoutAction() {
    global $view;
    unset($_SESSION["id"]);
    session_write_close();
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