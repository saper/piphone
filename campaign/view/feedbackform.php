
<?php require_once("head.php"); ?>
<h2><?php __("Feedback Form"); ?></h2>

<?php show_messages(); ?>

<p><?php __("Please tell us what happened if you was able to talk to someone. Your feedback is important to us."); ?></p>

<form method="post" action="/campaign/feedbackdo/<?php echo $view["campaign"]["slug"]; ?>/<?php echo $view["callid"]; ?>">
<p>
<textarea style="font-face: Arial, Helvetica, sans-serif; font-size: 12px; width: 600px; height: 300px" id="feedback" name="feedback"></textarea>
</p>

<input type="submit" name="go" value="<?php __("Send my feedback"); ?>" />
   <input type="button" name="cancel" value="<?php __("Cancel"); ?>" onclick="document.location='/campaign/go/<?php echo $view["campaign"]["slug"]; ?>'"/>
</form>
<?php require_once("foot.php"); ?>

