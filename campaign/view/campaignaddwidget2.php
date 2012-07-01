<?php


$acommittee = array(
		    "AFET" => "Committee on Foreign Affairs",
		    "ITRE" => "Committee on Industry, Research and Energy",
		    "LIBE" => "Committee on Civil Liberties, Justice and Home Affairs",
		    "EMPL" => "Committee on Employment and Social Affairs",
		    "REGI" => "Committee on Regional Development",
		    "ECON" => "Committee on Economic and Monetary Affairs",
		    "TRAN" => "Committee on Transport and Tourism",
		    "AGRI" => "Committee on Agriculture and Rural Development",
		    "BUDG" => "Committee on Budgets",
		    "IMCO" => "Committee on the Internal Market and Consumer Protection",
		    "FEMM" => "Committee on Women's Rights and Gender Equality",
		    "PETI" => "Committee on Petitions",
		    "INTA" => "Committee on International Trade",
		    "CULT" => "Committee on Culture and Education",
		    "DEVE" => "Committee on Development",
		    "CONT" => "Committee on Budgetary Control",
		    "JURI" => "Committee on Legal Affairs",
		    "PECH" => "Committee on Fisheries",
		    "AFCO" => "Committee on Constitutional Affairs",
		    );

?>
<?php require_once("head.php"); ?>


<h3><?php
if ($view["campaign"]["name-".$view["lang"]]) {   
  echo $view["campaign"]["name-".$view["lang"]];
} else {
  echo $view["campaign"]["name"];
}

?></h3>

<div id="abstract">
  <h4><?php __("Add the piphone widget to your website and spread the word."); ?></h4>
  <p><?php __("We are providing you with two widgets, one horizontal, one vertical, to insert on your website. It will allow people visiting your website to call using the piphone one European Parliament Member, picked at random in our listings."); ?></p>
  <div>
    <div id="left" style="float: left;">
      <object data="/campaign/widget2/<?php echo $view["campaign"]["slug"]?>" width="215" height="380"></object>
    </div>
    <div id="right">
      <textarea rows="4" cols="60" onclick="this.select()" onfocus="this.select()"><object data="http://piphone.lqdn.fr/campaign/widget2/<?php echo $view["campaign"]["slug"]?>" width="215" height="380"></object></textarea>
    </div>
  </div>
  <div style="clear: both;"></div>
  <div>
    <div id="left" style="float: left;">
      <object data="/campaign/widget2/<?php echo $view["campaign"]["slug"]?>/horiz" width="630" height="200"></object>
    </div>
    <div id="right">
      <textarea rows="9" cols="20" onclick="this.select()" onfocus="this.select()"><object data="http://piphone.lqdn.fr/campaign/widget2/<?php echo $view["campaign"]["slug"]?>/horiz" width="630" height="200"></object></textarea>
    </div>
  </div>
  <div style="clear: both;"></div>
</div>


<!--Clicka convi things -->
<?php require_once("foot.php"); ?>
