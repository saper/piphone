
<?php require_once("head.php"); ?>
<h2>Your account</h2>

  <?php show_messages(); ?>

<div class="caption">This is what we know about you. You can change some details
such as pasword or email adresses here.
</div>

<!-- Display the user id, email and passwordon left, and the connection on right -->
<div id="mep">
  <div class="left">
   <form method="post" action="/login/update/">
     <h5>Account details</h5>
	 <p><label for="login">Your username is: </label><input type="text" name="login" id="login" placeholder="<?php echo $_SESSION["id"]["login"]; ?>" /></p>
     <p><label for="email">Your email address: </label><input type="text" name="email" id="email" placeholder="<?php echo $_SESSION["id"]["email"]; ?>" /></p>
     <p class="button"><input type="submit" value="Update" class="green" /></p>
   </form>
 </div>
 <div class="right">
   <form method="post" action="/login/pwchange/">
     <h5>Authentification method</h5>
       <p class="caption">Classic HTTP auth using a password.</p>
	   <p><label for="current_pw">Enter your current password: </label><input type="password" name="current_pw" id="current_pw" value="placeholder" /></p>
	   <p><label for="new_pw">Enter your new password: </label><input type="password" name="new_pw" id="new_pw" value="placeholder" /></p>
	   <p><label for="new_pw2">Re-enter your new password: </label><input type="password" name="new_pw2" id="new_pw2" value="placeholdes" /></p>
	   <p class="button"><input type="submit" value="Change password" class="green" /></p>
    </form>
  </div>
</div>

 <?php if (count($view["campaigns"]) > 0) { ?>
<div class="autoscroll">Those are the calls you've made until now. They've helped to change the situation, thank you.</div>
    <table class="list issues">
	 <thead>
	  <tr>
	   <th>Campaign name</th>
	   <th>Number of calls you've made</th>
	  </tr>
	 </thead>
	 <tbody>
	  <?php foreach($view["campaigns"] as $cid => $campaign) { ?>
	    <tr><td><?php echo $campaign["name"]; ?></td><td><?php echo $campaign["score"]; ?></td></tr>
	  <?php } ?>
	 </tbody>
	</table>
</div>
    
 <?php } else { ?>
<div class="caption"><p>You haven't made any call yet.</p><p class="button"><a href="/" class="blue">Make yourself heard</a></p></div>
 <?php } ?>

<?php require_once("foot.php"); ?>
