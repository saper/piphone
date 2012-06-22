
<?php require_once("head.php"); ?>
<h3><?php echo $view["campaign"]["name"]; ?></h3>

<?php show_messages(); ?>

<div id="abstract">
   <?php 
   if ($view["campaign"]["description-".$view["lang"]]) {
     echo $view["campaign"]["description-".$view["lang"]]; 
   } else {
     echo $view["campaign"]["description"];
   }

?>
</div>

<div id="mep">
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

<!-- Now the form -->
<form method="post" action="/campaign/call/<?php echo $view["campaign"]["slug"]; ?>?step=1">
<p style="text-align: center;">
   <label for="country"><?php __("Choose your Country:"); ?></label> <select name="country" id="country"><option value=""><?php __("-- All Europe --"); ?> <?php eoption($view["countries"],$_REQUEST["country"]); ?></select>
</p>
<p>
   <label for="phone"><?php __("Enter your phone number to be connected with your MEP through our platform. <br />Go directly to the next step if you want to call him by yourself."); ?><br />
<small> <?php __("Phone number must start by +&lt;countrycode&gt; <br />(example, +33 for France, then the number without the initial 0):"); ?></small></label><input type="text" name="phone" id="phone" size="20" maxlength="32" value="<?php eher($_REQUEST["phone"]); ?>" />
</p>
<p class="action button">
<input class="green" id="callnow" type="submit" name="go" value="<?php __("Next Step"); ?> ->" />
<span> or </span>
<a class="blue" href="/campaign/go2/<?php echo $view["campaign"]["slug"]; ?>" ><?php __("Choose another random MEP"); ?></a>
</p>
</form>
<script type="text/javascript">
   /*   $("#phone").focus(); */
</script>

</div>
</div>

</body>
</html>
