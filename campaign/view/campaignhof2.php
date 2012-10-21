<?php require_once("head.php"); ?>
<h3>Hall of Fame for campaign <?php
if ($view["campaign"]["name-".$view["lang"]]) {
  echo $view["campaign"]["name-".$view["lang"]];
} else {
  echo $view["campaign"]["name"];
}
?></h3>

<?php show_messages(); ?>

<div id="autoscroll">
 <table class="list issues">
   <thead><tr>
   <th>#</th>
   <th>Name</th>
   <th>Score</th>
   </tr></thead>

   <tbody>
   <?php
   $row="odd";
   $rank=1;
   foreach($view["hof"] as $entry) {
     if ($row == "odd") $row = "even"; else $row = "odd";
	 ?>
	 <tr class="<?php echo $row; ?> user enabled">
	   <td class="id"><?php echo $rank; ?></td>
	   <td class="name"><?php echo $entry[0]; ?></td>
	   <td class="calls"><?php echo $entry[1]; ?></td>
	 </tr>
	 <?php $rank++;
	 }
	 ?>
  </table>
  <h3>Can't find yourself in here? </h3><h4>Raise your score and make yourself heard!</h4>
  <p class="button"><a href="/campaign/call2/<?php echo $view["campaign"]["slug"]; ?>" class="blue"><?php __("Act now!"); ?></a></p>
  </p>

<?php require_once("foot.php"); ?>
