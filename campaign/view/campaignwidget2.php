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
<?php if (strcmp($view["orientation"],"horiz") == 0) { ?>
<div id="global" style="width: 600px; height: 170px; padding: 5px;">
<?php } else { ?>
<div id="global" style="width: 200px; height: 350px; padding: 5px;">
<?php } ?>
<?php if (strcmp($view["orientation"],"horiz") == 0) { ?>
<h4 style="font-size: 100%; height: 10%;">
<?php } else { ?>
<h4 style="font-size: 100%; height: 30%;"><?php
}
if ($view["campaign"]["longname-".$view["lang"]]) {   
  echo $view["campaign"]["longname-".$view["lang"]];
} else {
  echo $view["campaign"]["longname"];
}

?></h3>
<?php
$us=@unserialize($view["callee"]["meta"]);
?>
<?php if (strcmp($view["orientation"],"horiz") == 0) { ?>
<div id="mep" style="height: 45%; width: 60%; float: left;"> 
  <p class="left" style="width: 20%; margin: 0;" ><img src="/static/pics/<?php echo $us["picurl"]; ?>" alt="mep" style="width: 85%; margin-right: 5px;"/></p>
  <div class="right" style="float: both; width: 25%; margin-right: 10px;">
   <p id="name" style="font-size: 100%; font-weight: bold;"><?php echo $view["callee"]["name"]; ?>
<?php if (isset($us["country"])) { ?> <img style="vertical-align: middle;" src="/static/ui-2.0/flag/<?php echo $us["country"]; ?>.png" height="18" alt="<?php echo $us["country"]; ?>" /> <?php } ?></p>
  </div>
  <div class="right" style="float: none; width: 40%; padding: 0px; margin: 0px;" >
      <ul id="resume">
<?php if (isset($us["group"])) { ?> <li id="group"><span><?php __("Group:"); ?></span><span style="font-size: 86%; font-weight: normal;" ><a href="https://memopol.lqdn.fr/europe/parliament/group/<?php echo $us["group"]; ?>/"><img style="vertical-align: middle;" src="https://memopol.lqdn.fr/static/img/groups/eu/<?php echo $us["group"]; ?>.png" height="16" alt="<?php echo $us["group"]; ?>" /></a>&nbsp;-&nbsp;<?php echo $us["group"]; ?></span></li> <?php } ?>
<?php if (isset($us["party"])) { ?> <li id="party"><span><?php __("Party:"); ?></span><span style="font-size: 85%; font-weight: normal;" > <?php if (strlen($us["party"])<40) echo $us["party"]; else echo substr($us["party"],0,37)."..."; ?></span></li> <?php } ?>
      </ul>
  </div>
</div>
<!-- Shoot at random -->
<form method="post" style="width: 10%; float: left; margin-left: 20px;" action="/campaign/call2/<?php echo $view["campaign"]["slug"]; ?>/<?php echo $view["callee"]["id"]; ?>/#mep" id="selcountry">
  <p class="action button">
    <br />
    <br />
    <br />
    <input type="submit" style="vertical-align: middle;" class="green" name="go" value="<?php __("Call, free of charge!");?>" />
  </p>
</form>
<?php } else { ?>
<div id="mep" style="height: 38%;" > 
  <p class="left" style="width: 30%; margin: 0;" ><img src="/static/pics/<?php echo $us["picurl"]; ?>" alt="mep" style=" width: 100%; margin-right: 5px;" /></p>
  <div class="right" style="float:right; width: 70%;">
   <p id="name" style="font-size: 100%; font-weight: bold;"><?php echo $view["callee"]["name"]; ?>
<?php if (isset($us["country"])) { ?> <img style="vertical-align: middle;" src="/static/ui-2.0/flag/<?php echo $us["country"]; ?>.png" height="18" alt="<?php echo $us["country"]; ?>" /> <?php } ?></p>
  </div>
  <div class="right" style="width: 100%; padding: 0px;" >
      <ul id="resume">
<?php if (isset($us["group"])) { ?> <li id="group"><span><?php __("Group:"); ?></span>
        <span style="font-size: 85%; font-weight: normal;" ><a href="https://memopol.lqdn.fr/europe/parliament/group/<?php echo $us["group"]; ?>/"><img style="vertical-align: middle;" src="https://memopol.lqdn.fr/static/img/groups/eu/<?php echo $us["group"]; ?>.png" height="16" alt="<?php echo $us["group"]; ?>" /></a>&nbsp;-&nbsp;<?php echo $us["group"]; ?></span></li> <?php } ?>
<?php if (isset($us["party"])) { ?> <li id="party"><span><?php __("Party:"); ?></span><span style="font-size: 85%; font-weight: normal;" > <?php if (strlen($us["party"])<40) echo $us["party"]; else echo substr($us["party"],0,37)."..."; ?></span></li> <?php } ?>
      </ul>
  </div>
  <div style="clear: both;"></div>
</div>
<?php } ?>

<?php if (strcmp($view["orientation"],"horiz") != 0) { ?>
<!-- Shoot at random -->
<form method="post" action="/campaign/call2/<?php echo $view["campaign"]["slug"]; ?>/<?php echo $view["callee"]["id"]; ?>/#mep" id="selcountry">
  <p class="action button" style="height: 15%;">
    <input type="submit" class="green" name="go" value="<?php __("Call, free of charge!");?>" />
  </p>
</form>
<?php } ?>

<!--Clicka convi things -->
<div style="clear:both;"></div>
</div>
</body>
</html>
