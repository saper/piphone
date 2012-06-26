<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" class="nojs">
<head>
   <meta http-equiv="content-type" content="text/html; charset=utf-8" />
   <title><?php echo PROJECTNAME; ?> <?php if (isset($view["title"])) echo " - ".$view["title"]; ?></title>
   <link rel="shortcut icon" href="/static/favicon.ico" />
   <link href="/static/ui-2.0/global.css" media="all" rel="stylesheet" type="text/css" />   
   <link href="/static/cleditor/jquery.cleditor.css" media="all" rel="stylesheet" type="text/css" />   
   <script src="/static/js/main.js" type="text/javascript"></script>
   <script src="/static/js/jquery-1.6.3.min.js" type="text/javascript"></script>
   <script src="/static/cleditor/jquery.cleditor.min.js" type="text/javascript"></script>
   <script src="/static/js/main.js" type="text/javascript"></script>
   <script src="/static/js/sorttable.js" type="text/javascript"></script>
   <meta name="robots" content="index,follow,noarchive" />
   <meta http-equiv="X-UA-Compatible" content="IE=edge, chrome=1">
   <meta name="viewport" content="width=device-width, initial-scale=1" />
   <?php if (isset($head)) echo $head; ?>
</head>
<body<?php if (isset($body)) echo $body; ?>>



<div id="wrapper">
<div id="header">
   <h1><?php __("PiPhone"); ?> </h1><h2><?php __("Call MEPs for free - and make yourself heard"); ?></h2>
</div>
<?php /*
<div class="error flash">
   THE PIPHONE IS CURRENTLY OUT OF ORDER, THIS MESSAGE WILL DISAPPEAR WHEN IT WILL WORK AGAIN<br />
   LE PIPHONE EST ACTUELLEMENT HORS SERVICE, NOUS ENLEVERONS CE MESSAGE UNE FOIS RÉPARÉ
</div>
      */ ?>
<div id="global">

<div class="left">
   <a href="?setlang=en"><img src="/static/ui-2.0/flags/GB.png" alt="English" /></a>
   <a href="?setlang=fr"><img src="/static/ui-2.0/flags/FR.png" alt="Français" /></a>
</div>

<div style="float: right;" id="logo">
<!-- Banner -->
   <?php if ($GLOBALS["lang"]=="fr_FR") { ?>
<a href="https://soutien.laquadrature.net/" title="Soutenez La Quadrature du Net contre ACTA et au-delà !" alt="Soutenez La Quadrature du Net contre ACTA et au-delà !"><img src="https://soutien.laquadrature.net/images/LQDN_support_against_ACTA_and_beyond_468*60.gif" alt="Soutenez La Quadrature du Net contre ACTA et au-delà !" /></a>
   <?php } else { ?>
<a href="https://support.laquadrature.net/" title="Support La Quadrature du Net against ACTA and beyond!" alt="Support La Quadrature du Net against ACTA and beyond!"><img src="https://support.laquadrature.net/images/LQDN_support_against_ACTA_and_beyond_468*60.gif" alt="Support La Quadrature du Net against ACTA and beyond!" /></a>
<?php } ?>
<!-- /Bennar -->
</div>

   <?php if (is_admin()) { ?>
<div style="float: right; padding: 10px;">
   <p><ul>
<li><a href="/admin"><?php __("Campaign Admin"); ?></a></li>
<li><a href="/user"><?php __("User Admin"); ?></a></li>
</ul>
</p>
</div>
    <?php } ?>
