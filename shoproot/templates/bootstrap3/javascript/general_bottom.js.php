<?php
/*-----------------------------------------------------------
   $Id: general_bottom.js.php 12789 2020-06-24 09:34:26Z GTB $

   modified eCommerce Shopsoftware
   http://www.modified-shop.org

   Copyright (c) 2009 - 2013 [www.modified-shop.org]
  -----------------------------------------------------------
   based on: (c) 2003 - 2006 XT-Commerce (general.js.php)
  -----------------------------------------------------------
   Released under the GNU General Public License
   -----------------------------------------------------------
*/
// this javascriptfile get includes at the BOTTOM of every template page in shop
// you can add your template specific js scripts here

// Template Sprachdatei laden
$smarty->config_load('lang_'.$_SESSION['language'].'.custom');
$heading_mmenu = $smarty->get_config_vars('heading_mmenu');
$heading_responsivemenu = $smarty->get_config_vars('heading_responsivemenu');

$script_array = array(
	DIR_TMPL_JS.'jquery.min.js',
	DIR_TMPL_JS.'bootstrap.min.js',
	DIR_TMPL_JS.'pushy.min.js',
	DIR_TMPL_JS.'bscarousel.min.js',
	DIR_TMPL_JS.'jquery.unveil.min.js',
	DIR_TMPL_JS.'jquery.alertable.min.js',
);

// Cookieconsent nur wenn nicht oil.min.js
if (!defined('MODULE_COOKIE_CONSENT_STATUS') || strtolower(MODULE_COOKIE_CONSENT_STATUS) == 'false') {
	$script_array[] = DIR_TMPL_JS .'jquery.cookieconsent.min.js';
}

// Cloud Zoom
if (USE_CLOUD_ZOOM == true) {
	$script_array[] = DIR_TMPL_JS .'cloud-zoom.1.0.3.min.js';
}

// Touch use für Superfishmenü
if (TOUCH_USE == true) {
	$script_array[] = DIR_TMPL_JS .'touchuse.min.js';
}

// MMenü
if (MMENU == true)
	$script_array[] = DIR_TMPL_JS .'jquery.mmenu.min.js';

// einfaches Responsivmenü
if (RESPONSIVEMENU == true)
	$script_array[] = DIR_TMPL_JS .'resmenu.min.js';


$script_min = DIR_TMPL_JS.'tpl_plugins.min.js';

$this_f_time = filemtime(DIR_FS_CATALOG.DIR_TMPL_JS.'general_bottom.js.php');

if (COMPRESS_JAVASCRIPT == 'true') {
	require_once(DIR_FS_BOXES_INC.'combine_files.inc.php');
	$script_array = combine_files($script_array,$script_min,false,$this_f_time);
}

foreach ($script_array as $script) {
	$script .= strpos($script,$script_min) === false ? '?v=' . filemtime(DIR_FS_CATALOG.$script) : '';
	echo '<script src="'.DIR_WS_BASE.$script.'" type="text/javascript"></script>'.PHP_EOL;
}

ob_start();
foreach(auto_include(DIR_FS_CATALOG.DIR_TMPL_JS.'/extra/','php') as $file) require ($file);
$javascript = ob_get_clean();
if (COMPRESS_JAVASCRIPT == 'true') {
  require_once(DIR_TMPL.'source/external/compactor/compactor.php');
  $compactor = new BS3_Compactor(array('strip_php_comments' => false, 'compress_scripts' => true));
  $javascript = $compactor->squeeze($javascript);
}
echo $javascript.PHP_EOL;
?>

