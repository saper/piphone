<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
   <meta http-equiv="content-type" content="text/html; charset=utf-8" />
   <title><?php echo PROJECTNAME; ?> <?php if (isset($view["title"])) echo " - ".$view["title"]; ?></title>
   <link rel="shortcut icon" href="/static/favicon.ico" />
   <link href="/static/ui-2.0/global.cs" media="all" rel="stylesheet" type="text/css" />   
   <script src="/static/js/main.js" type="text/javascript"></script>
   <script src="/static/js/jquery-1.6.3.min.js" type="text/javascript"></script>
   <meta name="robots" content="noindex,follow,noarchive" />
   <?php if (isset($head)) echo $head; ?>
</head>
   <body<?php if (isset($body)) echo $body; ?>>

<?php

     echo "<div id=\"callee\">"._("This campaign is expired.")."</div>";

?>

</body>
</html>
