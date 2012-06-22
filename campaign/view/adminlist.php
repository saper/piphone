
<?php require_once("head.php"); ?>
<h2>Campaign List</h2>

 <?php show_messages(); ?>

<p><a href="/admin/add" class="icon icon-save">New Campaign</a></p>

<div class="autoscroll">
<table class="list issues">
    <thead><tr>
	<th colspan="4"></th>
    <th>#</th>
    <th>Name</th>
    <th>Start</th>
    <th>End</th>
    <th>Live?</th>
    <th>List<br />Count</th>
    <th>Relevant<br />Calls</th>
    <th>Stats</th>
  </tr>

</thead>
  
  <tbody>
    
   <?php 
   $row="odd";
foreach($view["campaign"] as $c) { 
if ($row=="odd") $row="even"; else $row="odd";
?>
  <tr id="campaign-<?php echo $c["id"]; ?>" class="<?php echo $row; ?> user <?php if ($c["enabled"]) echo "enabled"; else echo "disabled"; ?>">
   <td class="links"><a class="icon icon-multiple" href="/admin/list/<?php echo $c["id"]; ?>">List</a></td>
   <td class="links">   <a class="icon icon-edit" href="/admin/edit/<?php echo $c["id"]; ?>">Edit</a></td>
   <td class="links">   <a class="icon icon-del" href="/admin/del/<?php echo $c["id"]; ?>">Delete</a></td>
   <?php if ($c["enabled"]) { ?>
   <td class="links">   <a class="icon icon-fav" href="/admin/disable/<?php echo $c["id"]; ?>">Disable</a></td>
      <?php } else { ?>
   <td class="links">   <a class="icon icon-fav-off" href="/admin/enable/<?php echo $c["id"]; ?>">Enable</a></td>
      <?php } ?>
</td>
   <td class="id"><?php echo $c["id"]; ?></td>
   <td class="name"><a href="/campaign/go/<?php echo $c["slug"] ?>"><?php echo $c["name"]; ?></a></td>
   <td class="datestart"><?php echo $c["datestart"]; ?></td>
   <td class="datestop"><?php echo $c["datestop"]; ?></td>
   <td class="live"><?php if($c["enabled"] & ! $c["expired"]) echo '<img src="/static/images/on_air.gif" alt="ON AIR" />'; ?></td>
   <td class="count"><?php echo $c["count"]; ?></td>
   <td class="calls"><?php echo $c["calls"]; ?></td>
   <td class="showstats"><a href="/admin/stats/<?php echo $c["id"]; ?>">Show Stats</a></td>
  </tr>
   <?php } ?>
    
    </tbody>
</table>
</div>
</form>

<?php require_once("foot.php"); ?>

