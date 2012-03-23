
<?php require_once("head.php"); ?>
<h2><?php __("Campaign's list"); ?></h2>

<?php show_messages(); ?>

<table>
<tr><th><?php __("Campaign Name"); ?></th><th><?php __("Starting"); ?></th><th><?php __("Ending"); ?></th></tr>
<?php
foreach($view["campaign"] as $c) {
?>
<tr>
  <td><a href="/campaign/go/<?php echo $c["slug"]; ?>"><?php echo $c["name"]; ?></a></td>
  <td><?php echo $c["datestart"]; ?></td>
  <td><?php echo $c["datestop"]; ?></td>
</tr>
<?php
}
?>
</table>

<?php require_once("foot.php"); ?>

