<?php

class loginController extends abstractController {

  private function _getRandToken() {
    $chars = '0123456789abcdefghijklmnopqrsrtuvwxyz';
   
    for ($p = 0; $p < 12; $p++) {
        $result .= $chars[mt_rand(0,36)];
    }
    return $result;
  }

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
      $view["login"]=mqlist("SELECT user.* FROM user WHERE user.login='".$_SESSION["id"]["login"]."';");
      $view["campaigns"]=mqlist("SELECT campaign.name, count(campaign.id) score FROM campaign, calls WHERE calls.uid=".$_SESSION["id"]["id"]." AND calls.campaign = campaign.id;");
      $view["calls"]=mqlist("SELECT lists.campaign, lists.name FROM lists, calls WHERE lists.phone = calls.callee AND calls.uid = ".$_SESSION["id"]["id"]. ";");
      render("logindetail");
    } else {
      render("loginregister");
    }
  }

  /* ************************************************************************ */
  /** Login */
  function authAction() {
    global $view;
    if (!isset($_SESSION["id"])) {
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
      $id=mqone("SELECT user.* FROM user WHERE user.login = '".$_REQUEST["user"]."' AND PASSWORD('".$_REQUEST["password"]."') = user.pass AND enabled = 1;");
      if (!$id) {
        $view["error"] .= "Incorrect login or password. Or account disabled.";
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
    session_destroy();
    header("Location: /");
  }

  /* ************************************************************************ */
  /** Show the form to add a new login account */
  function registerAction() {
    global $view;
    $view["title"]="Create an account for the piphone";
    $view["actionname"]="Create this account";
    render("loginform");
  }

  function checkAction() {
    global $view;
    $view["title"]="Create an account for the piphone";
    $view["actionname"]="Create this account";
    $email=$_REQUEST["email"];
    $password=$_REQUEST["password"];
    $password2=$_REQUEST["password2"];

    $exist=mqone("SELECT login FROM user WHERE login='".$_REQUEST["login"]."';");
    if ($exist) { $view["login"] = $_REQUEST["login"]; $invalid=True; }

    $valid=filter_var($_REQUEST["email"], FILTER_VALIDATE_EMAIL);
    if (!$valid) {$view["email"] = $_REQUEST["email"]; $invalid=True; }

    if (strcmp($_REQUEST["password"], $_REQUEST["password2"]) != 0 ) { $view["password"] = "1"; $invalid=True; }

    if (isset($invalid)) { render("loginform"); exit(); }

    $token=$this->_getRandToken();
    mq("INSERT INTO user (login,pass,email,enabled,admin,token) 
        VALUES ('".$_REQUEST["login"]."',PASSWORD('".$_REQUEST["password"]."'),'".$_REQUEST["email"]."','0','0','$token');");
    $id = mqone("SELECT id FROM user WHERE login = '" . $_REQUEST["login"] . "';");
    if ($_SERVER["HTTPS"] != "") {$protocol = "https";} else {$protocol = "http";}
    $url = "$protocol://" . $_SERVER["HTTP_HOST"] . "/login/validate/" . $id[0] ."/" . $token . "/";

    // Prepare a validation email
    $to = $_REQUEST["email"];
    $subject = "Ohai ".$_REQUEST["login"].", welcome to the piphone.";
    $headers = "From: piphone@lqdn.fr\n" . "Reply-To: no-reply@lqdn.fr\n". "X-Mailer: PHP/" . phpversion();
    $message = "Ohai,\n"
       . "\n"
       . "You have requested an account on the piphone. You just need to open the following link in your browser.\n"
       . "\t\t<a href=\"$url\">$url</a>\n"
       . "\n"
       . "If you have not required such account, just ignore this mail.\n"
       . "\n"
       . "With datalove, La Quadrature Du Net.\n";
    mail($to, $subject, $headers, $message);

    render("loginvalidate");
  }

  /* ************************************************************************ */
  /** Validate the account */
  function validateAction() {
    global $view,$params;
    if (!isset($params[0])) not_found();
    $id=intval($params[0]);

    if (!isset($params[1])) not_found();
    $token=$params[1];

    // Get the token stored in database
    $data=mqassoc("SELECT id, token FROM user WHERE id = '$id'");

    $view["message"] .= "Account validated, you can now login";

    if (strcmp($token, $data[$id]) != 0) not_found();
    mq("UPDATE user SET enabled='1', token='' WHERE id='$id'");
    render("loginauth");
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
  /** Reset the password */
  function pwresetAction() {
    global $view;
    render("loginreset");
  }

  function doresetAction() {
    global $view;
    if (!$_REQUEST["user"]) not_found();
    $login = $_REQUEST["user"];

    // The account must be enabled and have no token
    $account=mqone("SELECT user.* FROM user WHERE login='$login' AND enabled='1' AND token='';");
    if (!isset($account)) not_found();
    $tmp_password=$this->_getRandToken();
    $token=$this->_getRandToken();

    // Disable the account and set the tmp_password
    mq("UPDATE user SET enabled='0', pass=PASSWORD('$tmp_password'), token='$token' WHERE id='".$account[0]."';");

    // Prepare a reset email
    $to = $account["email"];
    $subject = "Ohai ".$account["login"].", you've asked for a new password.";
    $headers = "From: piphone@lqdn.fr\n" . "Reply-To: no-reply@lqdn.fr\n". "X-Mailer: PHP/" . phpversion();
    if ($_SERVER["HTTPS"] != "") {$protocol = "https";} else {$protocol = "http";}
    $url = "$protocol://" . $_SERVER["HTTP_HOST"] . "/login/validate/" . $account["id"] ."/" . $token . "/";
    $message = "Ohai,\n"
       . "\n"
       . "You have requested a password reste on the piphone. Your account have been disabled and a new password have been generated.\n"
       . "You just need to open the following link in your browser to enable your account and you will be asked to login with the new password.\n"
       . "\t\t<a href=\"$url\">$url</a>\n"
       . "\n"
       . "Your new password is: $tmp_password\n"
       . "\n"
       . "With datalove, La Quadrature Du Net.\n";
    
    mail($to, $subject, $headers, $message);
    render("loginauth");
  }

  /* Change the password */
  function pwchangeAction() {
    global $view;
    if (!isset($_SESSION["id"]))
      render("loginauth");

    if (!isset($_REQUEST["current_pw"]) or !isset($_REQUEST["new_pw"]) or !isset($_REQUEST["new_pw2"]))
    {
      $view["message"] .= "You must fill all the fields.";
      render("loginindex");
    }
    if (strcmp($_REQUEST["new_pw"] != $_REQUEST["new_pw2"]))
    {
      $view["message"] .= "New passwords do not match.";
      render("loginindex");
    }

    $old_pw=mqone("SELECT user.password FROM user WHERE id='".$_SESSION["id"]["id"]."' AND password=PASSWORD('".$_REQUEST["current_pw"]."');");
    if (!$old_pw)
    {
      $view["message"] .= "Wrong password";
      render("loginindex");
    }

    mq("UPDATE user SET password=PASSWORD('".$_REQUEST["new_pw2"]."') WHERE id='".$_SESSION["id"]["id"]."';");   
    $this->indexAction();
  }

  /* Update the email or login name */
  function updateAction() {
    global $view;
    if (!isset($_SESSION["id"]))
      render("loginauth");
    $sql = '';

    if ($_REQUEST["login"])
    {
      if (mqone("SELECT user.* FROM user WHERE login='".$_REQUEST["login"]."' AND enabled='1';"))
      {      
        $view["message"] .= "This login name already exist.";
        $this->indexAction();
        return;
      }  
      $sql .= "login='" . $_REQUEST["login"] ."'";
    }

    if ($_REQUEST["email"])
    {
      if ($sql != '')
        $sql .= ', ';
      $sql .= "email='" . $_REQUEST["email"] . "'";
    }

    if ($sql != '')
    {
      mq("UPDATE user SET $sql WHERE id='".$_SESSION["id"]["id"]."';");
    }

    $account=mqone("SELECT user.* FROM user WHERE id='".$_SESSION["id"]["id"]."' AND enabled='1' AND token='';");

    $_SESSION["id"] = $account;
    session_write_close();
    $this->indexAction();
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
}
