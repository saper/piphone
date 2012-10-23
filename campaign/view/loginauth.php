
<?php require_once("head.php") ?>
<h3>Authenticate on the piphone</h3>
<?php show_messages(); ?>
<div>
<h4>Classic authentication method</h4>
  <form method="post" action="/login/doauth/">
    <p><input type="text" name="user" id="user" placeholder="Enter your username" /></p>
    <p><label for="password">Enter your password: </label><input type="password" name="password" id="password"/></p>
    <p class="button"><input type="submit" class="green" value="Login" /></p>
  </form>
</div>
<?php require_once("foot.php") ?>
