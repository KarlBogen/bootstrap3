<?php
/* 
// -----------------------------------------------------------------------------------
	GUNNART "SHOW_CATEGORY ADVANCED"
	
	erweiterte Kategorien-Navigation für xt:Commerce 3.04 SP1 / SP2.1
	
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
		
		// Bis zu welcher Ebene soll der Kategorien-Baum standardmäßig 
		// aufgeklappt sein? 
		// false, wenn er komplett ausgeklappt sein soll.
		'MaxLevel' 			=> 	SUPERFISHMENU_MAXLEVEL,
		
		// Leere Kategorien verstecken? true: ja, false: nein
		'HideEmpty' 		=> 	false,
		
		// Dürfen aktive Kategorien weitere Unterkategorien aufklappen lassen?
		'ShowAktSub' 		=> 	false,
		
		// Kategorien-Tiefe: Wie soll die CSS-Klasse benannt werden?
		'ListPrefix'		=>	'men',
		
		// Aktive Kategorie: Soll der Link markiert werden?
		'MarkAktivLink'		=> 	true,
		'LinkCurrent'		=> 	'active',
		'LinkCurrentParent'	=> 	'active',
		
		// Aktive Kategorie: Soll der Listenpunkt markiert werden?
		'MarkAktivList'		=> 	true,
		'ListCurrent'		=>	'Selected active',
		'ListCurrentParent'	=>	'active',
		
		// Sollen Kategorien mit weiteren Unterkategorien gekennzeichnet werden?
		'MarkSubMenue'		=> 	true,
		'SubMenueCss'		=> 	'haschild',

		// Automatische Zuteilung einer CSS-ID (für den Listenpunkt)
		'ShowCssIdList'		=> 	true,
		'CssPrefixList'		=> 	'Cat',
		
		// Automatische Zuteilung einer CSS-ID (für den Link)
		'ShowCssIdLink'		=> 	true,
		'CssPrefixLink'		=> 	'CatLi',
		
		// Darstellung Produktzählung, falls eingeschaltet
		'CountPre'			=> 	'&nbsp;<span class="badge">',
		'CountAfter'		=>	'</span>',
		
		// Tags außerhalb des Links?
		'LinkPre'			=>	false,		// z.B. '<div>',
		'LinkAfter'			=>	false,		// z.B. '</div>',

		// Tags innerhalb des Links?
		'NamePre'			=>	false,		// z.B. '<span>',
		'NameAfter'			=>	false,		// z.B. '</span>',
		
		// Soll die Überschrift nach Css-Markern à la {#class:EinName#} 
		// durchsucht werden? So kann man z.B. einzelne 
		// Links speziell gestalten.
		'CssMarkersToList'	=>	false, 		// Gefundene Marker zur Liste?
		'CssMarkersToLink'	=>	false		// Gefundene Marker zum Link?
	
	);
// -----------------------------------------------------------------------------------
	$CurrentURL = xtc_href_link(basename($_SERVER['SCRIPT_NAME']),xtc_get_all_get_params(array('XTCsid')));
// -----------------------------------------------------------------------------------


// -----------------------------------------------------------------------------------
//	Findet heraus, ob Kategorie $category_id aktive (und für die Kundengruppe 
//	zugelassene) Artikel enthält. 
// -----------------------------------------------------------------------------------
//	Im Gegensatz zu xtc_count_products_in_category()
// 	werden hierbei die Berechtigungen und der FSK-Status geprüft.
// -----------------------------------------------------------------------------------
	function countProductsInCat($category_id) {
	
		$products_count = 0;
		$fsk_lock = $_SESSION['customers_status']['customers_fsk18_display'] == '0' ? "AND \tp.products_fsk18!=1 " : '';
   		$prod_group_check = GROUP_CHECK=='true' ? "AND \tp.group_permission_".$_SESSION['customers_status']['customers_status_id']."=1 " : '';
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
		
		$cat_group_check = GROUP_CHECK=='true' ? "AND \tgroup_permission_".$_SESSION['customers_status']['customers_status_id']."=1 " : '';
		$child_categories_query = xtDBquery("
			SELECT 	categories_id 
			FROM 	".TABLE_CATEGORIES." 
			WHERE 	parent_id = '".$category_id."' 
			".$cat_group_check."
			AND 	categories_status = '1'");
		if (xtc_db_num_rows($child_categories_query,true)) {
			while ($child_categories = xtc_db_fetch_array($child_categories_query,true)) {
				$products_count += countProductsInCat($child_categories['categories_id']);
			}
		}
		
		return $products_count;
	}
// -----------------------------------------------------------------------------------


// -----------------------------------------------------------------------------------
//	... ist $CurrentURL im Kategorien-Pfad drin?
// -----------------------------------------------------------------------------------
	function isInPath($CurrentURL,$CatID=false) {
		global $foo;
		if($CatID) {
			if($CurrentURL == $foo[$CatID]['link']) {
				return true;
			} elseif(is_array($foo[$CatID]['subcats'])) {
				foreach($foo[$CatID]['subcats'] as $SubCatID) {
					if(isInPath($CurrentURL,$SubCatID))
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
	function xtc_show_category_superfish($cid, $level, $foo, $cpath, $megacats) {

		global	$old_level,
				$categories_string,
				$categories_string2,
				$categories_string3,
				$CatConfig,
				$Mega,
                $active,
                $tabID,
                $string_level2,
                $string_level3,
				$CurrentURL;

		// Declare variables
		$LinkClass = array();
		$ListClass = array();
		$ListID = array();
		$LinkID = array();
		$Pre = '';
		$Pre2 = '';
		$Pre3 = '';

		$CatConfig['MaxLevel'] = intval($CatConfig['MaxLevel']);

		// 1) Zählen ist nicht immer nötig
		if($CatConfig['HideEmpty'] || SHOW_COUNTS == 'true')
			$pInCat = countProductsInCat($cid);

		// 2) Überprüfen, ob Kategorie Produkte enthält
		if($CatConfig['HideEmpty']) {
			$Empty = true;
			if ($pInCat > 0)
				$Empty = false;
		} else {
			$Empty = false;
		}

		// 3) Überprüfen, ob Kategorie gezeigt werden soll
		$Show = false;
		if($CatConfig['HideEmpty']) {
			if(!$Empty)
				$Show = true;
		} else {
			$Show = true;
		}

		// 3) Überprüfen, ob Unterkategorien gezeigt werden sollen
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
					if(isInPath($CurrentURL,$cid))
						$in_path = true;
				}

				for ($a = 0; $a < $level; $a++);

				$ProductsCount = false;
				if(SHOW_COUNTS == 'true') {
					$CountPre = $level == 1 ? str_replace('badge','badge pull-right', $CatConfig['CountPre']) : $CatConfig['CountPre'];
					$ProductsCount = ' '.$CountPre.$pInCat.$CatConfig['CountAfter'];
				}

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
						if ($level == RESPONSIVEMENU_MAXLEVEL) $ListClass[] = 'selected';
					}
					$Aktiv = true;
				}

				// Hat ein SubMenue - hat kein SubMenue
				// CSS-Klasse festlegen
				$haschild = '';
				if($CatConfig['MarkSubMenue'] && $foo[$cid]['subcats'] && (empty($CatConfig['MaxLevel']) || (!empty($CatConfig['MaxLevel']) && $level < $CatConfig['MaxLevel']))) {
					$haschild = 'text-muted ';
					$ListClass[] = $CatConfig['SubMenueCss'];
				}

				// Quelltext einrücken
				$Tabulator = str_repeat("\t",$level+1);

				if($CatConfig['ShowCssIdList']) {
					$ListID[] = $CatConfig['CssPrefixList'].$cid;
				}

				if($CatConfig['ShowCssIdLink']) {
					$LinkID[] = $CatConfig['CssPrefixLink'].$cid;
				}

				// Karl MegaKlasse und Prüfung, ob Link in Megacats
				$MegaClass = '';
				if (is_array($megacats) && in_array($cid, $megacats)) {
					$MegaClass = $ListClass[] = ' mega';
				}
				$ListClass[] = 'level'.$level;

				// Navigations-Liste hierarchisch ...
                if($old_level == 0) {
					$categories_string2 .= "\t".'<ul class="'.$cid.'">'."\n";
                	$categories_string3 .= "\t".'<ul>'."\n";
				}
				if($old_level) {
					if ($old_level < $level) {
						$Pre = "\n<ul>";
						$UlListClass = ' class="dropdown-menu'.$MegaClass.' level'.$level.' col'.$cid.'">';
						$Pre = str_replace("<ul>",$Tabulator."<ul".$UlListClass, $Pre)."\n";
						$Pre2 = "\n<ul>";
						$UlListClass2 = ' class="'.$cid.'" data-level="'.$level.'">';
						$Pre2 = $Pre3 = str_replace("<ul>",$Tabulator."<ul".$UlListClass2, $Pre2)."\n";
					} else {
						$Pre = "</li>\n";
						$Pre2 = "</li>\n";
						$Pre3 = "</li>\n";
						if ($old_level > $level) {
							// Listenpunkte schließen
							// Quelltext einrücken
							for ($counter = 0; $counter < $old_level - $level; $counter++) {
								$Pre .= str_repeat("\t", $old_level - $counter+1 )."</ul>\n".str_repeat("\t", $old_level - $counter)."</li>\n";
								$Pre2 .= str_repeat("\t", $old_level - $counter+1 )."</ul>\n".str_repeat("\t", $old_level - $counter)."</li>\n";
								$Pre3 .= str_repeat("\t", $old_level - $counter+1 )."</ul>\n".str_repeat("\t", $old_level - $counter)."</li>\n";
							}
						}
					}
				}

				if(is_array($ListClass)) {
					$ListClass = $ListClass2 = ' class="'.implode(' ',$ListClass).'"';
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

				// Karl Anpassung an Bootstrap
				if ($foo[$cid]['subcats'] && $level < 2 && (empty($CatConfig['MaxLevel']) || (!empty($CatConfig['MaxLevel']) && $level < $CatConfig['MaxLevel']))) {
					$caret = '<span class="caret"></span>'; // Karl Caret hinzufügen
				} elseif ($foo[$cid]['subcats'] && $level > 1 && !$MegaClass && (empty($CatConfig['MaxLevel']) || (!empty($CatConfig['MaxLevel']) && $level < $CatConfig['MaxLevel']))) {
					$caret = '<span class="caret-right"></span>'; // Karl Caret ab Level 2 hinzufügen
				} else {
					$caret = ''; // Karl Caret hinzufügen
				}

				if ($foo[$cid]['subcats'] && (empty($CatConfig['MaxLevel']) || (!empty($CatConfig['MaxLevel']) && $level < $CatConfig['MaxLevel']))){
					$ListClass = $level < 2 ? str_replace('class="','class="dropdown ',$ListClass) : str_replace('class="','class="dropdown dropdown-submenu ',$ListClass); // Karl Dropdownklasse einfügen
				}
				// Karl Ende
				// Listenpunkte zusammensetzen

				// $categories_string2 wird nur für Responsivemenü gebraucht
				if (RESPONSIVEMENU === true && MMENU === false) $categories_string2 .=	$Pre2.
										$Tabulator.
										'<li id="li'.$cid.'"'.$ListClass2.'>'.$CatConfig['LinkPre'].
										'<a id="a'.$cid.'"'.$LinkClass.' href="' . $foo[$cid]['link'] . '">'.
										$CatConfig['NamePre'].
										$foo[$cid]['name'].
										$CatConfig['NameAfter'].
										str_replace('pull-right','', $ProductsCount).'</a>'.
										$CatConfig['LinkAfter'];

				// $categories_string3 wird nur für Mmenü gebraucht
				if (MMENU === true) $categories_string3 .=	$Pre3.
										$Tabulator.
										'<li'.$ListClass2.'>'.$CatConfig['LinkPre'].
										'<a href="' . $foo[$cid]['link'] . '">'.
										$CatConfig['NamePre'].
										$foo[$cid]['name'].
										$CatConfig['NameAfter'].
										str_replace('pull-right','', $ProductsCount).'</a>'.
										$CatConfig['LinkAfter'];

				// Beginn MegaClass
				// Level 2
				if ($MegaClass && $level > 1 && $level == 2 && is_array($foo[$cid]['subcats']) ) {
					$Mega = true;
				    $tabID = $cid;
					if ($Aktiv) $active = 'active';
					if ($old_level == 1) {
						if (!$Aktiv) $active = '[place]';
						$string_level2 .= $Pre.$Tabulator.'<li>'."\n\t\t\t\t".'<ul id="myTab'.$cid.'" class="nav col'.$cid.' clearfix nav-pills">'."\n";
					}
				    $string_level2 .= $Tabulator."\t".'<li'.$ListID.str_replace('dropdown-submenu', $active, $ListClass).'>'.$CatConfig['LinkPre'].
												'<a'.$LinkID.$LinkClass.' href="#id' . $tabID . '" data-toggle="tab"><span class="' . $CatConfig['ListPrefix'] . '">'.
												$CatConfig['NamePre'].
												$foo[$cid]['name'].
												$CatConfig['NameAfter'].
												'</span>'.$caret.$ProductsCount.'</a>'.
												$CatConfig['LinkAfter']."</li>\n";
					if ($old_level == 3) {
						$string_level3 .= $Tabulator."\t\t\t".'</ul>'."\n".$Tabulator."\t\t".'</div>'."\n";
					}
					if ($old_level == 4) {
				    	$string_level3 .= $Tabulator."\t\t\t\t".'</ul>'."\n".$Tabulator."\t\t\t".'</div>'."\n".$Tabulator."\t\t\t".'</li>'."\n";
						$string_level3 .= $Tabulator."\t\t\t".'</ul>'."\n".$Tabulator."\t\t".'</div>'."\n";
					}
				}
				// Level 3
				elseif ($MegaClass && $level > 1 && $level == 3) {
					if ($old_level == 4) {
				    	$string_level3 .= $Tabulator."\t\t\t".'</ul>'."\n".$Tabulator."\t\t".'</div>'."\n".$Tabulator."\t\t".'</li>'."\n";
					}
					if ($old_level == 3) {
				    	$string_level3 .= "</li>\n";
					}
					if ($old_level == 2) {
				    	$string_level3 .= $Tabulator."\t".'<div id="id'. $tabID . '" class="tab-pane '.$active.$MegaClass.'">'."\n".$Tabulator."\t\t".'<ul class="col' . $cid . ' level' . $level . ' nav-pills">'."\n";
				        $active = '';
					}
					if (($old_level == 2 || $old_level == 4) && $foo[$cid]['subcats']){
						$search = array('dropdown-submenu','active');
				    	$string_level3 .= $Tabulator."\t\t".'<li'.$ListID.str_replace($search, '', $ListClass).'>'.$CatConfig['LinkPre'].
												'<a'.$LinkID.str_replace('="', '="'.$haschild, $ListClass).' href="' . $foo[$cid]['link'] . '"><span class="' . $CatConfig['ListPrefix'] . '">'.
												$CatConfig['NamePre'].
												$foo[$cid]['name'].
												$CatConfig['NameAfter'].
												'</span>'.$ProductsCount.'</a>'.
												$CatConfig['LinkAfter'];
					} else {
				    	$string_level3 .= $Tabulator."\t\t".'<li'.$ListID.str_replace('dropdown-submenu', '', $ListClass).'>'.$CatConfig['LinkPre'].
												'<a'.$LinkID.str_replace('="', '="'.$haschild, $ListClass).' href="' . $foo[$cid]['link'] . '"><span class="' . $CatConfig['ListPrefix'] . '">'.
												$CatConfig['NamePre'].
												$foo[$cid]['name'].
												$CatConfig['NameAfter'].
												'</span>'.$caret.$ProductsCount.'</a>'.
												$CatConfig['LinkAfter'];
					}
				}
				// Level 4
				elseif ($MegaClass && $level > 1 && $level == 4) {
					if ($old_level == 3) {
				    	$string_level3 .= "\n".$Tabulator."\t".'<div class="level'.$level.'">'."\n".$Tabulator."\t\t".'<ul class="col' . $cid . ' nav-pills">'."\n";
					}
				    $string_level3 .= $Tabulator."\t\t".'<li'.$ListID.$ListClass.'>'.$CatConfig['LinkPre'].
												'<a'.$LinkID.$LinkClass.' href="' . $foo[$cid]['link'] . '"><span class="' . $CatConfig['ListPrefix'] . '">'.
												$CatConfig['NamePre'].
												$foo[$cid]['name'].
												$CatConfig['NameAfter'].
												'</span>'.$caret.$ProductsCount.'</a>'.
												$CatConfig['LinkAfter']."</li>\n";
				}
				else {

					if ($level == 1 && $Mega != false) {
						if ($old_level == 4) {
				    		$string_level3 .= $Tabulator."\t\t\t\t\t".'</ul>'."\n".$Tabulator."\t\t\t\t".'</div>'."\n".$Tabulator."\t\t\t\t".'</li>'."\n";
						}
						if ($old_level == 3) {
				    		$string_level3 .= "</li>\n";
						}
						$string_level2 = strpos($string_level2, 'active') ? str_replace('[place]', '', $string_level2) : str_replace('[place]', 'active', $string_level2);
						$string_level3 = strpos($string_level3, 'active') ? str_replace('[place]', '', $string_level3) : str_replace('[place]', 'active', $string_level3);
						$categories_string .=	$string_level2 .$Tabulator. "\t\t</ul>\n" .$Tabulator. "\t\t".'<div class="tab-content">'."\n" . $string_level3 .$Tabulator."\t\t\t\t". "</ul>\n".$Tabulator."\t\t\t"."</div>\n".$Tabulator."\t\t"."</div>\n";
						$string_level2 = '';
						$string_level3 = '';
						$tabID = '';
						$Mega = false;
					    $Pre = $Tabulator."\t".'</li>'."\n".$Tabulator."\t".'</ul>'."\n".$Tabulator.'</li>'."\n";
				}

				// Listenpunkte zusammensetzen
				$categories_string .=	$Pre.
										$Tabulator.
										'<li'.$ListID.$ListClass.'>'.$CatConfig['LinkPre'].
										'<a'.$LinkID.$LinkClass.' href="' . $foo[$cid]['link'] . '"><span class="' . $CatConfig['ListPrefix'] . '">'.
										$CatConfig['NamePre'].
										$foo[$cid]['name'].
										$CatConfig['NameAfter'].
										'</span>'.$caret.$ProductsCount.'</a>'.
										$CatConfig['LinkAfter'];

				} // MegaClass

			}

			// für den nächsten Durchgang ...
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
						xtc_show_category_superfish($key, $level+1, $foo, ($level != 0 ? $cpath . $cid . '_' : ''), $megacats);
				}
			}
		} // Ende if($Show)
if ($level == 0 && $categories_string != '') {
			$CatNaviStart = "\t\t<ul id=\"main\" class=\"nav navbar-nav\">\n";
			$CatNaviEnd = '';
			for ($counter = 1; $counter < $old_level; $counter++) {
				$CatNaviEnd .= "</li>\n".str_repeat("\t", $old_level+2 - $counter)."</ul>\n";
				if ($old_level - $counter > 0)
					$CatNaviEnd .= str_repeat("\t", ($old_level+2 - $counter)-1);
			}
				$CatNaviEnd .= "</li>\n";
				if ($old_level - $counter > 0)
					$CatNaviEnd .= str_repeat("\t", ($old_level+2 - $counter)-1);
//	übernommen in Templatedatei	damit die Links Angebote und neue Artikel eingefügt werden können
//	$CatNaviEnd .= str_repeat("\t", $old_level+2 - $counter)."</ul>\n";
$categories_string = $CatNaviStart.$categories_string.$CatNaviEnd;
$categories_string2 = $categories_string2.$CatNaviEnd;
$categories_string3 = $categories_string3.$CatNaviEnd;
}

	}
// -----------------------------------------------------------------------------------

?>