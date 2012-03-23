
<?php require_once("head.php"); ?>
<h2>User List</h2>

 <?php show_messages(); ?>

<p><a href="/user/add" class="icon icon-save">New User</a></p>

<div class="autoscroll">
<table class="list issues">
    <thead><tr>
        <th class="hide-when-print"></th>
        <th class="hide-when-print"></th>
        <th class="hide-when-print"></th>
    <th>#</th>
    <th>Login</th>
    <th>Email</th>
    <th>Enabled</th>
    <th>Admin?</th>
  </tr>

</thead>
  
  <tbody>
    
   <?php 
   $row="odd";
foreach($view["user"] as $c) { 
if ($row=="odd") $row="even"; else $row="odd";
?>
  <tr id="user-<?php echo $c["id"]; ?>" class="<?php echo $row; ?> user <?php if ($c["enabled"]) echo "enabled"; else echo "disabled"; ?>">
   <td class="links"><a class="icon icon-edit" href="/user/edit/<?php echo $c["id"]; ?>">Edit</a></td>
   <td class="links"><a class="icon icon-del" href="/user/del/<?php echo $c["id"]; ?>">Delete</a></td>
   <?php if ($c["enabled"]) { ?>
   <td class="links"><a class="icon icon-fav-off" href="/user/disable/<?php echo $c["id"]; ?>">Disable</a></td>
      <?php } else { ?>
   <td class="links"><a class="icon icon-fav" href="/user/enable/<?php echo $c["id"]; ?>">Enable</a></td>
      <?php } ?>
   <td class="id"><?php echo $c["id"]; ?></td>
   <td class="login"><?php echo $c["login"]; ?></td>
   <td class="email"><?php echo $c["email"];  ?></td>
   <td class="enabled"><?php if ($c["enabled"]) echo "X"; ?></td>
   <td class="right"><?php if ($c["admin"]) echo "X"; ?></td>
   <td class="apiurl"><?php echo $c["apiurl"]; ?></td>
   <td class="apikey"><?php echo $c["apikey"]; ?></td>
  </tr>
   <?php } ?>
    
    </tbody>
</table>
</div>
</form>

<?php require_once("foot.php"); ?>

