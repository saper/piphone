
<?php require_once("head.php"); ?>
<h3><?php echo $view["campaign"]["name"]; ?></h3>

<?php show_messages(); ?>

<div id="abstract">
   <?php 
   if ($view["campaign"]["description-".$view["lang"]]) {
     echo $view["campaign"]["description-".$view["lang"]]; 
   } else {
     echo $view["campaign"]["description"];
   }

?>
</div>

<div id="callbox" style="padding: 4px 4px 4px 50px">
<iframe src="/campaign/call/<?php echo $view["campaign"]["slug"]; ?>" style="border: 0; padding:0; margin: 0; width: 750px; height: 400px; overflow: hidden" scrollbars="auto">
</iframe>
</div>

<?php require_once("foot.php"); ?>

