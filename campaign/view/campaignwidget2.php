<?php
$lang = "en_US";
if ($view['lang'] == "fr") $lang = "fr_FR";
putenv("LC_MESSAGES=".$lang);
putenv("LANG=".$lang);
putenv("LANGUAGE=".$lang);
setlocale(LC_ALL,$lang);

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
<div id="global" style="width: 600px; height: 245px; padding: 0px;">
<?php } else { ?>
<div id="global" style="width: 200px; height: 350px; padding: 0px;">
<?php } 

   if ($view["campaign"]["template"]!="") {
     $template=str_replace(".","",str_replace("/","",$view["campaign"]["template"]));
     require_once("widget_".$template.".php");
   } 

$us=@unserialize($view["callee"]["meta"]);

?>

<!--Clicka convi things -->
<div style="clear:both;"></div>
</div>
<p style="font-size:8pt;padding:0;margin:0;text-align:right">
<?php if($view['lang']=='fr'):?><a href="https://mxphone.lqdn.fr/campaign/addwidget2/<?php echo $view["campaign"]["slug"]; ?>?setlang=fr" target="_blank">Partagez sur votre site</a>
<?php else:?><a href="https://mxphone.lqdn.fr/campaign/addwidget2/<?php echo $view["campaign"]["slug"]; ?>?setlang=en" target="_blank">Share this on your site</a></p>
<?php endif;?>
</body>
</html>
