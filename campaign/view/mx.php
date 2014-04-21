<?php
$partyurl=array(
	"PRI" => "http://pri.senado.gob.mx/",
	"PAN" => "http://www.pan.senado.gob.mx/gppan/",
	"PRD" => "http://prd.senado.gob.mx/",
	"PVEM" => "http://www.pvem.senado.gob.mx/pvemsenado.html",
	"PT" => "http://dominiociudadano.org/",
	"MC" => "http://www.movimientociudadano.org.mx/",
	"PANAL" => "http://www.nueva-alianza.org.mx/",
);
?>
<div id="mep">
  <p class="left"><img src="<?php echo "/static/mxpic/".sprintf("%04d",$us["id"]); ?>.jpg" alt="Photo"></p>
  <div class="right">
   <p id="name"><?php echo $view["callee"]["name"]; ?></p>
      <ul id="resume">
   <?php $calleephone=preg_replace("#^00#","+",$view["callee"]["phone"]); ?>
   <li id="phone"><?php __("Phone number: "); ?><a href="tel:5253453000">(55) 53453000 ext. <?php echo $calleephone; ?></a></li>
<?php if (isset($us["nom_circo"])) { ?> <li id="group"><span><?php __("Nom de la circonscription:"); ?></span> <?php echo $us["nom_circo"]; ?></li> <?php } ?>
<li id="party">
<?php if (isset($us["party"])) { ?> <span><?php __("Party:"); ?></span> <a href="<?php echo $partyurl[$us["party"]]; ?>" ><img src="/static/mxparty/<?php echo $us["party"]; ?>.png" alt="<?php echo $us["party"]; ?>" title="<?php echo $us["party"]; ?>" style="vertical-align: middle" /></a> <?php } ?>
<?php if (isset($us["comision"]) && $us["comision"]) { ?> <span><?php __("Comisión:"); ?></span> <?php echo $us["comision"]; ?> <?php } ?>

</li>
<li id="age">
<?php if (isset($us["email"]) && $us["email"]) { ?> <span><?php __("Email:"); ?></span> <a href="mailto:<?php echo $us["email"]; ?>" ><?php echo $us["email"]; ?></a> <?php } ?>
<?php if (isset($us["twitter"]) && $us["twitter"]) { ?> <span><?php __("Twitter:"); ?></span> <a href="https://twitter.com/@<?php echo $us["twitter"]; ?>"><?php echo $us["twitter"]; ?></a> <?php } ?>

</li>
      </ul>
   <p id="info"><a href="<?php echo $view["callee"]["url"]; ?>"><?php __("Get more info…"); ?></a></p>
  </div>
</div>
