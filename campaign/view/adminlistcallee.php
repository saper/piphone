
<?php require_once("head.php"); ?>
<h2><?php echo $view["title"]; ?></h2>

 <?php show_messages(); ?>

<form method="post" action="/admin/dolist">
<input type="hidden" id="id" name="id" value="<?php eher($view["campaign"]["id"]); ?>" />
<table class="list sortable">
<tr>
  <th>Enabled ?</th>
  <th>Name</th>
  <th>Country</th>
  <th>Group</th>
  <th>Number</th>
  <th>CallCount</th>
  <th>Imported Score</th>
  <th>Weighted Score</th>
</tr>
   <?php foreach($view["list"] as $callee) { 

$us=unserialize($callee["meta"]);
?>
<tr>
<td><input type="checkbox" name="callee[<?php echo $callee["id"]; ?>]" value="1" id="callee<?php echo $callee["id"]; ?>_1" <?php if ($callee["enabled"] == 1) { ?> checked="1" <?php } ?>/></td>
   <td><?php echo $callee["name"]; ?></td>
   <td><?php echo $callee["country"]; ?></td>
   <td><?php echo $us["group"]; ?></td>
   <td><?php echo $callee["phone"]; ?></td>
   <td><?php echo $callee["callcount"]; ?></td>
   <td><?php echo $callee["scores"]; ?></td>
   <td><input type="text" name="score[<?php echo $callee["id"]; ?>]" value="<?php echo $callee["pond_scores"]; ?>" /></td>
</tr>
 <?php } ?>

</table>
<input type="submit" name="go" value="<?php echo $view["actionname"]; ?>" />
<input type="button" name="cancel" value="Cancel" onclick="document.location='/admin'" />

</form>


<?php require_once("foot.php"); ?>

