
<?php require_once("head.php"); ?>


<?php
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
	<h4><?php echo $c["name"] ?></h4>
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
	<p class="button"><a href="/campaign/call2/<?php echo $c["slug"]; ?>" class="blue"><?php __("Act now!"); ?></a></p>
</div>
<?php
    }
?>
</table>
    <?php } else { /* There is no current campaign */ ?>

<?php show_messages(); ?>

<p><?php __("There is currently no campaign. Please come back later or when you hear there will be one"); ?> 

<?php } ?>
<?php require_once("foot.php"); ?>

