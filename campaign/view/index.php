<?php

// Only one campaign => redirect
if (count($view["campaign"])==1) {
  header("Location: /campaign/call2/".$view["campaign"][0]["slug"]);
  exit();
}

?>
<?php require_once("head.php"); ?>


<?php
if (empty($view['campaign'])){
  header('Location: http://demo.piphone.eu');
  exit;
}
if (count($view['campaign'])<2){
  $c = array_shift($view['campain']);
  $url = '/campaign/call2/'. $c["slug"]; 
  header('Location: '.$url);
  exit;
}


if (count($view["campaign"])) {
?>
<h3><?php __("Campaign's list"); ?></h2>

<?php show_messages(); ?>

   <?php 
   $row="odd";
foreach($view["campaign"] as $c) { 
if ($row=="odd") $row="even"; else $row="odd";
?>
<div id="campaign-<?php echo $c["id"]; ?>" class="campaign">
	<h4><?php
if ($c["name-".$view["lang"]]) {   
  echo $c["name-".$view["lang"]];
} else {
  echo $c["name"];
}

?></h4>
	<p><?php
//				       echo $GLOBALS["lang"];
	  if ($c["longname-".substr($GLOBALS["lang"],0,2)]) {
	    echo $c["longname-".substr($GLOBALS["lang"],0,2)];
	  } else {
	    echo $c["longname"];
	  }
	?>
	</p>
	<p class="deadline"><?php echo $c["datestop"]; ?></p>
	<p class="button"><a href="/campaign/call2/<?php echo $c["slug"]; ?>" class="blue"><?php __("Act now!"); ?></a>
	<!--<a href="/campaign/hof2/<?php echo $c["slug"]; ?>" class="blue"><?php __("Hall of Fame"); ?></a> --></p>
</div>
<?php
    }
?>
</table>
    <?php } else { /* There is no current campaign */ ?>

<?php show_messages(); ?>

<p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p>
<p><?php __("There is currently no campaign. Please come back later or when you hear there will be one"); ?> </p>

<p><h4><?php __("In the meantime, you may be interested in that campaign from friends of us:"); ?></h4></p>
<p><a href="http://call.unitary-patent.eu">Unitary Patent campaign from April: call your MEP!</a></p>

   <p>&nbsp;</p>

   <p><center><?php __("How to call your MEP, a video from La Quadrature du Net"); ?><br /><iframe src="http://mediakit.laquadrature.net/embed/852?size=small&amp;onlyvideo" style="width: 500px; height: 300px; border: 0; padding: 0; margin: 0;"></iframe></center></p>

<?php } ?>
<?php require_once("foot.php"); ?>

