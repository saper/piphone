
<div id="mep">
  <p class="left"><img src="<?php echo $us["picurl"]; ?>" alt="mep"></p>
  <div class="right">
   <p id="name"><?php echo $view["callee"]["name"]; ?></p>
      <ul id="resume">
   <!--        <li id="age">51 years old</li> -->
   <?php $calleephone=preg_replace("#^00#","+",$view["callee"]["phone"]); ?>
   <li id="phone"><?php __("Phone number: "); ?><a href="tel:<?php echo $calleephone; ?>"><?php echo $calleephone; ?></a></li>
<?php if (isset($us["group"])) { ?> <li id="group"><span><?php __("Political group:"); ?></span><a href="https://memopol.lqdn.fr/search/?q=group:<?php echo $us["group"]; ?>%20is_active:1"><img style="vertical-align: middle;" src="/static/eu/<?php echo str_replace('/','',$us["group"]); ?>.gif" height="24" alt="<?php echo $us["group"]; ?>" /></a> - <?php echo $us["group"]; ?></li> <?php } ?>
<?php if (isset($us["party"])) { ?> <li id="party"><span><?php __("National party:"); ?></span> <?php echo $us["party"]; ?></li> <?php } ?>
<?php if (isset($view["callee"]["country"])) { ?> <li id="country"><span><?php __("Country: "); ?></span><img style="vertical-align: middle;" src="/static/ui-2.0/flag/<?php echo $view["callee"]["country"]; ?>.png" height="24" alt="<?php echo $us["country"]; ?>" /></li> <?php } ?>
      </ul>
   <?php if (isset($us["committee"])) { ?>
      <ul id="committee">
	 <?php foreach($us["committee"] as $com) { if ($com != "") {?>        <li title="<?php echo $acommittee[$com]; ?>"><a href="https://memopol.lqdn.fr/europe/parliament/committee/<?php echo $com; ?>/" style="color: white;"><?php echo $com; ?></a></li> <?php }} ?>
      </ul>
	 <?php } ?>
	 <p id="info"><a href="https://memopol.lqdn.fr/europe/parliament/deputy/<?php echo str_replace(' ', '', ucwords(strtolower(str_replace('-', ' ', iconv("UTF-8", "US-ASCII//TRANSLIT", $view["callee"]["name"]))))); ?>/"><?php __("Get more info…"); ?></a></p>
  </div>
</div>
