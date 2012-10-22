
<?php require_once("head.php"); ?>
<h2>Your account</h2>

  <?php show_messages(); ?>

<div class="caption">This is what we know about you. You can change some details
such as pasword or email adresses here.
</div>

<!-- Display the user id, email and passwordon left, and the connection on right -->
<div id="callbox">
  <div class="left">
   <form method="post" action="/login/update/<?php echo $view["login"]["id"]; ?>">
     <h5>Account details</h5>
	 <p><label for="login">Your username is:</label><input type="text" name="login" id="login" value="<?php echo $view["login"]["login"]; ?>" /></p>
     <p><label for="email">Your email address:</label><input type="text" name="email" id="email" value="<?php echo $view["login"]["email"]; ?>" /></p>
     <p class="button"><input type="submit" value="Update" class="green" /></p>
   </form>
 </div>
 <div class="right">
   <form method="post" action="/login/pwchange/<?php echo $view["login"]["id"]; ?>">
     <h5>Authentification method</h5>
       <p class="caption">Classic HTTP auth using a password.</p>
	   <p><label for="current_pw">Enter your current password</label><input type="password" name="current_pw" id="current_pw" value="placeholder" /></p>
	   <p><label for="new_pw">Enter your new password</label><input type="password" name="new_pw" id="new_pw" value="placeholder" /></p>
	   <p><label for="new_pw2">Re-enter your new password</label><input type="password" name="new_pw2" id="new_pw2" value="placeholdes" /></p>
	   <p class="button"><input type="submit" value="Change password" class="green" /></p>
    </form>
       <p class="button"><a class="blue" href="/login/pwforget/<?php echo $view["login"]["id"]; ?>">I forget my password</a></p>
  </div>
</div>

 <?php if (isset($view["calls"])) { ?>
<div class="autoscroll">Those are the calls you've made until now. They've helped to change the situation, thank you.</div>
    <table class="list issues">
	 <thead>
	  <tr>
	   <th>Campaign name</th>
	   <th>Number of calls you've made</th>
	  </tr>
	 </thead>
	 <tbody>
	  <?php foreach($view["campaigns"] as $cname => $cscore) { ?>
	    <tr><td><?php echo $cname; ?></td><td><?php echo $cscore; ?></td></tr>
	  <?php } ?>
	 </tbody>
	</table>
</div>
    
 <?php } else { ?>
<div class="caption"><p>You haven't made any call yet.</p><p class="button"><a href="/" class="blue">Make yourself heard</a></p></div>
 <?php } ?>

<?php require_once("foot.php"); ?>
