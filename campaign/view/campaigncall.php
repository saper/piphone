<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
   <meta http-equiv="content-type" content="text/html; charset=utf-8" />
   <title><?php echo PROJECTNAME; ?> <?php if (isset($view["title"])) echo " - ".$view["title"]; ?></title>
   <link rel="shortcut icon" href="/static/favicon.ico" />
   <link href="/static/css/main.css" media="all" rel="stylesheet" type="text/css" />   
   <script src="/static/js/main.js" type="text/javascript"></script>
   <script src="/static/js/jquery-1.6.3.min.js" type="text/javascript"></script>
   <meta name="robots" content="noindex,follow,noarchive" />
   <?php if (isset($head)) echo $head; ?>
</head>
   <body<?php if (isset($body)) echo $body; ?>>

<div id="callee">
   <?php 

   if ($view["callee"]["url"]) echo "<a target=\"memopol\" href=\"".$view["callee"]["url"]."\">";
echo $view["callee"]["name"]; 
   if ($view["callee"]["url"]) echo "</a>";
?>
   <?php __("has phone number"); ?>
   <?php $calleephone=preg_replace("#^00#","+",$view["callee"]["phone"]);
   echo "<a href=\"callto://".$calleephone."\">".$calleephone."</a>";
?>
</div>

<div id="status">
<?php
 
   if ($view["phone"]) {
     echo "<div id=\"callst\"><p>";
     echo _("If you read the briefing above, and if you fully acknowledge it, click the button below to get in touch with that person.");
     echo "</p><p>";
     echo "<input type=\"button\" onclick=\"gocall(".$view["callid"].")\" value=\" - "._("Call me")." - \" />";
     // __("We are now calling you and you will soon be at the phone with the person above.");
     echo "</p></div>";
   } else {
     echo "<p>";
     __("You should call him now, and fill the form below to give us some feedback about that call.");
     echo "</p>";
   }
?>
<p>
<?php __("If you prefer to call another MEP, click the button "); ?>
   <input type="button" name="cancel" value="<?php __("Call another one"); ?>" onclick="document.location='/campaign/call/<?php echo $view["campaign"]["slug"]; ?>'"/>
   </p>
<p><?php __("Please tell us what happened if you was able to talk to someone. Your feedback is important to us."); ?></p>

<form method="post" target="_parent" action="/campaign/feedbackdo/<?php echo $view["campaign"]["slug"]; ?>/<?php echo $view["callid"]; ?>">
<p>
<textarea style="font-face: Arial, Helvetica, sans-serif; font-size: 12px; width: 550px; height: 100px" id="feedback" name="feedback"></textarea>
</p>

<input type="submit" name="go" value="<?php __("Send my feedback"); ?>" />
   <input type="button" name="cancel" value="<?php __("Cancel"); ?>" onclick="document.location='/campaign/call/<?php echo $view["campaign"]["slug"]; ?>'"/>
</form>

<script type="text/javascript">
   function gocall(id) {
     $.get('/campaign/callme/'+id, function(data) {
	 $("#callst").html('<?php __("We are now calling you, you will be connected to the phone above soon..."); ?>');
       });
   }
</script>

</div>

</body>
</html>
