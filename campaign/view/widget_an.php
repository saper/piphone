<?php
if ($view['lang'] == 'fr'): ?>
<a href="http://piphone.lqdn.fr" target="_blank">Appelez</a> les députés&nbsp;! 
<?php else: ?>
<a href="http://piphone.lqdn.fr" target="_blank">Call</a> your deputy!
<?php endif; ?>
<?php
if ($view["campaign"]["longname-".$view["lang"]]) {
  echo $view["campaign"]["longname-".$view["lang"]];
} else {
  echo $view["campaign"]["longname"];
}
?>
</h4>
<?php
$us=@unserialize($view["callee"]["meta"]);
?>
<?php if (strcmp($view["orientation"],"horiz") == 0) { ?>
<div id="mep" style="height: 45%; width: 60%; float: left;"> 
  <p class="left" style="width: 20%; margin: 0;" ><img src="<?php echo "/static/deputes/".$us["slug"]; ?>.jpg" alt="député" style="width: 85%; margin-right: 5px;"/></p>
  <div class="right" style="float: both; width: 25%; margin-right: 10px;">
   <p id="name" style="font-size: 100%; font-weight: bold;"><?php echo $view["callee"]["name"]; ?></p>
  </div>
  <div class="right" style="float: none; width: 40%; padding: 0px; margin: 0px;" >
      <ul id="resume">
  <?php if (isset($us["party"])) { ?> <li id="party"><span><?php __("Party:"); ?></span><span style="font-size: 85%; font-weight: normal;" > <?php if (strlen($us["party"])<40) echo $us["party"]; else echo substr($us["party"],0,37)."..."; ?></span></li> <?php } ?>
   <li id="age"><?php echo $us["age"]._(" years old"); ?></li> 

      </ul>
  </div>
</div>
<!-- Shoot at random -->
<form target="_blank" method="post" style="width: 10%; float: left; margin-left: 20px;" action="/campaign/call2/<?php echo $view["campaign"]["slug"]; ?>/<?php echo $view["callee"]["id"]; ?>/#mep" id="selcountry">
  <p class="action button">
    <br />
    <br />
    <br />
    <input type="submit" style="vertical-align: middle;" class="green" name="go" value="<?php __("Call, free of charge!");?>" />
  </p>
</form>
   <?php } else { ?>
<div id="mep" style="height: 42%;" > 
  <p class="left" style="width: 28%; margin: 0; padding: 0;" ><img src="<?php echo "/static/deputes/".$us["slug"]; ?>.jpg" alt="mep" style=" width: 100%; margin-right: 5px;" /></p>
  <div class="right" style="float:right; width: 68%; margin: 0; padding: 0;">
   <p id="name" style="font-size: 100%; font-weight: bold;"><?php echo $view["callee"]["name"]; ?></p>
  </div>
  <div class="right" style="width: 100%; padding: 0px;" >
      <ul id="resume">
<?php if (isset($us["party"])) { ?> <li id="party"><span><?php __("Party:"); ?></span><span style="font-size: 85%; font-weight: normal;" > <?php if (strlen($us["party"])<40) echo $us["party"]; else echo substr($us["party"],0,37)."..."; ?></span></li> <?php } ?>
   <li id="age"><?php echo $us["age"]._(" years old"); ?></li> 

      </ul>
  </div>
  <div style="clear: both;"></div>
</div>
<!-- Shoot at random -->
<form target="_blank" method="post" action="/campaign/call2/<?php echo $view["campaign"]["slug"]; ?>/<?php echo $view["callee"]["id"]; ?>/#mep" id="selcountry">
  <p class="action button" style="height: 15%;">
    <input type="submit" class="green" name="go" value="<?php __("Call, free of charge!");?>" />
  </p>
</form>
<?php } ?>

<!--Clicka convi things -->
<div style="clear:both;"></div>
</div>
<p style="font-size:8pt;padding:0;margin:0;text-align:right">
<?php if($view['lang']=='fr'):?><a href="http://piphone.lqdn.fr/campaign/addwidget2/<?php echo $view["campaign"]["slug"]; ?>?setlang=fr" target="_blank">Partagez sur votre site</a>
<?php else:?><a href="http://piphone.lqdn.fr/campaign/addwidget2/<?php echo $view["campaign"]["slug"]; ?>?setlang=en" target="_blank">Share this on your site</a></p>
<?php endif;?>
</body>
</html>

   <?php exit(); ?>