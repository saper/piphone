<?php


$acommittee = array(
		    "AFET" => "Committee on Foreign Affairs",
		    "ITRE" => "Committee on Industry, Research and Energy",
		    "LIBE" => "Committee on Civil Liberties, Justice and Home Affairs",
		    "EMPL" => "Committee on Employment and Social Affairs",
		    "REGI" => "Committee on Regional Development",
		    "ECON" => "Committee on Economic and Monetary Affairs",
		    "TRAN" => "Committee on Transport and Tourism",
		    "AGRI" => "Committee on Agriculture and Rural Development",
		    "BUDG" => "Committee on Budgets",
		    "IMCO" => "Committee on the Internal Market and Consumer Protection",
		    "FEMM" => "Committee on Women's Rights and Gender Equality",
		    "PETI" => "Committee on Petitions",
		    "INTA" => "Committee on International Trade",
		    "CULT" => "Committee on Culture and Education",
		    "DEVE" => "Committee on Development",
		    "CONT" => "Committee on Budgetary Control",
		    "JURI" => "Committee on Legal Affairs",
		    "PECH" => "Committee on Fisheries",
		    "AFCO" => "Committee on Constitutional Affairs",
		    );

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" class="nojs">
<head>
   <meta http-equiv="content-type" content="text/html; charset=utf-8" />
   <title><?php echo PROJECTNAME; ?> <?php if (isset($view["title"])) echo " - ".$view["title"]; ?></title>
   <link rel="shortcut icon" href="/static/favicon.ico" />
   <link href="/static/ui-2.0/widget.css" media="all" rel="stylesheet" type="text/css" />   
   <meta http-equiv="X-UA-Compatible" content="IE=edge, chrome=1">
   <meta name="viewport" content="width=device-width, initial-scale=1" />
   <?php if (isset($head)) echo $head; ?>
</head>
<body<?php if (isset($body)) echo $body; ?>>
<div id="global" style="max-width: 250px">
<h3><?php
if ($view["campaign"]["name-".$view["lang"]]) {   
  echo $view["campaign"]["name-".$view["lang"]];
} else {
  echo $view["campaign"]["name"];
}

?></h3>
<?php
$us=@unserialize($view["callee"]["meta"]);
?>
<div id="mep"> 
  <p class="left"><img src="/static/pics/<?php echo $us["picurl"]; ?>" alt="mep" style="height: 90px; width: 67px;" /></p>
  <div class="right" style="float:right;" >
   <p id="name" style="font-size: 100%; font-weight: bold;"><?php echo $view["callee"]["name"]; ?></p>
<?php if (isset($us["country"])) { ?> <p><img style="vertical-align: middle;" src="/static/ui-2.0/flag/<?php echo $us["country"]; ?>.png" height="24" alt="<?php echo $us["country"]; ?>" /></p> <?php } ?>
  </div>
  <div class="right">
      <ul id="resume">
<?php if (isset($us["group"])) { ?> <li id="group"><span><?php __("Group:"); ?></span><a href="https://memopol.lqdn.fr/europe/parliament/group/<?php echo $us["group"]; ?>/"><img style="vertical-align: middle;" src="https://memopol.lqdn.fr/static/img/groups/eu/<?php echo $us["group"]; ?>.png" height="20" alt="<?php echo $us["group"]; ?>" /></a>&nbsp;-&nbsp;<?php echo $us["group"]; ?></li> <?php } ?>
<?php if (isset($us["party"])) { ?> <li id="party"><span><?php __("Party:"); ?></span> <?php echo $us["party"]; ?></li> <?php } ?>
      </ul>
  </div>
  <div style="clear: both;"></div>
</div>

<!-- Shoot at random -->
<form method="post" action="/campaign/call2/<?php echo $view["campaign"]["slug"]; ?>/<?php echo $view["callee"]["id"]; ?>/#mep" id="selcountry">
  <p class="action button">
    <input type="submit" class="green" name="go" value="<?php __("Call, free of charge!");?>" />
  </p>
</form>

<!--Clicka convi things -->
<div style="clear:both;"></div>
</div>
</body>
</html>
