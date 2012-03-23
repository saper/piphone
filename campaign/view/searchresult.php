
<?php require_once("head.php"); 

function highlight($str) {
  global $view;
  $str=htmlentities(substr($str,0,1024),ENT_COMPAT,"UTF-8"); // only first KB ...
  $words=explode(" ",$view["highlight"]);
  if (count($words)>0) {
    foreach($words as $i) {
      $str = preg_replace('/('.$i.')/i',"<strong>$1</strong>",$str);
    }
  }
  $str=str_replace("\r","",$str);
  //  if (strlen($str)/substr_count($str,"\n")<120) {
  /*
    $str=str_replace("\n\n","²",$str);
    $str=str_replace("\n","",$str);
    $str=str_replace("²","\n",$str);
  */
    //  } 
  $str=str_replace("\n\n","\n",$str);
  $str=str_replace("\n\n","\n",$str);
  $str=str_replace("\n\n","\n",$str);
  echo nl2br($str);
}

?>
<h2>Search Form</h2>

<?php 
// echo "VIEW:"; echo "<pre>\n"; print_r($view); echo "</pre>\n"; 
?>

<?php show_messages(); ?>

<div id="search">

<form action="/search" method="get" id="searchform">

<div style="float: right; width: 400px; padding-left: 10px">
<p>Search with boolean operators (AND is the default, OR can be used). Example: Obama AND Iran AND nuclear OR weapons. Or use some combinations to find more relevant documents. </p>
<p>Check the full list of operators and search syntax <a href="http://sphinxsearch.com/docs/1.10/extended-syntax.html" target="_blank">at sphinx search documentation website</a> (external link).
</p>
<p>You can filter the search using a date in the following format: YYYY-MM-DD (Month and Day are not mandatory)
Example: 2009 will return all the documents from 2009, 2009-10 all the documents dated October 2009, 
2009-10-11 all the documents from the day October 11 2009</p>
</div>

<fieldset><legend>Search terms</legend> 

<p>Terms: <input type="text" name="q" id="q" size="60" maxlength="160" class="text" value="<?php ehe($view["q"]); ?>" /></p>
<p>Subject includes: <input type="text" name="title" id="title" size="20" class="text" value="<?php ehe($view["title"]) ;?>" /> (Example: [Tactical], Analysis, etc)</p>
<p>Subject excludes: <input type="text" name="notitle" id="notitle" size="20" class="text" value="<?php ehe($view["notitle"]); ?>" />
(Example: [OS] excludes open source articles) </p>
  <p>Limit by Date: <input type="text" name="date" id="date" size="10" maxlength="10" class="text" value="<?php echo $view["date"]; ?>"/> 
  &nbsp; &nbsp; Show <select name="count" id="count"><?php eoption(array("10"=>"10","20"=>"20","50"=>"50","100"=>"100","200"=>"200","500"=>"500","1000"=>"1000"),$view["count"]); ?></select> results per page
</p>
<p><input type="submit" value="Search" class="button" /></p>
</fieldset>

<fieldset><legend>Search a file by name</legend>
File name: <input type="text" name="file" id="file" size="30" maxlength="60" class="text" value="<?php ehe($view["file"]); ?>" />
<input type="submit" value="Search by attach filename" class="button" /></fieldset>

<fieldset><legend>Directly go to a document by ID</legend>
Document-ID: <input type="text" name="docid" id="docid" size="9" maxlength="9" class="text" value="<?php ehe($view["docid"]); ?>" />
<input type="submit" value="GO" class="button" /></fieldset>

</form>
</div>

<hr>

<?php 
		  if ($view["grandtotal"]==0) { 
		    if ($view["q"]!="" || $view["file"]!="") {
?>
<div class="flash warning">Your search returned no result, please try something else</div>
<?php
				} else {
?>
<div class="flash warning">Please search the database using the form above.</div>
<?php		   
		    }
 } else { ?>

<h2>Search Result (<?php echo $view["grandtotal"]; ?> results, results <?php echo $view["offset"]." to "; if ($view["offset"]+$view["count"]>$view["total"]) echo $view["total"]; else echo $view["offset"]+$view["count"]; ?>)</h2>

 <?php if (isset($view["error"])) echo "<div class=\"flash error\">".$view["error"]."</div>"; ?>
 <?php if (isset($view["message"])) echo "<div class=\"flash notice\">".$view["message"]."</div>"; ?>

<?php
// show the "previous 0 1 2 3 ... 12 _13_ 14 ... 22 23 24 next" pager
pager( $view["offset"], $view["count"], $view["total"],
       "/search?q=".urlencode($view["q"])."&title=".urlencode($view["title"])."&notitle=".urlencode($view["notitle"])."&date=".urlencode($view["date"])."&file=".urlencode($view["file"])."&count=".intval($view["count"])."&offset=%%offset%%",
       "<div class=\"pager\">","</div>");
?>

<div id="docerror" class="flash error" style="display: none; position: fixed; top: 10px; left: 20px;">An unexpected error occurred...</div>
<div id="docalready" class="flash warning" style="display: none; position: fixed; top: 10px; left: 20px;">This doc is already in the current release...</div>
<div id="docadded" class="flash notice" style="display: none; position: fixed; top: 10px; left: 20px;">The doc has been added</div>

<div class="autoscroll">
<table class="list issues">
    <thead><tr>
   <th></th>
    <th>Doc #</th>
    <th>Date</th>
    <th>Subject</th>
    <th>From</th>
    <th>To</th>
  </tr>
</thead>
  
  <tbody>
    
   <?php 
   $row="odd";
foreach($view["docs"] as $c) {
if ($row=="odd") $row="even"; else $row="odd";
?>
  <tr id="doc-<?php echo $c["id"]; ?>" class="<?php echo $row; ?> doc">
<?php
if ($_COOKIE["currentrelease"]) { ?>
    <td class="links"><a class="icon icon-fav" href="javascript:adddocnow(<?php echo doubleval($c["id"]); ?>,<?php echo doubleval($_COOKIE["currentrelease"]); ?>)" title="Add this doc to the current release">Add</a></td>
<?php } else { ?>
    <td class="links"></td>
<?php } ?>
    <td class="links"><a class="icon icon-details" href="/doc/view/<?php echo $c["id"]; ?>" title="View this doc"><?php echo $c["id"]; ?></a></td>
    <td class="date"><?php echo $c["date"]; ?></td>
    <td class="subject"><?php echo $c["title"]; ?></td>
    <td class="from"><?php echo $c["from"]; ?></td>
    <td class="to"><?php echo $c["to"]; ?></td>
  </tr>
   <tr id="doc-<?php echo $c["id"]; ?>" class="<?php echo $row; ?> bigdoc">
<td></td>
<td></td>
<td colspan="6">
       <?php highlight($c["description"]); ?>
   </td></tr>
   
   <?php } ?>
    
    </tbody>
</table>
</div>
<?php
// show the "previous 0 1 2 3 ... 12 _13_ 14 ... 22 23 24 next" pager
pager( $view["offset"], $view["count"], $view["total"],
       "/search?q=".urlencode($view["q"])."&title=".urlencode($view["title"])."&notitle=".urlencode($view["notitle"])."&date=".urlencode($view["date"])."&file=".urlencode($view["file"])."&offset=%%offset%%",
       "<div class=\"pager\">","</div>");

}
?>

<?php require_once("foot.php"); ?>

