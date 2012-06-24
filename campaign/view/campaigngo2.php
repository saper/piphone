
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

<!-- The conditionnal frame -->
<?php
    if ($view["frame"] == 1) {
?>   <div id="callbox">
      <div class="left">
       <form method="post" action="/campaign/callme2/<?php echo $view["campaign"]["slug"]; ?>">
        <h5>Call for free</h5>
        <p>If you want to call fo free, you must provide us with your phone number (the PiPhone will call that number to initiate the communication).</p>
        <?php if ($view["message"]) { ?><p class="caption"><? echo $view["message"]; ?></p> <?php }?>
        <p><label for="phone">Your phone number:</label> <input type="text" name="phone" id="phone" placeholder="+33123456789001" /></p>
        <p class="caption">Starting with your <a href="http://en.wikipedia.org/wiki/List_of_country_calling_codes#Zones_3.2F4_.E2.80.93_Europe">country code</a>, without
        <p class="button"><input type="submit" value="I'm ready, call me" class="green" /></p>

        <h5>Call at your expense</h5>
        <p>If you don't want to call for free, here is the number of the current MEP (you can either dial it from your phone or push the button if any VoIP client si inst
        <p class="button"><a href="callto://<?php echo $view["callee"]["phone"]; ?>" class="blue" target="_blank">☎ <?php echo $view["callee"]["phone"]; ?></a></p>
       </form>
      </div>
      <div class="right">
       <form method="post" action="/campaign/feedback2/<?php echo $view["campaign"]["slug"]; ?>">
        <h5>Feedback</h5>
        <p>Please take a second to give us your feedback.</p>
        <p>Were you able to reach somebody or not? How long did the conversation last? What information did you get?</p>
        <p><label for="feedback">Your feedback:</label> <textarea id="feedback" name="feedback"></textarea></p>
        <p class="button"><input type="submit" value="Send" class="green" /></p>
       </form>
      </div>
    </div>
<?php
	};
?>
<!-- Now the form -->
<form method="post" action="/campaign/go2/<?php echo $view["campaign"]["slug"]; ?>?step=1">
<p style="text-align: center;">
   <label for="country"><?php __("Choose your Country:"); ?></label> <select name="country" id="country"><option value=""><?php __("-- All Europe --"); ?> <?php eoption($view["countries"],$_REQUEST["country"]); ?></select>
</p>
<p class="action button">
<input class="green" id="callnow" type="submit" name="go" value="<?php __("Call Now"); ?>" />
<span> or </span>
<a class="blue" href="/campaign/go2/<?php echo $view["campaign"]["slug"]; ?>?step=0" ><?php __("Choose another random MEP"); ?></a>
</p>
</form>
<script type="text/javascript">
   /*   $("#phone").focus(); */
</script>

</div>
</div>

</body>
</html>
