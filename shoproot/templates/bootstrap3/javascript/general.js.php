<?php
/*-----------------------------------------------------------
   $Id: general.js.php 13590 2021-06-15 16:28:04Z GTB $

   modified eCommerce Shopsoftware
   http://www.modified-shop.org

   Copyright (c) 2009 - 2013 [www.modified-shop.org]
  -----------------------------------------------------------
   based on: (c) 2003 - 2006 XT-Commerce (general.js.php)
  -----------------------------------------------------------
   Released under the GNU General Public License
   -----------------------------------------------------------
*/
define('DIR_TMPL_JS', DIR_TMPL.'javascript/');
// this javascriptfile get includes at the TOP of every template page in shop
// you can add your template specific js scripts here
?>
<script type="text/javascript">
  var DIR_WS_BASE = "<?php echo DIR_WS_BASE ?>";
  var DIR_WS_CATALOG = "<?php echo DIR_WS_CATALOG ?>";
  var SetSecCookie = "<?php echo ((HTTP_SERVER == HTTPS_SERVER && $request_type == 'SSL') ? true : false); ?>";
</script>