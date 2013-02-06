
<?php require_once("head.php"); ?>
<h3><?php echo $view["title"]; ?></h3>

<?php show_messages(); ?>

<div id="abstract">
You want to be in the hall of fame? Or to know who you've called until now?
Just create an account.
</div>
<?php if (isset($view["login"])) { ?> <div class="flash error"><?php echo $view["login"]; ?> is not available</div> <?php } ?>
<?php if (isset($view["email"])) { ?> <div class="flash error"><?php echo $view["email"]; ?> is not a valid email address</div> <?php } ?>
<?php if (isset($view["password"])) { ?> <div class="flash error">Passwords doesn't match</div> <?php } ?>

<div class="mep">
<form method="post" action="/login/check">
  <p><label for="login">Choose a login name, this will be displayed in the hall of fames.</label><input type="text" name="login" id="login" /></p>
  <p><label for="email">We need a valid email address to send you validation mail or resetting the password.</label><input type="text" name="email" id="email" /></p>
  <p><label for="password">Choose a password. Everything should be a valid character.</label><input type="password" name="password" id="password" /></p>
  <p><label for="password2">Re-enter your password</label><input type="password" name="password2" id="password2" /></p>
  <p class="button"><input type="submit" class="green" value="<?php echo $view["actionname"]; ?>" /></p>
</form>
</div>
<?php require_once("foot.php"); ?>

