
<?php require_once("head.php");
function convert($str) {
  echo nl2br($str);
}
//echo "<pre>\n"; print_r($view); echo "\n</pre>\n";
 ?>

<div id="docerror" class="flash error" style="display: none; position: fixed; top: 10px; left: 20px;">An unexpected error occurred...</div>
<div id="docalready" class="flash warning" style="display: none; position: fixed; top: 10px; left: 20px;">This doc is already in the current release...</div>
<div id="docadded" class="flash notice" style="display: none; position: fixed; top: 10px; left: 20px;">The doc has been added</div>

<?php if ($_COOKIE["currentrelease"]) { ?>
    <td class="links"><a class="icon icon-fav" href="javascript:adddocnow(<?php echo doubleval($view["doc"]["id"]); ?>,<?php echo doubleval($_COOKIE["currentrelease"]); ?>)" title="Add this doc to the current release">Add this doc to the current release</a></td>
 <?php } ?>


<h2><?php ehe($view["doc"]["title"]); ?></h2>

 <?php show_messages(); ?> 

<table class="">
  <?php if (substr($view["doc"]["date"],0,10)!="1970-01-01") { ?>
<tr>
   <th>Date</th>
   <td><?php echo $view["doc"]["date"]; ?></td>
</tr>
 <?php } ?>
<tr>
   <th>From</th>
   <td><?php foreach($view["tags"][7] as $t) echo $t."<br />"; ?></td>
</tr>
<tr>
   <th>To</th>
   <td><?php foreach($view["tags"][8] as $t) echo $t."<br />"; ?></td>
</tr>
 <?php if (count($view["tags"][6]) || $view["doc"]["messageid"] || $view["doc"]["inreplyto"]) { ?>
<tr>
   <th>Others</th>
   <td><?php
if (count($view["tags"][6])) echo "Listname: ".implode(",",$view["tags"][6])."<br />";
if ($view["doc"]["messageid"]) echo "MessageId: ".$view["doc"]["messageid"]."<br />";
if ($view["doc"]["inreplyto"]) echo "InReplyTo: ".$view["doc"]["inreplyto"]."<br />";
?></td>
</tr>
<?php } ?>

<?php if (count($view["files"])) { ?>
<tr>
   <th>Attachments</th>
   <td><ul><?php
foreach($view["files"] as $f) {
  echo "<li><a href=\"/".$f["path"]."\">".basename($f["path"])."</a> (".format_size($f["size"]).")</li>\n";
} 
?></ul></td>
</tr>
 <?php } ?>

<tr>
   <th colspan="2">Text</th>
</tr>
<tr>
 <td colspan="2"><?php convert($view["doc"]["description"]); ?></td>
</tr>
</table>


<?php require_once("foot.php"); ?>

