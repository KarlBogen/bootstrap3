<?php
/* -----------------------------------------------------------------------------------------
   $Id: languages.php 13588 2021-06-15 16:10:06Z GTB $

   modified eCommerce Shopsoftware
   http://www.modified-shop.org

   Copyright (c) 2009 - 2013 [www.modified-shop.org]
   -----------------------------------------------------------------------------------------
   based on:
   (c) 2000-2001 The Exchange Project  (earlier name of osCommerce)
   (c) 2002-2003 osCommerce(languages.php,v 1.14 2003/02/12); www.oscommerce.com
   (c) 2003 nextcommerce (languages.php,v 1.8 2003/08/17); www.nextcommerce.org
   (c) 2006 XT-Commerce

   Released under the GNU General Public License
   ---------------------------------------------------------------------------------------*/

// include smarty
include(DIR_FS_BOXES_INC . 'smarty_default.php');

// set cache id
$cache_id = md5('lID:'.$_SESSION['language'].'|site:'.basename($PHP_SELF).'|params:'.xtc_get_all_get_params());

if (!$box_smarty->is_cached(CURRENT_TEMPLATE.'/boxes/box_languages.html', $cache_id) || !$cache) {

  if (!isset($lng) || (isset($lng) && !is_object($lng))) {
    require_once(DIR_WS_CLASSES . 'language.php');
    $lng = new language;
  }

  if (count($lng->catalog_languages) > 1 && strpos(basename($PHP_SELF), 'checkout') === false) {
    $box_content = array();
    reset($lng->catalog_languages);
    foreach ($lng->catalog_languages as $key => $value) {
		if ($value['id'] == $_SESSION['languages_id']){
      		$cur_link_txt = file_exists('lang/' .  $value['directory'] .'/' . $value['image']) ? xtc_image('lang/' .  $value['directory'] .'/' . $value['image'], $value['name']) : $value['name'];
		}
      $lng_link_txt = file_exists('lang/' .  $value['directory'] .'/' . $value['image']) ? xtc_image('lang/' .  $value['directory'] .'/' . $value['image'], $value['name'], '', '', 'title="' . $value['name']. '"') . '&nbsp;&nbsp;' . $value['name'] : $value['name'];
      $lng_link_url = xtc_href_link(basename($PHP_SELF), xtc_get_all_get_params(array('language', 'currency')) . 'language=' . $key, $request_type);
      if ($lng_link_url != '#') {
        $box_content[] = ' <li><a href="' . $lng_link_url . '">' . $lng_link_txt  . '</a></li>';
      }
    }
    if (count($box_content) > 1) {
      $box_smarty->assign('CUR_LINK_TEXT', $cur_link_txt);
      $box_smarty->assign('BOX_CONTENT', implode('', $box_content));
    }
  }
}

if (!$cache) {
  $box_languages = $box_smarty->fetch(CURRENT_TEMPLATE.'/boxes/box_languages.html');
} else {
  $box_languages = $box_smarty->fetch(CURRENT_TEMPLATE.'/boxes/box_languages.html', $cache_id);
}

$smarty->assign('box_LANGUAGES', $box_languages);
?>