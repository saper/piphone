<?php
$lang = "en_US";
if ($view['lang'] == "fr") $lang = "fr_FR";
putenv("LC_MESSAGES=".$lang);
putenv("LANG=".$lang);
putenv("LANGUAGE=".$lang);
setlocale(LC_ALL,$lang);
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
<div id="global" style="width: 503px; height: 150px; padding: 5px;">
<?php } else { ?>
<div id="global" style="width: 200px; height: 350px; padding: 5px;">
<?php } ?>
<?php if (strcmp($view["orientation"],"horiz") == 0) { ?>
<h4 style="font-size: 100%; height: 10%;margin-top: 0.8em;">
<?php } else { ?>
<h4 style="font-size: 90%; height: 25%;margin: 0.33em 0"><?php
}


__('Support LQDN');?>
</h4>
<?php if (strcmp($view["orientation"],"horiz") == 0) { ?>
<div id="mep" style="height: 60; width: 468; float: left;"> 
  <div class="right" style="float: both; margin-right: 10px;">
   <a href="https://support.laquadrature.net/" title="Support La Quadrature du Net against ACTA and beyond!" alt="Support La Quadrature du Net against ACTA and beyond!"><img src="https://support.laquadrature.net/images/LQDN_support_against_ACTA_and_beyond_468*60.gif" alt="Support La Quadrature du Net against ACTA and beyond!" /></a>
  </div>
</div>
<!-- Shoot at random -->
<?php } else { ?>
<div id="mep" > 
  <div class="right" style="width: 100%;">
   <a href="https://support.laquadrature.net/" title="Support La Quadrature du Net against ACTA and beyond!" alt="Support La Quadrature du Net against ACTA and beyond!"><img src="https://support.laquadrature.net/images/LQDN_support_against_ACTA_and_beyond_250*250.gif" alt="Support La Quadrature du Net against ACTA and beyond!" style="height: 180px; width: 180px;"/></a>
  </div>
  <div style="clear: both;"></div>
</div>
<?php } ?>

<!--Clicka convi things -->
<?php if($view['lang']=='fr'):?><a href="http://piphone.lqdn.fr/campaign/addwidget2/acta-final-vote?setlang=fr" target="_blank">Partagez sur votre site</a>
<?php else:?><a href="http://piphone.lqdn.fr/campaign/addwidget2/acta-final-vote?setlang=en" target="_blank">Share this on your site</a></p>
<?php endif;?>
<div style="clear:both;"></div>
</div>
<p style="font-size:8pt;padding:0;margin:0;text-align:right">
</body>
</html>
