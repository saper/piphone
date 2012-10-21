<?php

function show_messages() {
  global $view;
  if (isset($view["error"])) echo "<div class=\"flash error\">".$view["error"]."</div>"; 
  if (isset($view["warning"])) echo "<div class=\"flash warning\">".$view["warning"]."</div>"; 
  if (isset($view["message"])) echo "<div class=\"flash notice\">".$view["message"]."</div>"; 
}

// todo : change eher to ehe and make it use request !!
function eher($str) { echo htmlentities($str,ENT_COMPAT,"UTF-8"); } 

function ehe($str) { echo htmlentities($str,ENT_COMPAT,"UTF-8"); } 
function her($str) { return htmlentities($str,ENT_COMPAT,"UTF-8"); } 
function he($str) { return htmlentities($str,ENT_COMPAT,"UTF-8"); } 
function checked($bool) { if ($bool) echo " checked=\"checked\""; }

function mq($query) { 
  $q=mysql_query($query); 
  if (!$q) echo "MySQL ERR: ".mysql_error()."\nQUERY WAS: $query\n"; 
  return $q; 
}

function mqone($query) { 
  $q=mq($query);
  if (!$q) return false; 
  else 
    return mysql_fetch_array($q); 
}

function mqonefield($query) { 
  $q=mq($query);
  if (!$q) return false; 
  $res=mysql_fetch_array($q);
  return $res[0];
}

function mqassoc($query) { 
  $q=mq($query); 
  if (!$q) return false; 
  $res=array(); 
  while ($c=mysql_fetch_array($q)) $res[$c[0]]=$c[1]; 
  return $res; 
}

function mqlistone($query) { 
  $q=mq($query); 
  if (!$q) return false; 
  $res=array(); 
  while ($c=mysql_fetch_array($q)) $res[]=$c[0]; 
  return $res; 
}

function mqlist($query) { 
  $q=mq($query); 
  if (!$q) return false; 
  $res=array(); 
  while ($c=mysql_fetch_array($q)) $res[]=$c; 
  return $res; 
}

function mquote($str) {
  return addslashes($str);
}

function format_size($s) {
  if ($s<1024) return $s."B";
  if ($s<1024*1024) return (intval($s/102.4)/10)."KiB";
  if ($s<1024*1024*1024) return (intval($s/102.4/1024)/10)."MiB";
  if ($s<1024*1024*1024) return (intval($s/102.4/1024/1024)/10)."GiB";
  if ($s<1024*1024*1024) return (intval($s/102.4/1024/1024/1024)/10)."TiB";
  return "too big to be printed, surely an error...";
}

// Overload this with a proper gettext function when in multilanguage env.
function _l($str) { return $str; } 
function __($str) { echo _($str); } 


/* select_values($arr,$cur) echo des <option> du tableau $values ou de la table sql $values
   selectionne $current par defaut. Par defaut prends les champs 0 comme id et 1 comme 
   donnees pour la table. sinon utilise $info[0] et $info[1].
*/
function eoption($values,$cur,$info="") {
  if (is_array($values)) {
    foreach ($values as $k=>$v) {
      echo "<option value=\"$k\"";
      if ($k==$cur) echo " selected=\"selected\"";
      echo ">".$v."</option>";
    }
  } else {
    if (is_array($info)) {
      $r=mqlist("SELECT ".$info[0].", ".$info[1]." FROM $values ORDER BY ".$info[0].";");
    } else {
      $r=mqlist("SELECT * FROM $values ORDER BY 2;");
    }

    foreach ($r as $c) {
      echo "<option value=\"".$c[0]."\"";
      if ($c[0]==$cur) echo " selected=\"selected\"";
      echo ">".sts($c[1])."</option>";
    }
  }
}


/* Affiche un pager sous la forme suivante : 
  Page précédente 0 1 2 ... 16 17 18 19 20 ... 35 36 37 Page suivante
  Les arguments sont comme suit : 
  $offset = L'offset courant de la page.
  $count = Le nombre d'éléments affiché par page.
  $total = Le nombre total d'éléments dans l'ensemble
  $url = L'url à afficher. %%offset%% sera remplacé par le nouvel offset des pages.
  $before et $after sont le code HTML à afficher AVANT et APRES le pager SI CELUI CI DOIT ETRE AFFICHE ...
  TODO : ajouter un paramètre class pour les balises html A.
  */
