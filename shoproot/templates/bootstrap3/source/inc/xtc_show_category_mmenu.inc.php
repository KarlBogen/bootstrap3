<?php
/* 
// -----------------------------------------------------------------------------------
	GUNNART "SHOW_CATEGORY ADVANCED"
	
	erweiterte Kategorien-Navigation f?r xt:Commerce 3.04 SP1 / SP2.1
	
	Proudly togetherfummeled by Gunnar Tillmann
	http://www.gunnart.de
	Version 2.0 Beta / April 2008 
// -----------------------------------------------------------------------------------
	... ist noch Beta - Anleitung unter http://www.gunnart.de?p=360
// -----------------------------------------------------------------------------------
*/


// -----------------------------------------------------------------------------------
// 	KONFIGURATION
// -----------------------------------------------------------------------------------
	$CatConfig = array(
		
		// Bis zu welcher Ebene soll der Kategorien-Baum standardm䟩g 
		// aufgeklappt sein? 
		// false, wenn er komplett ausgeklappt sein soll.
		'MaxLevel' 			=> 	MMENU_MAXLEVEL,
		
		// Leere Kategorien verstecken? true: ja, false: nein
		'HideEmpty' 		=> 	true,
		
		// D?rfen aktive Kategorien weitere Unterkategorien aufklappen lassen?
		'ShowAktSub' 		=> 	false,
		
		// Kategorien-Tiefe: Wie soll die CSS-Klasse benannt werden?
		'ListPrefix'		=>	'',
		
		// Aktive Kategorie: Soll der Link markiert werden?
		'MarkAktivLink'		=> 	true,
		'LinkCurrent'		=> 	'active',
		'LinkCurrentParent'	=> 	'',
		
		// Aktive Kategorie: Soll der Listenpunkt markiert werden?
		'MarkAktivList'		=> 	true,
		'ListCurrent'		=>	'Selected active',
		'ListCurrentParent'	=>	'',
		
		// Sollen Kategorien mit weiteren Unterkategorien gekennzeichnet werden?
		'MarkSubMenue'		=> 	false,
		'SubMenueCss'		=> 	'haschild',

		// Automatische Zuteilung einer CSS-ID (f?r den Listenpunkt)
		'ShowCssIdList'		=> 	false,
		'CssPrefixList'		=> 	'MyCat',
		
		// Automatische Zuteilung einer CSS-ID (f?r den Link)
		'ShowCssIdLink'		=> 	false,
		'CssPrefixLink'		=> 	'MyCatLink',
		
		// Darstellung Produktz䨬ung, falls eingeschaltet
		'CountPre'			=> 	'&nbsp;<span class="badge">',
		'CountAfter'		=>	'</span>',
		
		// Tags auߥrhalb des Links?
		'LinkPre'			=>	false,		// z.B. '<div>',
		'LinkAfter'			=>	false,		// z.B. '</div>',

		// Tags innerhalb des Links?
		'NamePre'			=>	false,		// z.B. '<span>',
		'NameAfter'			=>	false,		// z.B. '</span>',
		
		// Soll die ܢerschrift nach Css-Markern ࠬa {#class:EinName#} 
		// durchsucht werden? So kann man z.B. einzelne 
		// Links speziell gestalten.
		'CssMarkersToList'	=>	false, 		// Gefundene Marker zur Liste?
		'CssMarkersToLink'	=>	false		// Gefundene Marker zum Link?
	
	);
// -----------------------------------------------------------------------------------
	$CurrentURL = xtc_href_link(basename($_SERVER['SCRIPT_NAME']),xtc_get_all_get_params(array('XTCsid')));
// -----------------------------------------------------------------------------------


