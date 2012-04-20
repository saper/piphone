
<?php require_once("head.php"); ?>
<h2><?php echo $view["title"]; ?></h2>

 <?php show_messages(); ?>

<form method="post" action="/admin/do">
<input type="hidden" id="id" name="id" value="<?php eher($view["campaign"]["id"]); ?>" />
<table class="form">
<tr><th><label for="name">Name</label></th></tr>
<tr><td><input size="32" type="text" id="name" name="name" value="<?php eher($view["campaign"]["name"]); ?>" /></td></tr>

<tr><th><label for="slug">Slug</label></th></tr>
<tr><td><input size="32" type="text" id="slug" name="slug" value="<?php eher($view["campaign"]["slug"]); ?>" /></td></tr>


<tr><th><label for="longname">Longname</label></th></tr>
<tr><td><textarea class="longname" rows="10" cols="80" id="longname" name="longname"><?php eher($view["campaign"]["longname"]); ?></textarea></td></tr>

<tr><th><label for="longname-fr">Longname FR</label></th></tr>
<tr><td><textarea class="longname" rows="10" cols="80" id="longname-fr" name="longname-fr"><?php eher($view["campaign"]["longname-fr"]); ?></textarea></td></tr>

<tr><th><label for="description">Description</label></th></tr>
<tr><td><textarea class="description" rows="30" cols="80" id="description" name="description"><?php eher($view["campaign"]["description"]); ?></textarea></td></tr>


<tr><th><label for="description-fr">Description FR</label></th></tr>
<tr><td><textarea class="description" rows="30" cols="80" id="description-fr" name="description-fr"><?php eher($view["campaign"]["description-fr"]); ?></textarea></td></tr>

<tr><th><label for="datestart">Date Start (YYYY-MM-DD hh:mm:ss)</label></th></tr>
<tr><td><input size="60" type="text" id="datestart" name="datestart" value="<?php eher($view["campaign"]["datestart"]); ?>" /></td></tr>

<tr><th><label for="datestop">Date Stop (YYYY-MM-DD hh:mm:ss)</label></th></tr>
<tr><td><input size="60" type="text" id="datestop" name="datestop" value="<?php eher($view["campaign"]["datestop"]); ?>" /></td></tr>
<script type="text/javascript">
 $(document).ready(function() {
  $(".longname").cleditor({width:"100%", height:"150"});
  $(".description").cleditor({width:"100%", height:"400"});
 });
</script>
</table>

<input type="submit" name="go" value="<?php echo $view["actionname"]; ?>" />
<input type="button" name="cancel" value="Cancel" onclick="document.location='/admin'" />

</form>

<script type="text/javascript">
 $(document).ready(function() {
  $("textarea").cleditor({width:"100%", height:"400"});
 });
</script>

<?php require_once("foot.php"); ?>

