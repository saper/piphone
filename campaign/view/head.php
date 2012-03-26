<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
   <meta http-equiv="content-type" content="text/html; charset=utf-8" />
   <title><?php echo PROJECTNAME; ?> <?php if (isset($view["title"])) echo " - ".$view["title"]; ?></title>
   <link rel="shortcut icon" href="/static/favicon.ico" />
   <link href="/static/css/main.css" media="all" rel="stylesheet" type="text/css" />   
   <link href="/static/cleditor/jquery.cleditor.css" media="all" rel="stylesheet" type="text/css" />   
   <script src="/static/js/main.js" type="text/javascript"></script>
   <script src="/static/js/jquery-1.6.3.min.js" type="text/javascript"></script>
   <script src="/static/cleditor/jquery.cleditor.min.js" type="text/javascript"></script>
   <script src="/static/js/main.js" type="text/javascript"></script>
   <meta name="robots" content="noindex,follow,noarchive" />
   <?php if (isset($head)) echo $head; ?>
</head>
   <body<?php if (isset($body)) echo $body; ?>>

<div id="wrapper">

<div style="float: right; padding: 10px;">
   <?php if (is_admin()) { ?>
   <p><ul>
<li><a href="/admin"><?php __("Campaign Admin"); ?></a></li>
<li><a href="/user"><?php __("User Admin"); ?></a></li>
</ul>
</p>
    <?php } ?>
   <img src="/static/piphone_final.png" alt="Le PiPhone de La Quadrature du Net" />
</div>

   <div style="padding: 10px">
   [<a href="?setlang=en">English</a>] 
   [<a href="?setlang=fr">Français</a>] 
</div>

   <h1>PiPhone Campaigning Tool</h1>

<div id="content">


