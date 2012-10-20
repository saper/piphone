<?php require_once("head.php"); ?>
<h2><?php echo $view["title"]; ?></h2>

 <?php show_messages(); ?>

<form method="post" action="/admin/doimport" enctype="multipart/form-data">
<p><?php __("Choose a CSV file to upload, one entry per line, fields are comma
separated and double quotes enclosed."); ?>
<?php __("Field order is / name / url / phone number / country code / score") ?>
</p>
<input type="hidden" id="id" name="id" value="<?php eher($view["campaign"]["id"]); ?>" />
<input type="file" name="file" />
<input type="submit" value="<?php __($view["actionname"]); ?>" />
</form>

<?php require_once("foot.php"); ?>
