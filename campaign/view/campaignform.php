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

<?php show_messages(); ?>

<!-- Now the form -->
<form method="post" action="/campaign/call/<?php echo $view["campaign"]["slug"]; ?>?step=1">
<p>
   <label for="country"><?php __("Choose your Country:"); ?></label> <select name="country" id="country"><option value=""><?php __("-- All Europe --"); ?> <?php eoption($view["countries"],$_REQUEST["country"]); ?></select>
</p>
<p>
   <label for="phone"><?php __("Enter your phone number to be connected with your MEP through our platform. <br />Go directly to the next step if you want to call him by yourself."); ?><br />
<small> <?php __("Phone number must start by +&lt;countrycode&gt; <br />(example, +33 for France, then the number without the initial 0):"); ?></small></label><input type="text" name="phone" id="phone" size="20" maxlength="32" value="<?php eher($_REQUEST["phone"]); ?>" />
</p>
<p>
<input type="submit" name="go" value="<?php __("Next Step"); ?> ->" />
</p>
</form>
<script type="text/javascript">
   /*   $("#phone").focus(); */
</script>

</body>
</html>
