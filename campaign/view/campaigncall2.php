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
<?php require_once("head.php"); ?>


<h3><?php
if ($view["campaign"]["name-".$view["lang"]]) {   
  echo $view["campaign"]["name-".$view["lang"]];
} else {
  echo $view["campaign"]["name"];
}

?></h3>

<?php show_messages(); ?>

<?php
if (isset($_COOKIE["piphone-phone"])) {$view["phone"]=$_COOKIE["piphone-phone"];}
if (isset($_COOKIE["piphone-country"])) {$view["country"]=$_COOKIE["piphone-country"];}
if (isset($_REQUEST["country"])) {$view["country"]=$_REQUEST["country"];}
?>
<?php
$us=unserialize($view["callee"]["meta"]);

// mep template : 
if ($view["campaign"]["template"]!="") {
  $template=str_replace(".","",str_replace("/","",$view["campaign"]["template"]));
  require_once($template.".php");
} else {
  require_once("mep.php");
}

?>


<!-- Shoot at random -->
<form method="post" action="/campaign/call2/<?php echo $view["campaign"]["slug"]; ?>#mep" id="selcountry">
  <p style="text-align: center;">
<!--    <label for="country"><?php __("Choose a Country:"); ?></label> <select name="country" id="country" onchange="$('#selcountry').submit()"><option value=""><?php __("-- All Europe --"); ?> <?php eoption($view["countries"],$view["country"]); ?></select> -->
  </p>
  <p class="action button">
    <?php if ($view["callid"]) { ?>
      <input type="button" class="blue" id="callnow" type="submit" name="go" value="<?php __("Show Call & Feedback Popup"); ?>" />
    <?php } else { ?>
      <input type="button" class="green" id="callnow" type="submit" name="go" value="<?php __("Call Now"); ?>" />
      <span> or </span>
      <input type="submit" class="blue" href="/campaign/call2/<?php echo $view["campaign"]["slug"]; ?>" value="<?php __("Choose another random MEP"); ?>" />
    <?php } ?>
  </p>
</form>
<p class="action button"><a class="blue" href="/campaign/addwidget2/<?php echo $view["campaign"]["slug"]; ?>"><?php __("Add a widget on your website"); ?></a></p>

<!-- Da callbox. Not blue, not a TARDIS. -->
<div id="callbox">
  <div class="left">
    <form method="post" action="/campaign/call2/<?php echo $view["campaign"]["slug"]; ?>/<?php echo $view["callee"]["id"]; ?>/#mep">
      <h5><?php __("Call for free"); ?></h5>
      <p><?php __("If you want to call for free, you must provide us with your phone number (the PiPhone will call that number to initiate the communication).");?></p>
      <?php if ($view["message"]) { ?><p class="caption"><? echo $view["message"]; ?></p> <?php }?>
      <p><label for="phone"><?php __("Your phone number:"); ?></label> <input type="text" name="phone" id="phone" placeholder="55 53453000" <?php if ($view["phone"]) { ?>value="<?php echo $view["phone"]; } ?>"></p>
      <p class="caption"><?php __('use + plus your country code if not calling from Mexico. from Mexico start with your area code'); ?>
      <?php if ($view["callid"]) {?><p id="callme" class="button"><input type="submit" disabled="disabled" value="<?php __("Call in progress."); ?>" class="green" /></p>
      <?php } else { ?><p id="callme" class="button"><input type="submit" value="<?php __("I'm ready, call me"); ?>" class="green" /></p><?php } ?>

      <h5><?php __("Call at your expense"); ?></h5>
      <p><?php __("If you don't want to call for free, here is the number of the current MEP (you can either dial it from your phone or push the button if any VoIP client is installed on your device."); ?></p>
      <p class="button"><a href="tel:<?php echo $view["callee"]["phone"]; ?>" class="blue" target="_blank">☎ <?php echo $view["callee"]["phone"]; ?></a></p>
    </form>
  </div>
  <div class="right">
    <form method="post" action="/campaign/call2/<?php echo $view["campaign"]["slug"]; ?>/<?php echo $view["callee"]["id"]; ?>/<?php echo $view["callid"]; ?>">
      <h5><?php __("Feedback"); ?><span id="pophide" style="display: none; float: right;" onMouseOver="this.style.cursor='pointer'"><? __("X");?></span></h5>
      <p><b><?php __("You are calling: "); ?></b><?php echo $view["callee"]["name"] . " ( " . $view["callee"]["country"] . " - " . $us["group"] ." )"; ?></p>
      <p><?php __("Please take a second to give us your feedback."); ?></p>
      <p><?php __("Were you able to reach somebody or not? How long did the conversation last? What information did you get?"); ?></p>
      <p><label for="feedback"><?php __("Your feedback:"); ?></label> <textarea id="feedback" name="feedback"></textarea></p>
      <p class="button"><input type="submit" value="<?php __("Send"); ?>" class="green" /></p>
    </form>
  </div>
</div>

<div id="abstract">
   <?php 
   if ($view["campaign"]["description-".$view["lang"]]) {
     echo $view["campaign"]["description-".$view["lang"]]; 
   } else {
     echo $view["campaign"]["description"];
   }

?>
</div>

<!--Clicka convi things -->
<?php if (strpos($_SERVER['HTTP_USER_AGENT'],'Mobile') === false) { ?>
<script src="js/jquery-1-7-2.min.js"></script>
<script>
$('#callbox').hide();
$('#pophide').show();
$('#callnow').bind('click', function() {
  $('body').append('<div id="background" />');
  $('#callbox').show();
});
$('#pophide').bind('click', function() {
  $('#callbox').hide();
  $('#background').hide();
});
$('#background').live('click', function() {
  $(this).hide();
  $('#callbox').hide();
});
</script>
<!-- if call is in progress, show the popup -->
<?php
if ($view["callid"]) { ?>
<script>
  $('body').append('<div id="background" />');
  $('#callbox').show();
</script>
<?php
}
}
?>
<?php require_once("foot.php"); ?>
