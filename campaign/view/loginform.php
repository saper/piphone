
<?php require_once("head.php"); ?>
<h3><?php echo $view["title"]; ?></h3>

<?php show_messages(); ?>

<div id="abstract">
You want to be in the hall of fame? Or to know who you've called until now?
Just create an account.
</div>
<div class="mep">
<form method="post" action="/login/check">
  <p><label for="login">Choose a login name, this will be displayed in the hall of fames.</label><input type="text" name="login" id="login" /><?php if (isset($view["login"])) { ?>
    <span class="flash error"><?php echo $view["login"]; ?> is not available</span> <?php } ?></p>
  <p><label for="email">We need a valid email address to send you validation mail or resetting the password.</label><input type="text" name="email" id="email" /><?php if (isset($view["email"])) { ?>
    <span class="flash error"><?php echo $view["email"]; ?> is not a valid email address</span> <?php } ?></p>
  <p><label for="password">Choose a password. Everything should be a valid character.</label><input type="password" name="password" id="password" /><?php if (isset($view["password"])) { ?>
    <span class="flash error">Passwords doesn't match</span> <?php } ?></p>
  <p><label for="password2">Re-enter your password</label><input type="password" name="password2" id="password2" /></p>
  <p class="button"><input type="submit" class="green" value="<?php echo $view["actionname"]; ?>" /></p>
</form>
</div>
<?php require_once("foot.php"); ?>

