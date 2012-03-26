
<?php require_once("head.php"); ?>
<h2>Confirm Campaign Deletion</h2>

 <?php show_messages(); ?>

<p>Please confirm that you want to delete campaign <b><?php echo $view["campaign"]["name"]; ?></b></p>

<form method="post" action="/admin/dodel">
<input type="hidden" id="id" name="id" value="<?php eher($view["campaign"]["id"]); ?>" />
<input type="submit" name="confirm" value="YES, DELETE this campaign" />
<input type="button" name="cancel" value="NO, KEEP this campaign" onclick="document.location='/admin'"/>
</form>

<?php require_once("foot.php"); ?>

