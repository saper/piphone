
<?php require_once("head.php"); ?>
<h2><?php __("Campaign's list"); ?></h2>

<?php show_messages(); ?>

<table>
<tr><th><?php __("Campaign Name"); ?></th><th><?php __("Starting"); ?></th><th><?php __("Ending"); ?></th></tr>
   <?php 

if (count($view["campaign"])) {

   $row="odd";
foreach($view["campaign"] as $c) { 
if ($row=="odd") $row="even"; else $row="odd";
?>
  <tr id="campaign-<?php echo $c["id"]; ?>" class="<?php echo $row; ?>">
  <td><a href="/campaign/go/<?php echo $c["slug"]; ?>"><?php echo $c["name"]; ?></a></td>
  <td><?php echo $c["datestart"]; ?></td>
  <td><?php echo $c["datestop"]; ?></td>
</tr>
  <tr class="<?php echo $row; ?>">
      <td></td>
  <td colspan="2" style="font-size: small">
<?php
     //				       echo $GLOBALS["lang"];
  if ($c["longname-".substr($GLOBALS["lang"],0,2)]) {
    echo $c["longname-".substr($GLOBALS["lang"],0,2)];
  } else {
    echo $c["longname"];
  }
?>
</td>
</tr>
<?php
    }
?>
</table>
    <?php } else { /* There is no current campaign */ ?>

<p><?php __("There is currently no campaign. Please come back later or when you hear there will be one"); ?> 

<?php } ?>
<?php require_once("foot.php"); ?>