function pager($offset,$count,$total,$url,$before="",$after="") {
  //  echo "PAGER : offset:$offset, count:$count, total:$total, url:$url<br />";
  $offset=intval($offset); 
  $count=intval($count); 
  $total=intval($total); 
  if ($offset<=0) $offset="0";
  if ($count<=1) $count="1";
  if ($total<=0) $total="0";
  if ($total<$offset) $offset=max(0,$total-$count);

  if ($total<=$count) { // When there is less element than 1 complete page, just don't do anything :-D
    return true;
  }
  echo $before;
  // Shall-we show previous page link ?
  if ($offset) {
    $o=max($offset-$count,0);
    echo "<a href=\"".str_replace("%%offset%%",$o,$url)."\" alt=\"(Ctl/Alt-p)\" title=\"(Alt-p)\" accesskey=\"p\">"._l("Previous")."</a> ";
  } else {
    echo _l("Previous")." ";
  }

  if ($total>(2*$count)) { // On n'affiche le pager central (0 1 2 ...) s'il y a au moins 2 pages.
    echo " - ";
    if (($total<($count*10)) && ($total>$count)) {  // moins de 10 pages : 
      for($i=0;$i<$total/$count;$i++) {
	$o=$i*$count;
	if ($offset==$o) {
	  echo $i." "; 
	} else {
	  echo "<a href=\"".str_replace("%%offset%%",$o,$url)."\">$i</a> ";
	}
      }
    } else { // Plus de 10 pages, on affiche 0 1 2 , 2 avant et 2 après la page courante, et les 3 dernieres
      for($i=0;$i<=2;$i++) {
	$o=$i*$count;
	if ($offset==$o) {
	  echo $i." "; 
	} else {
	  echo "<a href=\"".str_replace("%%offset%%",$o,$url)."\">$i</a> ";
	}
      }
      if ($offset>=$count && $offset<($total-2*$count)) { // On est entre les milieux ...
	// On affiche 2 avant jusque 2 après l'offset courant mais sans déborder sur les indices affichés autour
	$start=max(3,intval($offset/$count)-2);
	$end=min(intval($offset/$count)+3,intval($total/$count)-3);
	if ($start!=3) echo " ... ";
	for($i=$start;$i<$end;$i++) {
	  $o=$i*$count;
	  if ($offset==$o) {
	    echo $i." "; 
	  } else {
	    echo "<a href=\"".str_replace("%%offset%%",$o,$url)."\">$i</a> ";
	  }
	}
	if ($end!=intval($total/$count)-3) echo " ... ";
      } else {
	echo " ... ";
      }
      for($i=intval($total/$count)-3;$i<$total/$count;$i++) {
	$o=$i*$count;
	if ($offset==$o) {
	  echo $i." "; 
	} else {
	  echo "<a href=\"".str_replace("%%offset%%",$o,$url)."\">$i</a> ";
	}
      }
    echo " - ";
    } // More than 10 pages?
  }
  // Shall-we show the next page link ?
  if ($offset+$count<$total) {
    $o=$offset+$count;
    echo "<a href=\"".str_replace("%%offset%%",$o,$url)."\" alt=\"(Ctl/Alt-s)\" title=\"(Alt-s)\" accesskey=\"s\">"._l("Next")."</a> ";
  } else {
    echo _l("Next")." ";
  }
  echo $after;
}




function error($str) { 
  echo "ERR: ".$str."\n"; 
}

function not_found() {
  header("HTTP/1.0 404 Not Found");
  echo "<h1>"._("Page Not Found")."</h1>\n<p>"._("The requested page has not been found or an error happened. Please check")."</p>\n";
  print_r($_REQUEST);
  exit(); 
}

function render($viewname) {
  // We get the global $view so that the view can use it to render any data.
  global $view;
  if (!defined("VIEW_DIR")) {
    error("VIEW_DIR not defined, exiting!");
    not_found();
  }
  if (!preg_match("#^[0-9a-zA-Z]+$#",$viewname)) {
    error("view name incorrect ($viewname), exiting!");
    not_found();
  }
  if (!file_exists(VIEW_DIR."/".$viewname.".php")) {
    error("view not found ($view), exiting!");
    not_found();
  }
  // We move into the VIEW_DIR so that 
  $cur=getcwd();  chdir(VIEW_DIR);
  require_once(VIEW_DIR."/".$viewname.".php");
  chdir($cur); 
}


function check_user_identity() {
  if (!isset($_SERVER['PHP_AUTH_USER'])) {
    header('WWW-Authenticate: Basic realm="OpenMediaKit Transcoder"');
    header('HTTP/1.0 401 Unauthorized');
    echo 'Please authenticate';
    exit;
  } 
  $GLOBALS["me"]=mqone("SELECT * FROM user WHERE login='".mquote($_SERVER['PHP_AUTH_USER'])."' AND pass=PASSWORD('".mquote($_SERVER['PHP_AUTH_PW'])."') AND enabled=1;");
  
  if (!$GLOBALS["me"]) {
    header('WWW-Authenticate: Basic realm="OpenMediaKit Transcoder"');
    header('HTTP/1.0 401 Unauthorized');
    echo 'Login or password incorrect, or account disabled';
    exit;
  }
  //  mq("UPDATE user SET lastlogin=NOW() WHERE id='".$GLOBALS["me"]["id"]."';");
}


/* ************************************************************ */
/** Returns TRUE if the current user is an administrator
 */
function is_admin() {
  return ($GLOBALS["me"]["admin"]!=0);
}

/* ************************************************************ */
/** Here be parsers for meta. All those functiosn return an array */
/** that is then serialized and used later to populate the person */
/** frame. it takes an URL as an argument */
function _parseparltrack($url) {
	  // Parse the parltrack URL
	  if (($parltrack=fopen("$url","r")) !== True) return null;
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
	  return $mep;
}_
