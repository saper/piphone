<?php require_once("head.php"); ?>
<h2><?php echo $view["title"]; ?></h2>

<?php show_messages(); ?>

<span><?php __($view["lines"]) ?> of CSV imported into campaign <?php __($view["campaign"]["name"]) ?></span>
<?php if (count($errors) > 0)
  { ?>
  <p>
	  <ul><b>There is <?php __(count($errors)) ?></b>
	  <?php foreach ($errors as $error) { ?>
	    <li><?php __($error); ?></li>
      <?php } ?>
	  </ul>
  </p>

  <a href="/admin/list/<?php $campaign["id"] ?>">Go back to the list page</a>

<?php require_once("foot.php"); ?>
