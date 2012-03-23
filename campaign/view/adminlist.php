
<?php require_once("head.php"); ?>
<h2>Campaign List</h2>

 <?php show_messages(); ?>

<p><a href="/admin/add" class="icon icon-save">New Campaign</a></p>

<div class="autoscroll">
<table class="list issues">
    <thead><tr>
        <th class="hide-when-print"></th>
        <th class="hide-when-print"></th>
        <th class="hide-when-print"></th>
    <th>#</th>
    <th>Name</th>
    <th>Start</th>
    <th>End</th>
  </tr>

</thead>
  
  <tbody>
    
   <?php 
   $row="odd";
foreach($view["campaign"] as $c) { 
if ($row=="odd") $row="even"; else $row="odd";
?>
  <tr id="campaign-<?php echo $c["id"]; ?>" class="<?php echo $row; ?> user <?php if ($c["enabled"]) echo "enabled"; else echo "disabled"; ?>">
   <td class="links"><a class="icon icon-edit" href="/admin/edit/<?php echo $c["id"]; ?>">Edit</a></td>
   <td class="links"><a class="icon icon-del" href="/admin/del/<?php echo $c["id"]; ?>">Delete</a></td>
   <?php if ($c["enabled"]) { ?>
   <td class="links"><a class="icon icon-fav-off" href="/admin/disable/<?php echo $c["id"]; ?>">Disable</a></td>
      <?php } else { ?>
   <td class="links"><a class="icon icon-fav" href="/admin/enable/<?php echo $c["id"]; ?>">Enable</a></td>
      <?php } ?>
   <td class="id"><?php echo $c["id"]; ?></td>
   <td class="name"><?php echo $c["name"]; ?></td>
   <td class="datestart"><?php echo $c["datestart"]; ?></td>
   <td class="dateend"><?php echo $c["dateend"]; ?></td>

  </tr>
   <?php } ?>
    
    </tbody>
</table>
</div>
</form>

<?php require_once("foot.php"); ?>

