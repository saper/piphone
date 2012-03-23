<?php

define("PIPHONE_VERSION","0.1 [codename Graham]");

require_once("config.php");

mysql_query("SET NAMES UTF8;");

require_once(dirname(__FILE__)."/functions_iso.php");
require_once(dirname(__FILE__)."/functions.php");

require_once(dirname(__FILE__)."/lang.php");

//check_user_identity(); // exit if failed.

/* 

   Globals used in this application : 
   $me[] = array of key=>value pair : the current user from the User table.
   $class = name of the part of the application that we are executing (first part of the url which is http://domain.tld/class/action
   $action = the action requested. May be "" (=="list") or "edit", "doedit", "add", "doadd", "delete", "dodelete" ...

*/

// get the class and action from the URL : 
$path=$_SERVER["REQUEST_URI"];
if (strpos($_SERVER["REQUEST_URI"],"?")!==false) {
  $path=substr($_SERVER["REQUEST_URI"],0,strpos($_SERVER["REQUEST_URI"],"?"));
}
@list($class,$action,$params)=explode("/",trim($path,"/"),3);
if (isset($params)) {
  $params=explode("/",$params);
} else {
  $params=array();
}
//echo "class:$class action:$action\n"; 
if (!preg_match("#^[0-9a-z]*$#",$class) || !preg_match("#^[0-9a-z]*$#",$action)) {
  not_found();
}

if ($class=="") $class="index";
if ($action=="") $action="index";


// I'm naming controller and actions like Zend do ;) 
$classfile=strtolower($class)."Controller";
$actionmethod=strtolower($action)."Action";

// Adds here requires for class hierarchy ...
require_once("controller/abstractController.php");

// Now we have class and action and they look nice. Let's instanciate them if possible 
if (!file_exists("controller/".$classfile.".php")) {
  not_found();
}


// We prepare the view array for the rendering of data: 
$view=array();
//$view["me"]=$me;
$view["class"]=$class;
$view["action"]=$action;

define("VIEW_DIR",dirname(__FILE__)."/view");
// We define the view here because the controller class may need it in its constructor ;)


require_once("controller/".$classfile.".php");
$$classfile=new $classfile();

if (!method_exists($$classfile,$actionmethod)) {
  error("Method not found");
  not_found();
}

// We launch the requested action.
// in "<class>Controller" class, we launch "<action>Action" method : 
$$classfile->$actionmethod();
// This action will either do a redirect(); to point to another page, 
// or do a render($viewname) to render a view 


?>