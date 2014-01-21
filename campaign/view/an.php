
<div id="mep">
  <p class="left"><img src="<?php echo "/static/deputes/".$us["slug"]; ?>.jpg" alt="photo député"></p>
  <div class="right">
   <p id="name"><?php echo $view["callee"]["name"]; ?></p>
      <ul id="resume">
   <?php $calleephone=preg_replace("#^00#","+",$view["callee"]["phone"]); ?>
   <li id="phone"><?php __("Phone number: "); ?><a href="tel:<?php echo $calleephone; ?>"><?php echo $calleephone; ?></a></li>
<?php if (isset($us["nom_circo"])) { ?> <li id="group"><span><?php __("Nom de la circonscription:"); ?></span> <?php echo $us["nom_circo"]; ?></li> <?php } ?>
<?php if (isset($us["party"])) { ?> <li id="party"><span><?php __("Party:"); ?></span> <?php echo $us["party"]; ?></li> <?php } ?>
   <li id="age"><?php echo $us["age"]._(" years old"); ?></li> 
<?php if (isset($us["job"])) { ?> <li id="job"><span><?php __("Job:"); ?></span> <?php echo $us["job"]; ?></li> <?php } ?>
      </ul>
   <p id="info"><a href="<?php echo 'http://www.nosdeputes.fr/'.$us["slug"]; ?>"><?php __("Get more info…"); ?></a></p>
  </div>
</div>
