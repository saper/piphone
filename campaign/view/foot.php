<!--<p class=button><a class=blue href="/campaign/hof2/">Citizens Hall of Fame</a></p>-->

</div>

<?php session_write_close(); ?>
        <div style="clear:both;"></div>

<!-- <div id="ajax-indicator" style="display:none;"><span>Loading...</span></div> -->

<p style="float: right">
       <a href="/campaign/privacy"><?php __("Privacy Policy"); ?></a>
</p>
<p id="footer">
    <?php if (defined("PIPHONE_VERSION")) echo "version ".PIPHONE_VERSION; ?> &copy; La Quadrature du Net 2011-2014 <br /> code by <a href="http://www.digi-nation.com/">Digination</a>, <a href="http://benjamin.sonntag.fr/">Benjamin Sonntag</a>, <a href="http://about.okhin.fr">Okhin</a>, Design by <a href="http://nurpa.be/">André Loconte</a>
</p>
<?php
if(strpos($_SERVER['HTTP_USER_AGENT'],'Mobile') === false)
{ ?>
<script>
$('html').removeClass('nojs').addClass('js');
</script>
<?php } ?>
</body>
</html>
