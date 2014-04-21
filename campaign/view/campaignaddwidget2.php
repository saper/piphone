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
$lang = 'en';
if($GLOBALS["lang"]=="fr_FR")$lang = 'fr';
if($GLOBALS["lang"]=="es_ES")$lang = 'es';

?></h3>

<div id="abstract">
  <h4><?php __("Add the piphone widget to your website and spread the word."); ?></h4>
  <p><?php if($lang=="fr"):?>
Vous pouvez insérer l'un de ces "widgets" sur votre site web pour permettre à vos amis et visiteurs d'appeler les élus, choisis au hasard parmi ceux qui restent à convaincre, <strong>gratuitement</strong> !
<?php else:?>
You can insert one of these two widgets on your website to allow your friends and visitors to call the elected representatives, picked at random from the ones left to convince, <strong>free of charge</strong>!
<?php endif;?>
</p>
  <div>
    <div id="left" style="float: left;">
      <object data="/campaign/widget2/<?php echo $view["campaign"]["slug"]?>/horiz/<?php echo $lang ?>" width="630" height="300"></object>
    </div>
    <div id="right">
      <textarea rows="9" cols="20" onclick="this.select()" onfocus="this.select()"><object data="https://mxphone.lqdn.fr/campaign/widget2/<?php echo $view["campaign"]["slug"]?>/horiz/<?php echo $lang ?>" width="630" height="300"></object></textarea>
    </div>
  </div>
  <div style="clear: both;"></div>
<!--
  <div>
    <div id="left" style="float: left;">
      <object data="/campaign/widget2/<?php echo $view["campaign"]["slug"]?>/verti/<?php echo $lang?>" width="215" height="380"></object>
    </div>
    <div id="right">
      <textarea rows="10" cols="25" onclick="this.select()" onfocus="this.select()"><object data="https://mxphone.lqdn.fr/campaign/widget2/<?php echo $view["campaign"]["slug"]?>/verti/<?php echo $lang ?>" width="215" height="380"></object></textarea>
    </div>
  </div>
  <div style="clear: both;"></div>
<p>
 -->
</div>


<!--Clicka convi things -->
<?php require_once("foot.php"); ?>
