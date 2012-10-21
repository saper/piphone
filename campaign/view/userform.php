
<?php require_once("head.php"); ?>
<h2><?php echo $view["title"]; ?></h2>

 <?php show_messages(); ?>

<form method="post" action="/user/do">
<input type="hidden" id="id" name="id" value="<?php eher($view["user"]["id"]); ?>" />
<table class="form">
<tr><th><label for="login">Login</label></th></tr>
<tr><td><input size="32" type="text" id="login" name="login" value="<?php eher($view["user"]["login"]); ?>" /></td></tr>

<tr><th><label for="pass">Password</label></th></tr>
<tr><td><input size="32" type="password" id="pass" name="pass" value="<?php eher($view["user"]["pass"]); ?>" /></td></tr>

<tr><th><label for="pass2">Password (again)</label></th></tr>
<tr><td><input size="32" type="password" id="pass2" name="pass2" value="<?php eher($view["user"]["pass2"]); ?>" /></td></tr>

<tr><th><label for="email">Email</label></th></tr>
<tr><td><input size="60" type="text" id="email" name="email" value="<?php eher($view["user"]["email"]); ?>" /></td></tr>

<tr><th><label for="enabled">Enabled ?</label>
  <input type="checkbox" id="enabled" name="enabled"<?php checked($view["user"]["enabled"]); ?> value="1" />
</th></tr>

<tr><th><label for="admin">Admin ?</label>
  <input type="checkbox" id="admin" name="admin"<?php checked($view["user"]["admin"]); ?> value="1" />
</th></tr>


</table>

<input type="submit" name="go" value="<?php echo $view["actionname"]; ?>" />
<input type="button" name="cancel" value="Cancel" onclick="document.location='/user'" />

</form>


<?php require_once("foot.php"); ?>

