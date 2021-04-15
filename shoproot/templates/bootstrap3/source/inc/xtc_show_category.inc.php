<?php
  /* -----------------------------------------------------------------------------------------
   $Id: xtc_show_category.inc.php 12822 2020-07-09 06:24:46Z GTB $

   modified eCommerce Shopsoftware
   http://www.modified-shop.org

   Copyright (c) 2009 - 2013 [www.modified-shop.org]
   -----------------------------------------------------------------------------------------
   based on: 
   (c) 2000-2001 The Exchange Project  (earlier name of osCommerce)
   (c) 2002-2003 osCommerce(categories.php,v 1.23 2002/11/12); www.oscommerce.com
   (c) 2003   nextcommerce (xtc_show_category.inc.php,v 1.4 2003/08/13); www.nextcommerce.org 
   (c) 2010  web28 (xtc_show_category.inc.php, v 2.1 2010/11/12); www.rpa-com.de

   Released under the GNU General Public License 
   ---------------------------------------------------------------------------------------*/   

  function xtc_show_category($counter, $oldlevel=1) {
    global $foo, $categories_string, $id, $cPath;  
    $level = $foo[$counter]['level']+1;

// Max-Level eingefügt
if ($foo[$counter]['level'] < CATEGORIESMENU_MAXLEVEL || CATEGORIESMENU_MAXLEVEL === false) {

    //BOF +++ UL LI Verschachtelung  mit Quelltext Tab Einzügen +++
    $ul = $tab = '';  
    for ($i = 1; $i <= $level; $i++) {
      $tab .= "\t";
    }    

    if ($level > $oldlevel) { //neue Unterebene
      $ul = "\n" . $tab. '<div class="panel panel-default"><ul class="list-group">'. "\n";
      $categories_string = rtrim($categories_string, "\n"); //Zeilenumbruch entfernen
      $categories_string = substr($categories_string, 0, -5);  //letztes  </li>  entfernen
    } elseif ($level < $oldlevel) { //zurück zur höheren Ebene
	if ($oldlevel > CATEGORIESMENU_MAXLEVEL && CATEGORIESMENU_MAXLEVEL != false) {
      $ul = close_ul_tags($level,CATEGORIESMENU_MAXLEVEL);
	} else {
      $ul = close_ul_tags($level,$oldlevel);
	}
    }
    //EOF +++ UL LI Verschachtelung  mit Quelltext Tab Einzügen +++

    //BOF +++ Kategorien markieren +++
    $category_path = explode('_',$cPath); //Kategoriepfad in Array einlesen

    //Elternkategorie markieren
    $cat_active_parent = '';
    $in_path = in_array($counter, $category_path); //Testen, ob aktuelle Kategorie ID im Kategoriepfad enthalten ist
    if ($in_path && CATEGORIESMENU_MAXLEVEL == $level) $cat_active_parent = " active";
//    if (!$in_path && $level == 1) $cat_active_parent = " hide";

    //Aktive Kategorie markieren
    $cat_active = '';
    $this_category = array_pop($category_path); //Letzter Eintrag im Array ist die aktuelle Kategorie
//    if ($this_category == $counter) $cat_active = " active".$level;
    if ($this_category == $counter) $cat_active = " active";
    //EOF +++ Kategorien markieren +++

    // Meine Änderung Zeichen für Kategorie mit Unterkategorie einfügen
		$IPre = '';
          if ($foo[$counter]['hassubcat'] === true && ($level < CATEGORIESMENU_MAXLEVEL || CATEGORIESMENU_MAXLEVEL === false)) {
          $IPre = '<span class="caret"></span>';
          }

		// -----------------------------------------------------------------------------------
		//	Link ermitteln - sucht dabei gleich nach "Ersatz-Markern"
		//	Mit {#coID=7#} wird z.B. zur ContentManager-Seite 7 verlinkt (Kontakt)
		//	Mit {#pID=123#} zum Produkt mit ID 123
		//	Mit {#account.php#} zur Seite "Mein Konto"
		// -----------------------------------------------------------------------------------
		//	So kann man diverse Links in EINE Kategorien-Navigation setzen.
		// -----------------------------------------------------------------------------------

		if (isset($foo[$counter]['heading']) && $foo[$counter]['heading'] != '') {
			$CatHeading = $foo[$counter]['heading'];
			$newHref = initNewHref($CatHeading);
			if (!empty($newHref)) $foo[$counter]['link'] = $newHref;
		}

    //BOF +++ Kategorie Linkerstellung +++
    if (trim($categories_string == '')) $categories_string = "\n"; //Zeilenschaltung Codedarstellung  
    $categories_string .= $ul; //UL LI Versschachtelung
    $categories_string .= $tab; //Tabulator Codedarstellung
    $categories_string .= '<li class="list-group-item level'.$level.$cat_active.$cat_active_parent.'">';
    $categories_string .= '<a  class="list-group-item'.$cat_active.'" href="'.$foo[$counter]['link'].'" title="'. $foo[$counter]['name'] . '">';
    $categories_string .= $foo[$counter]['name'];
    //Anzeige Anzahl der Produkte in Kategorie, für bessere Performance im Admin deaktivieren
    if (SHOW_COUNTS == 'true') {
      $products_in_category = xtc_count_products_in_category($counter);
      if ($products_in_category > 0) {
        $categories_string .= '<span class="badge badge-default pull-right">' . $products_in_category . '</span>';
      }
    }
    $categories_string .= ' '.$IPre.'</a></li>';
    $categories_string .= "\n"; //Zeilenschaltung Codedarstellung  
    //EOF  +++ Kategorie Linkerstellung +++
}

    //Nächste Kategorie
    if ($foo[$counter]['next_id']) {
      xtc_show_category($foo[$counter]['next_id'], $level);
    } else {
	if ($level > CATEGORIESMENU_MAXLEVEL && CATEGORIESMENU_MAXLEVEL != false) {
     if ($level > 1) $categories_string .= close_ul_tags(1,CATEGORIESMENU_MAXLEVEL);
	} else {
     if ($level > 1) $categories_string .= close_ul_tags(1,$level);
	}
      return;
    }
  }

		// -----------------------------------------------------------------------------------
			function initNewHref($CatHeading=false) {
				$newHref = false;
					if($CatHeading) {
						if(preg_match("/\{#([^#\{\}]*\.php[^#\{\}]*)#\}/",$CatHeading,$LinkedScriptComplete)) {
							if(preg_match("/(.*\.php)(.*)/",$LinkedScriptComplete[1],$LinkedScript)) {
								if(file_exists($LinkedScript[1]))
									$newHref = xtc_href_link($LinkedScript[1]).$LinkedScript[2];
							}
						} elseif(preg_match("/\{#[^\{\}]*coID\=(\d*)[^\{\}]*#\}/i",$CatHeading,$Treffer)) {
							$newHref = initContentManagerHref(intval($Treffer[1]));
						} elseif(preg_match("/\{#[^\{\}]*pID\=(\d*)[^\{\}]*#\}/i",$CatHeading,$Treffer)) {
							$newHref = initProductsHref(intval($Treffer[1]));
						}
					}
				return $newHref;
			}

		// -----------------------------------------------------------------------------------
			function initContentManagerHref($coID=false) {
				if($coID) {
					if (GROUP_CHECK == 'true')
						$group_check = "AND \tgroup_ids LIKE '%c_".$_SESSION['customers_status']['customers_status_id']."_group%'";
					$dbQuery = xtDBquery("
						SELECT	content_title
						FROM 	".TABLE_CONTENT_MANAGER."
						WHERE 	content_group = '".intval($coID)."'
						AND 	languages_id = '".(int) $_SESSION['languages_id']."'
						".$group_check."
						AND 	content_status = '1'");
					$dbQuery = xtc_db_fetch_array($dbQuery,true);
					if(!empty($dbQuery)){
						if(SEARCH_ENGINE_FRIENDLY_URLS == 'true')
							$SEF_parameter = '&content='.xtc_cleanName($dbQuery['content_title']);
						return xtc_href_link(FILENAME_CONTENT,'coID='.$coID.$SEF_parameter);
					}
				}
				return false;
			}
		// -----------------------------------------------------------------------------------
			function initProductsHref($ProdID=false,$DateCheck=true) {
				if($ProdID) {
					if ($_SESSION['customers_status']['customers_fsk18_display'] == '0')
						$fsk_lock = "AND \tp.products_fsk18!=1 ";
					if (GROUP_CHECK == 'true')
						$group_check = "AND \tp.group_permission_".$_SESSION['customers_status']['customers_status_id']."=1 ";
					$dbQuery = xtDBquery("
						SELECT 	p.products_id, pd.products_name
						FROM 	".TABLE_PRODUCTS_DESCRIPTION." pd,
								".TABLE_PRODUCTS." p
						WHERE 	pd.products_id = '".intval($ProdID)."'
						AND 	pd.products_id = p.products_id
						".$fsk_lock."
						".$group_check."
						AND		p.products_status = '1'
						AND 	pd.language_id = '".(int)$_SESSION['languages_id']."' ");
					$dbQuery = xtc_db_fetch_array($dbQuery,true);
					if(!empty($dbQuery['products_id']))
						return xtc_href_link(FILENAME_PRODUCT_INFO,xtc_product_link(intval($ProdID),$dbQuery['products_name']));
				}
				return false;
			}
		// -----------------------------------------------------------------------------------
?>