
<?php require_once("head.php") ?>
<h3>Reset your password for the piphone</h3>
<?php show_messages(); ?>
<div>
  <form method="post" action="/login/doreset/">
    <p><label for="password">Enter your username: </label><input type="text" name="user" id="user" placeholder="Enter your username" /></p>
    <p class="button"><input type="submit" class="green" value="Reset" /></p>
  </form>
</div>
<?php require_once("foot.php") ?>
