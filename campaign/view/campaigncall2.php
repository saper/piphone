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

<div id="abstract">
   <?php 
   if ($view["campaign"]["description-".$view["lang"]]) {
     echo $view["campaign"]["description-".$view["lang"]]; 
   } else {
     echo $view["campaign"]["description"];
   }

?>
</div>

<?php
if (isset($_COOKIE["piphone-phone"])) {$view["phone"]=$_COOKIE["piphone-phone"];}
if (isset($_COOKIE["piphone-country"])) {$view["country"]=$_COOKIE["piphone-country"];}
if (isset($_REQUEST["country"])) {$view["country"]=$_REQUEST["country"];}
?>
<?php
$us=@unserialize($view["callee"]["meta"]);
?>
<div id="mep">
  <p class="left"><img src="/static/pics/<?php echo $us["picurl"]; ?>" alt="mep"></p>
  <div class="right">
   <p id="name"><?php echo $view["callee"]["name"]; ?></p>
      <ul id="resume">
   <!--        <li id="age">51 years old</li> -->
   <?php $calleephone=preg_replace("#^00#","+",$view["callee"]["phone"]); ?>
   <li id="phone"><?php __("Phone number: "); ?><a href="callto://<?php echo $calleephone; ?>"><?php echo $calleephone; ?></a></li>
<?php if (isset($us["group"])) { ?> <li id="group"><span><?php __("Political group:"); ?></span><a href="https://memopol.lqdn.fr/europe/parliament/group/<?php echo $us["group"]; ?>/"><img style="vertical-align: middle;" src="https://memopol.lqdn.fr/static/img/groups/eu/<?php echo $us["group"]; ?>.png" height="24" alt="<?php echo $us["group"]; ?>" /></a> - <?php echo $us["group"]; ?></li> <?php } ?>
<?php if (isset($us["party"])) { ?> <li id="party"><span><?php __("National party:"); ?></span> <?php echo $us["party"]; ?></li> <?php } ?>
<?php if (isset($us["country"])) { ?> <li id="country"><span><?php __("Country: "); ?></span><img style="vertical-align: middle;" src="/static/ui-2.0/flag/<?php echo $us["country"]; ?>.png" height="24" alt="<?php echo $us["country"]; ?>" /></li> <?php } ?>
<!--        <li id="score"><span>Score:</span> 25 / 100</li> -->
      </ul>
   <?php if (isset($us["committee"])) { ?>
      <ul id="committee">
	 <?php foreach($us["committee"] as $com) { ?>        <li title="<?php echo $acommittee[$com]; ?>"><a href="https://memopol.lqdn.fr/europe/parliament/committee/<?php echo $com; ?>/" style="color: white;"><?php echo $com; ?></a></li> <?php } ?>
      </ul>
	 <?php } ?>
	 <p id="info"><a href="https://memopol.lqdn.fr/europe/parliament/deputy/<?php echo $us["url"]; ?>/"><?php __("Get more info…"); ?></a></p>
  </div>
</div>

<!-- Shoot at random -->
<form method="post" action="/campaign/call2/<?php echo $view["campaign"]["slug"]; ?>#mep" id="selcountry">
  <p style="text-align: center;">
    <label for="country"><?php __("Choose a Country:"); ?></label> <select name="country" id="country" onchange="$('#selcountry').submit()"><option value=""><?php __("-- All Europe --"); ?> <?php eoption($view["countries"],$view["country"]); ?></select>
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

<p><a href="/campaign/addwidget2/<?php echo $view["campaign"]["slug"]; ?>">Add a widget on your website</a></p>
<!-- Da callbox. Not blue, not a TARDIS. -->
<div id="callbox">
  <div class="left">
    <form method="post" action="/campaign/call2/<?php echo $view["campaign"]["slug"]; ?>/<?php echo $view["callee"]["id"]; ?>/#mep">
      <h5><?php __("Call for free"); ?></h5>
      <p><?php __("If you want to call fo free, you must provide us with your phone number (the PiPhone will call that number to initiate the communication).");?></p>
      <?php if ($view["message"]) { ?><p class="caption"><? echo $view["message"]; ?></p> <?php }?>
      <p><label for="phone"><?php __("Your phone number:"); ?></label> <input type="text" name="phone" id="phone" placeholder="+33123456789001" <?php if ($view["phone"]) { ?>value="<?php echo $view["phone"]; } ?>"></p>
      <p class="caption"><?php __('Starting with your <a href="http://en.wikipedia.org/wiki/List_of_country_calling_codes#Zones_3.2F4_.E2.80.93_Europe">country code</a>, without the initial 0'); ?>
      <?php if ($view["callid"]) {?><p id="callme" class="button"><input type="submit" disabled="disabled" value="<?php __("Call in progress."); ?>" class="green" /></p>
      <?php } else { ?><p id="callme" class="button"><input type="submit" value="<?php __("I'm ready, call me"); ?>" class="green" /></p><?php } ?>

      <h5><?php __("Call at your expense"); ?></h5>
      <p><?php __("If you don't want to call for free, here is the number of the current MEP (you can either dial it from your phone or push the button if any VoIP client is installed"); ?></p>
      <p class="button"><a href="callto://<?php echo $view["callee"]["phone"]; ?>" class="blue" target="_blank">☎ <?php echo $view["callee"]["phone"]; ?></a></p>
    </form>
  </div>
  <div class="right">
    <form method="post" action="/campaign/call2/<?php echo $view["campaign"]["slug"]; ?>/<?php echo $view["callee"]["id"]; ?>/<?php echo $view["callid"]; ?>">
      <h5><?php __("Feedback"); ?><span id="pophide" style="float: right;" onMouseOver="this.style.cursor='pointer'"><? __("X");?></span></h5>
      <p><?php __("Please take a second to give us your feedback."); ?></p>
      <p><?php __("Were you able to reach somebody or not? How long did the conversation last? What information did you get?"); ?></p>
      <p><label for="feedback"><?php __("Your feedback:"); ?></label> <textarea id="feedback" name="feedback"></textarea></p>
      <p class="button"><input type="submit" value="<?php __("Send"); ?>" class="green" /></p>
    </form>
  </div>
</div>

<!--Clicka convi things -->
<script src="js/jquery-1-7-2.min.js"></script>
<script>
$('html').removeClass('nojs').addClass('js');
$('#callbox').hide();
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
?>
<?php require_once("foot.php"); ?>
