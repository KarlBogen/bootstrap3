<?php
/* -----------------------------------------------------------------------------------------
   $Id: general.css.php 10665 2017-04-06 18:13:26Z web28 $

   modified eCommerce Shopsoftware
   http://www.modified-shop.org

   Copyright (c) 2009 - 2013 [www.modified-shop.org]
   -----------------------------------------------------------------------------------------
   based on:
   (c) 2006 XT-Commerce

   Released under the GNU General Public License
   ---------------------------------------------------------------------------------------*/

  define('DIR_TMPL', 'templates/'.CURRENT_TEMPLATE.'/');
  define('DIR_TMPL_CSS', DIR_TMPL.'css/');

  if ($_SESSION['customers_status']['customers_status'] == '0') {
    echo '<link rel="stylesheet" property="stylesheet" href="'.DIR_WS_BASE.DIR_TMPL_CSS.'adminbar.css" type="text/css" media="screen" />';
  }

	$css_array = array();
	// include bootstrap
	if (BOOTSTRAP_THEME != "default-theme") {
		$css_array[] = DIR_TMPL_CSS.'bootstrap/'.BOOTSTRAP_THEME.'.min.css';
	} else {
		$css_array[] = DIR_TMPL_CSS.'bootstrap/default.min.css';
		$css_array[] = DIR_TMPL_CSS.'bootstrap/theme.min.css';
	}

	if (defined('MODULE_COOKIE_CONSENT_STATUS') && strtolower(MODULE_COOKIE_CONSENT_STATUS) == 'true') {
		$css_array[] = DIR_TMPL_CSS.'jquery.cookieconsent-oil.css';
	}
	else {
		$css_array[] = DIR_TMPL_CSS.'jquery.cookieconsent.css';
	}

	// Cloud Zoom
	if (USE_CLOUD_ZOOM === true) {
		$css_array[] = DIR_TMPL_CSS.'cloud-zoom.css';
	}

	$css_array[] = DIR_TMPL_CSS.'pushy.min.css';
	$css_array[] = DIR_TMPL_CSS.'jquery.alertable.css';
	if (MMENU === true)
	$css_array[] = DIR_TMPL_CSS.'mmenu.css';

    $css_array[] = DIR_TMPL.'stylesheet.css';

	$css_min = DIR_TMPL_CSS.'stylesheet.min.css';

  $this_f_time = filemtime(DIR_FS_CATALOG.DIR_TMPL_CSS.'general.css.php');

  if (COMPRESS_STYLESHEET == 'true') {
    require_once(DIR_FS_BOXES_INC.'combine_files.inc.php');
    $css_array = combine_files($css_array,$css_min,true,$this_f_time);
  }

  // Put CSS-Inline-Definitions here, these CSS-files will be loaded at the TOP of every page
  
  foreach ($css_array as $css) {
    $css .= strpos($css,$css_min) === false ? '?v=' . filemtime(DIR_FS_CATALOG.$css) : '';
    echo '<link rel="stylesheet" href="'.DIR_WS_BASE.$css.'" type="text/css" media="screen" />'.PHP_EOL;
  }
?>