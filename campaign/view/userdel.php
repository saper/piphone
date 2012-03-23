
<?php require_once("head.php"); ?>
<h2>Confirm User Deletion</h2>

 <?php show_messages(); ?>

<p>Please confirm that you want to delete user <b><?php echo $view["user"]["login"]; ?></b></p>

<form method="post" action="/user/dodel">
<input type="hidden" id="id" name="id" value="<?php eher($view["user"]["id"]); ?>" />
<input type="submit" name="confirm" value="YES, DELETE this user" />
<input type="button" name="cancel" value="NO, KEEP this user account" onclick="document.location='/user'"/>
</form>

<?php require_once("foot.php"); ?>