// -----------------------------------------------------------------------------------
//	Findet heraus, ob Kategorie $category_id aktive (und f?r die Kundengruppe 
//	zugelassene) Artikel enth䬴. 
// -----------------------------------------------------------------------------------
//	Im Gegensatz zu xtc_count_products_in_category()
// 	werden hierbei die Berechtigungen und der FSK-Status gepr?ft.
// -----------------------------------------------------------------------------------
	function countProductsInCatMmenu($category_id) {
	
		$products_count = 0;
	 	if ($_SESSION['customers_status']['customers_fsk18_display'] == '0') {
			$fsk_lock = "AND \tp.products_fsk18!=1 ";
		} else {
			$fsk_lock = '';
		}
		if (GROUP_CHECK=='true') {
	   		$prod_group_check = "AND \tp.group_permission_".$_SESSION['customers_status']['customers_status_id']."=1 ";
		} else {
	   		$prod_group_check = '';
		}
		$products_query = xtDBquery("
			SELECT 	count(*) as total 
			FROM 	".TABLE_PRODUCTS." p, 
			".TABLE_PRODUCTS_TO_CATEGORIES." p2c 
			WHERE 	p.products_id = p2c.products_id 
			".$prod_group_check."
			".$fsk_lock." 
			AND	 	p.products_status = '1' 
			AND 	p2c.categories_id = '".$category_id."'");
		$products = xtc_db_fetch_array($products_query,true);
		$products_count += $products['total'];
		
		if (GROUP_CHECK=='true') {
			$cat_group_check = "AND \tgroup_permission_".$_SESSION['customers_status']['customers_status_id']."=1 ";
		} else {
			$cat_group_check = '';
		}
		$child_categories_query = xtDBquery("
			SELECT 	categories_id 
			FROM 	".TABLE_CATEGORIES." 
			WHERE 	parent_id = '".$category_id."' 
			".$cat_group_check."
			AND 	categories_status = '1'");
		if (xtc_db_num_rows($child_categories_query,true)) {
			while ($child_categories = xtc_db_fetch_array($child_categories_query,true)) {
				$products_count += countProductsInCatMmenu($child_categories['categories_id']);
			}
		}
		
		return $products_count;
	}
// -----------------------------------------------------------------------------------


// -----------------------------------------------------------------------------------
//	... ist $CurrentURL im Kategorien-Pfad drin?
// -----------------------------------------------------------------------------------
	function isInPathMmenu($CurrentURL,$CatID=false) {
		global $foo;
		if($CatID) {
			if($CurrentURL == $foo[$CatID]['link']) {
				return true;
			} elseif(is_array($foo[$CatID]['subcats'])) {
				foreach($foo[$CatID]['subcats'] as $SubCatID) {
					if(isInPathMmenu($CurrentURL,$SubCatID))
						return true;
				}
			}
		}
		return false;
	}
// -----------------------------------------------------------------------------------


// -----------------------------------------------------------------------------------
//	Hauptfunktion
// -----------------------------------------------------------------------------------
	function xtc_show_category_mmenu($cid, $level, $foo, $cpath) {
	
		global	$old_level, 
				$categories_string,
				$CatConfig,
				$CurrentURL;
		
		// Declare variables
		$ListClass = array();
		$LinkClass = array();
		$ListID = array();
		$LinkID = array();
		$Pre = array();

		$CatConfig['MaxLevel'] = intval($CatConfig['MaxLevel']);
		
		// 1) Z䨬en ist nicht immer n?
		if($CatConfig['HideEmpty'] || SHOW_COUNTS == 'true')
			$pInCat = countProductsInCatMmenu($cid);
	
		// 2) ܢerpr?fen, ob Kategorie Produkte enth䬴
		if($CatConfig['HideEmpty']) {
			$Empty = true;
			if ($pInCat > 0)
				$Empty = false;
		} else {
			$Empty = false;
		}	
		
		// 3) ܢerpr?fen, ob Kategorie gezeigt werden soll
		$Show = false;
		if($CatConfig['HideEmpty']) {
			if(!$Empty)
				$Show = true;
		} else {
			$Show = true;
		}
	
		// 3) ܢerpr?fen, ob Unterkategorien gezeigt werden sollen
		$ShowSub = false;
		if($CatConfig['MaxLevel']) {
			if ($level < $CatConfig['MaxLevel'])
				$ShowSub = true;
		} else {
			$ShowSub = true;
		}
					
		if($Show) { // Wenn Kategorie gezeigt werden soll ....
		
			if($cid != 0) {
				
				$category_path 		= explode('_',$GLOBALS['cPath']); 
				$in_path 			= in_array($cid, $category_path);
				$this_category 		= array_pop($category_path);
				
				if(empty($this_category)) {
					if(isInPathMmenu($CurrentURL,$cid))
						$in_path = true;
				}
			
				for ($a = 0; $a < $level; $a++);
				
				$ProductsCount = false;
				if(SHOW_COUNTS == 'true') 
					$ProductsCount = ' '.$CatConfig['CountPre'].$pInCat.$CatConfig['CountAfter'];	
	                                                  
				// Aktiv - Nicht Aktiv usw.
				$Collapse 
				= $Expand 
				= $Aktiv 
				= $AktivList 
				= $AktivLink 
				= $CssClassMarker 
				= false;
				
				// Nach Collapse- bzw. Expand-Markern suchen
				if(strpos(strtolower($foo[$cid]['heading']),'{#collapse#}') !== false)
					$Collapse = true;
				if(strpos(strtolower($foo[$cid]['heading']),'{#expand#}') !== false)
					$Expand = true;
					
				$ListClass[] = $CatConfig['ListPrefix'];
				
				// Nach CSS-Markern suchen
				if($CatConfig['CssMarkersToList']||$CatConfig['CssMarkersToLink']) {
					if(preg_match("/\{\#class\:([^\#\}]+)\#\}/i",$foo[$cid]['heading'],$Treffer)) { 
						$CssClassMarker = trim($Treffer[1]);
						if($CatConfig['CssMarkersToList']&&!empty($CssClassMarker))
							$ListClass[] = $CssClassMarker;
						if($CatConfig['CssMarkersToLink']&&!empty($CssClassMarker))
							$LinkClass[] = $CssClassMarker;
					}
				}
				
				if($this_category == $cid || $foo[$cid]['link'] == $CurrentURL) {
					// Wenn Kategorie aktiv ist
					if($CatConfig['MarkAktivLink']) {
						$LinkClass[] = $CatConfig['LinkCurrent'];
					}
					if($CatConfig['MarkAktivList']) {
						$ListClass[] = $CatConfig['ListCurrent'];
					}
					$Aktiv = true;
				}elseif($in_path) { 
					// Wenn Oberkategorie aktiv ist
					if($CatConfig['MarkAktivLink']) {
						$LinkClass[] = $CatConfig['LinkCurrentParent'];
					} 
					if($CatConfig['MarkAktivList']) {
						$ListClass[] = $CatConfig['ListCurrentParent'];
					}
					$Aktiv = true;
				}
		
				// Hat ein SubMenue - hat kein SubMenue
				// CSS-Klasse festlegen
				if($CatConfig['MarkSubMenue'] && $foo[$cid]['subcats']) {
					$ListClass[] = $CatConfig['SubMenueCss'];
				}
				
				// Quelltext einr?cken
				$Tabulator = str_repeat("\t",$level+1);

				if($CatConfig['ShowCssIdList']) {
					$ListID[] = $CatConfig['CssPrefixList'].$cid;
				}
				
				if($CatConfig['ShowCssIdLink']) {
					$LinkID[] = $CatConfig['CssPrefixLink'].$cid;
				}
		
				// Navigations-Liste hierarchisch ...
				if($old_level) { 
					if ($old_level < $level) {
						$Pre = "\n<ul>";
          $UlLevel = $level-1;  
					$UlListClass = ' class="cssmenu level'.$UlLevel.'">';
						$Pre = str_replace("<ul>",$Tabulator."<ul".$UlListClass, $Pre)."\n";

					} else {
						$Pre = "</li>\n";
						if ($old_level > $level) {
							// Listenpunkte schlieߥn
							// Quelltext einr?cken
							for ($counter = 0; $counter < $old_level - $level; $counter++) {
								$Pre .= str_repeat("\t", $old_level - $counter+1 )."</ul>\n".str_repeat("\t", $old_level - $counter)."</li>\n";
							}
						}
					} 
				}
				
				if(is_array($ListClass)) {
					$ListClass = ' class="'.implode(' ',$ListClass).'"';
				}
				if(is_array($ListID)) {
					$ListID = ' id="'.implode(' ',$ListID).'"';
				}
				if(is_array($LinkClass)) {
					$LinkClass = ' class="'.implode(' ',$LinkClass).'"';
				}
				if(is_array($LinkID)) {
					$LinkID = ' id="'.implode(' ',$LinkID).'"';
				}

				// Listenpunkte zusammensetzen
				$categories_string .=	$Pre.
										$Tabulator.
										'<li'.$ListID.$ListClass.'>'.$CatConfig['LinkPre'].
										'<a'.$LinkID.$LinkClass.' href="' . $foo[$cid]['link'] . '"><span>'.
										$CatConfig['NamePre'].
										$foo[$cid]['name'].
										$ProductsCount.
										$CatConfig['NameAfter'].
										'</span></a>'.
										$CatConfig['LinkAfter'];
			}
			
			// f?r den n䣨sten Durchgang ...
			$old_level = $level;
		
			// Unterkategorien durchsteppen
			foreach ($foo as $key => $value) {
		
				if ($foo[$key]['parent'] == $cid) {
						
					// Sollen Unterkategorien gezeigt werden?
					if($CatConfig['ShowAktSub'] && $Aktiv)
						$ShowSub = true;
					
					// Wenn Collapse, dann immer eingeklappt
					if($ShowSub && isset($Collapse) && $Collapse && !$Aktiv)
						$ShowSub = false;
					
					// Wenn Expand, dann ausgeklappt
					if($ShowSub || $Expand) 
						xtc_show_category_mmenu($key, $level+1, $foo, ($level != 0 ? $cpath . $cid . '_' : ''));
				} 
			}
		} // Ende if($Show)
	} 	
// -----------------------------------------------------------------------------------
		
?>