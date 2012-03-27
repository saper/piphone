
<?php require_once("head.php"); ?>
<h2><?php echo $view["title"]; ?></h2>

 <?php show_messages(); ?>

<script type="text/javascript">
function vistoggle(tgtId, txtId)
{
        var tgtElt = document.getElementById(tgtId) ;
        var txtElt = document.getElementById(txtId)
        if (tgtElt.style.display == "none")
        {
                tgtElt.style.display = "" ;
                txtElt.innerHTML = "[hide]"

        } else {
                tgtElt.style.display = "none" ;
                txtElt.innerHTML = "[show]"
        }
}
</script>

<h3>Calls breakdown</h3>
<ul>
<li><?php echo count($view["withfeedback"]); ?> calls with feedback <span id="show_fb" onClick="javascript:vistoggle('tbl_fb','show_fb');">[show]</span></li>
<table id="tbl_fb" class="list sortable" style="display:none">
  <thead><tr>
    <th>Caller</th>
    <th>Callee</th>
    <th>Call start</th>
    <th>Call end</th>
    <th>Duration</th>
    <th>Feedback</th>
  </tr></thead>
  <tbody>
  <?php
  $row="odd";
  foreach($view["withfeedback"] as $c) {
    if ($row=="odd") $row="even"; else $row="odd"; ?>
    <tr id="call-<?php echo $c["id"]; ?>" class="<?php echo $row; ?>">
      <td><?php echo $c["caller"]; ?></td>
      <td><?php echo $c["callee2"]; ?></td>
      <td><?php echo $c["datestart"]; ?></td>
      <td><?php echo $c["dateend"]; ?></td>
      <td><?php echo $c["duration"]; ?></td>
      <td><?php echo $c["feedback"]; ?></td>
    </tr>
    <?php } // end foreach ?>
  </tbody>
</table>
<li><?php echo count($view["withuuid"]); ?> calls through piphone (with uuid) <span id="show_pi" onClick="javascript:vistoggle('tbl_pi','show_pi');">[show]</span></li>
<table id="tbl_pi" class="list sortable" style="display:none">
  <thead><tr>
    <th>Caller</th>
    <th>Callee</th>
    <th>Call start</th>
    <th>Call end</th>
    <th>Duration</th>
    <th>Feedback</th>
  </tr></thead>
  <tbody>
  <?php
  $row="odd";
  foreach($view["withuuid"] as $c) {
    if ($row=="odd") $row="even"; else $row="odd"; ?>
    <tr id="call-<?php echo $c["id"]; ?>" class="<?php echo $row; ?>">
      <td><?php echo $c["caller"]; ?></td>
      <td><?php echo $c["callee2"]; ?></td>
      <td><?php echo $c["datestart"]; ?></td>
      <td><?php echo $c["dateend"]; ?></td>
      <td><?php echo $c["duration"]; ?></td>
      <td><?php echo $c["feedback"]; ?></td>
    </tr>
    <?php } // end foreach ?>
  </tbody>
</table>

<li><?php echo count($view["rawstats"]); ?> calls logged</li>
<ul>


<?php require_once("foot.php"); ?>

