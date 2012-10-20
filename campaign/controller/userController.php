<?php

class userController extends abstractController {

  private _protected() {
    check_user_identity();
    if (!is_admin()) {
      error("Permission Denied on userController");
      not_found();
    }
  }

  /* ************************************************************************ */
  /** This entire controller requires the RIGHT_USER
   __constructor : 
  */
  function userController() {
  }

  /* ************************************************************************ */
  /** Get the list of user accounts */
  function indexAction() {
    _protected();
    global $view;
    $view["user"]=mqlist("SELECT user.* FROM user ORDER BY user.login;");
    render("userlist");
  }

  /* ************************************************************************ */
  /** Show the form to add a new user account */
  function addAction() {
    global $view;
    $view["title"]="User account creation";
    $view["actionname"]="Create this user account";
    render("userform");
  }

  /* ************************************************************************ */
  /** Show the form to edit an existing user account */
  function editAction() {
    _protected();
    global $view,$params;
    if (!isset($params[0])) not_found();
    $id=intval($params[0]);
    if (!$id) not_found();
    $user=mqone("SELECT * FROM user WHERE id=$id;");
    if (!$user) not_found();
    $view["title"]="Editing user account ".$user["login"];
    $view["actionname"]="Edit this user account";
    $view["user"]=$user;
    list($view["user"]["parentlogin"])=mqone("SELECT login FROM user WHERE id='".$view["user"]["parent"]."';");
    unset($view["user"]["pass"]);
    render("userform");
  }

  /* ************************************************************************ */
  /** Receive a POST to add or edit a user account */
  function doAction() {
    global $view;
    $id=intval($_REQUEST["id"]);
    if ($id) {
      $old=mqone("SELECT * FROM user WHERE id=$id;");
      if (!$old) not_found();
    }

    // Validate the fields : 
    $fields=array("id","login","pass","email","enabled","admin");
    foreach($fields as $f) $view["user"][$f]=$_REQUEST[$f]; 

    if ($_REQUEST["pass"]!=$_REQUEST["pass2"]) {
      $view["error"]="Passwords do not match, please check";
      $this->_cancelform($id,$old["login"]);
      return;
    }
    $already=mqone("SELECT * FROM user WHERE login='".addslashes($view["user"]["login"])."' AND id!='$id';");
    if ($already) {
      $view["error"]="That login already exists, please use another one.";
      $this->_cancelform($id,$old["login"]);
      return;      
    }

    $sqlpass="";
    if ($view["user"]["pass"]) {
      $sqlpass=" pass=PASSWORD('".addslashes($view["user"]["pass"])."'), ";
    }
    $sql="          login='".addslashes($view["user"]["login"])."',
          email='".addslashes($view["user"]["email"])."',
          enabled='".addslashes($view["user"]["enabled"])."',
          admin='".addslashes($view["user"]["admin"])."'";

    if ($id) {
      // Update user: 
      mq("UPDATE user SET $sqlpass $sql WHERE id='$id' ;");
      $view["message"]="The user has been edited successfully";
    } else {
      // Create user:
      mq("INSERT INTO user SET $sqlpass $sql;");
      $view["message"]="The user has been created successfully";
    }
    $this->indexAction();
  } // doAction

  // Go back to the form, having the right params : 
  private function _cancelform($id,$login="") {
    global $view;
    if ($id) {
      $view["title"]="Editing user account ".$login;
      $view["actionname"]="Edit this user account";
    } else {
      $view["title"]="User account creation";
      $view["actionname"]="Create this user account";
    }
    render("userform");
  }

  /* ************************************************************************ */
  /** Show the form to confirm when deleting a user */
  function delAction() {
    _protected();
    global $view,$params;
    if (!isset($params[0])) not_found();
    $id=intval($params[0]);
    $user=mqone("SELECT * FROM user WHERE id=$id;");
    if (!$user) not_found();
    $view["title"]="Deleting user account ".$user["login"];
    $view["user"]=$user;
    unset($view["user"]["pass"]);
    render("userdel");
  }

  /* ************************************************************************ */
  /** Receive a POST to del a user account */
  function dodelAction() {
    _protected();
    global $view;
    $id=intval($_REQUEST["id"]);
    $user=mqone("SELECT * FROM user WHERE id=$id;");
    if (!$user) not_found();
    mq("DELETE FROM user WHERE id=$id;");    
    $view["message"]="The user has been deleted successfully";
    $this->indexAction();
  } // dodelAction

  /* ************************************************************************ */
  /** Receive a URL to disable a user account */
  function disableAction() {
    _protected();
    global $view,$params;
    if (!isset($params[0])) not_found();
    $id=intval($params[0]);
    $user=mqone("SELECT * FROM user WHERE id=$id;");
    if (!$user) not_found();
    mq("UPDATE user SET enabled=0 WHERE id=$id;");    
    $view["message"]="The user has been disabled successfully";
    $this->indexAction();
  }

  /* ************************************************************************ */
  /** Receive a URL to enable a user account */
  function enableAction() {
    _protected();
    global $view,$params;
    if (!isset($params[0])) not_found();
    $id=intval($params[0]);
    $user=mqone("SELECT * FROM user WHERE id=$id;");
    if (!$user) not_found();
    mq("UPDATE user SET enabled=1 WHERE id=$id;");    
    $view["message"]="The user has been enabled successfully";
    $this->indexAction();
  }
}
