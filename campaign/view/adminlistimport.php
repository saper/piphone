<?php require_once("head.php"); ?>
<h2><?php echo $view["title"]; ?></h2>

 <?php show_messages(); ?>

<form method="post" action="/admin/doimport" enctype="multipart/form-data">
<ul><?php __("Choose a CSV file to upload, one entry per line, fields are comma
separated and double quotes enclosed."); ?>
<?php __("Field order is / name / url / phone number / country code / score"); ?>
<li><input type="hidden" id="id" name="id" value="<?php eher($view["campaign"]["id"]); ?>" /></li>
<li><input type="file" name="file" /></li>
<li><ul><?php __("Choose a meta data provider"); ?>
  <li><label><input type="radio" name="meta_engine" value="parltrack" checked="1" />Parltrack</label></input></li>
  </ul>
</li>
<li><input type="submit" name="ok" value="<?php __($view["actionname"]); ?>" /></li>
</ul>
</form>

<?php require_once("foot.php"); ?>
