<?php
/* 
!!!!!!!!!!!!!!! Bitte beachten !!!!!!!!!!!!!!!!!!!!!
Dies ist eine erweiterte Version - angepasst an das Bootstrap-Framework 3 und
die M?chkeit bestimmte Kategorien als Megakategorien festzulegen.

Es ist wichtig, dass bei diesen Megakategorien bestimmte Regeln eingehalten werden.

M?chkeit 1:
- Hauptkategorie Level 1
	+ Unterkategorie Level 2
	+ Unterkategorie Level 2
	+ Unterkategorie Level 2
	+ Unterkategorie Level 2 ...

M?chkeit 2:
- Hauptkategorie Level 1
	+ Unterkategorie Level 2
		++ Unterkategorie Level 3
		++ Unterkategorie Level 3
		++ Unterkategorie Level 3 ...
	+ Unterkategorie Level 2
		++ Unterkategorie Level 3
		++ Unterkategorie Level 3
		++ Unterkategorie Level 3 ...
	+ Unterkategorie Level 2 ...
		++ Unterkategorie Level 3
		++ Unterkategorie Level 3
		++ Unterkategorie Level 3 ...

Weitere Level erzeugen Fehler
Gibt es einen 3. Level m?ssen alle Unterkategorien Level 2 ?ber Unterkategorien Level 3 verf?gen.

// -----------------------------------------------------------------------------------
	GUNNART "SHOW_CATEGORY ADVANCED"
	
	erweiterte Kategorien-Navigation f?r xt:Commerce 3.04 SP1 / SP2.1
	
	Proudly togetherfummeled by Gunnar Tillmann
	http://www.gunnart.de
	Version 2.0 Beta 3 / April 2008 
// -----------------------------------------------------------------------------------
	... ist noch Beta - Anleitung unter http://www.gunnart.de?p=360
	6. Mai 2008: BugFix beim festlegen der $cache_id
// -----------------------------------------------------------------------------------
*/	


// -----------------------------------------------------------------------------------
// 	Smarty starten
// -----------------------------------------------------------------------------------
	$box_smarty 	= 	new Smarty;
	$box_content	=	'';
	$box_smarty->assign('tpl_path','templates/'.CURRENT_TEMPLATE.'/');
	$box_smarty->assign('language', $_SESSION['language']);
	$box_smarty->assign('home', xtc_href_link());
// -----------------------------------------------------------------------------------


// -----------------------------------------------------------------------------------
// 	Cache-ID setzen
// -----------------------------------------------------------------------------------
	if (!CacheCheck()) {
		
		$cache = false;
		$box_smarty->caching = 0;
		$cache_id = '';

	} else {

		$cache = true;
		$box_smarty->caching = 1;
		$box_smarty->cache_lifetime=CACHE_LIFETIME;
		$box_smarty->cache_modified_check=CACHE_CHECK;
		
		$cache_id = 'lID:'.$_SESSION['language'].'|csID:'.$_SESSION['customers_status']['customers_status_id'];
		if(!empty($GLOBALS['cPath'])) 
			$cache_id .= '|cP:'.$GLOBALS['cPath'];
		elseif(!empty($_GET['coID']))
			$cache_id .= '|coID:'.$_GET['coID'];
		else
			$cache_id .= '_Script-'.basename($_SERVER['SCRIPT_NAME']);

			$cache_id = md5($cache_id);

	}
// -----------------------------------------------------------------------------------


// -----------------------------------------------------------------------------------
//	Das alles braucht nur dann ausgef?hrt zu werden, wenn noch keine gecachtes 
//	HTML-File vorliegt
// -----------------------------------------------------------------------------------
	if (!$box_smarty->is_cached(CURRENT_TEMPLATE.'/boxes/box_categories_superfish.html', $cache_id) || !$cache) {
	
		// -------------------------------------------------------------------------------
		//	CategoriesArray (f?r $foo) zusammenbauen
		// -------------------------------------------------------------------------------
			function initCategoriesArray_superfish() {
				if (GROUP_CHECK=='true') {
					$group_check = "and c.group_permission_".$_SESSION['customers_status']['customers_status_id']."=1 ";
				} else {
					$group_check = '';
				}

			// ---------------------------------------------------------------------------
			//	Datenbank ...
			// ---------------------------------------------------------------------------
				$categories_query = xtc_db_query("
					SELECT	c.categories_id,
							cd.categories_name, 
							cd.categories_heading_title, 
							c.parent_id 
					FROM 	".TABLE_CATEGORIES." c, 
							".TABLE_CATEGORIES_DESCRIPTION . " cd 
					WHERE 	c.categories_status = '1' 
							".$group_check." 
					AND 	c.categories_id = cd.categories_id
					AND 	cd.language_id='" . (int)$_SESSION['languages_id'] ."'
					ORDER BY sort_order, cd.categories_name");
			// ---------------------------------------------------------------------------
			//	Array zusammenfriemeln ...
			// ---------------------------------------------------------------------------
				while ($categories = xtc_db_fetch_array($categories_query))  {
					$Cats[$categories['categories_id']] = array(	
						'id' 					=> 	$categories['categories_id'],
						'name' 					=> 	$categories['categories_name'],
						'heading' 				=> 	$categories['categories_heading_title'],
						
						'parent' 				=> 	$categories['parent_id'],
						'subcats'				=> 	false,
						
						'link'					=>	initCategoryLink($categories['categories_id'],$categories['categories_name'],$categories['categories_heading_title'])
					);
				}
			// ---------------------------------------------------------------------------
			//	... und gleich die SubCats ermitteln. 
			//	Die Funktion xtc_has_category_subcategories() k?mmert sich leider nicht um 
			// 	Berechtigungen und Status aktiv/inaktiv. Daher machen wir das hier. Spart
			//	Auߥrdem gleich noch ein paar Datenbank-Abfragen ...
			// ---------------------------------------------------------------------------
				$Keys = array_keys($Cats);
				foreach($Keys as $Key) {
					if($Cats[$Key]['parent']!=0) {
						if ($Key) $Cats[$Cats[$Key]['parent']]['subcats'] = array();
						$Cats[$Cats[$Key]['parent']]['subcats'][]=$Key;
					}
				}
		// -------------------------------------------------------------------------------
				if(!empty($Cats))
					return $Cats;
				return false;
			}
		// -------------------------------------------------------------------------------


		// -----------------------------------------------------------------------------------
			function initContentManagerLink($coID=false) {
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
			function initProductsLink($ProdID=false,$DateCheck=true) {
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


		// -----------------------------------------------------------------------------------
		//	Link ermitteln - sucht dabei gleich nach "Ersatz-Markern"
		//	Mit {#coID=7#} wird z.B. zur ContentManager-Seite 7 verlinkt (Kontakt)
		//	Mit {#pID=123#} zum Produkt mit ID 123
		//	Mit {#account.php#} zur Seite "Mein Konto"
		// -----------------------------------------------------------------------------------
		//	So kann man diverse Links in EINE Kategorien-Navigation setzen.
		// -----------------------------------------------------------------------------------
			function initCategoryLink($CatID=false,$CatName=false,$CatHeading=false) {
				$CategoryLink = false;
				if($CatID) {
					if($CatHeading) {
						if(preg_match("/\{#([^#\{\}]*\.php[^#\{\}]*)#\}/",$CatHeading,$LinkedScriptComplete)) {
							if(preg_match("/(.*\.php)(.*)/",$LinkedScriptComplete[1],$LinkedScript)) {
								if(file_exists($LinkedScript[1])) 
									$CategoryLink = xtc_href_link($LinkedScript[1]).$LinkedScript[2];
							}
						} elseif(preg_match("/\{#[^\{\}]*coID\=(\d*)[^\{\}]*#\}/i",$CatHeading,$Treffer)) {
							$CategoryLink = initContentManagerLink(intval($Treffer[1]));
						} elseif(preg_match("/\{#[^\{\}]*pID\=(\d*)[^\{\}]*#\}/i",$CatHeading,$Treffer)) {
							$CategoryLink = initProductsLink(intval($Treffer[1]));
						}
					}
					if(!$CategoryLink) {
						$CategoryLink = xtc_href_link(FILENAME_DEFAULT,xtc_category_link(intval($CatID),$CatName));
					}
				}
				return $CategoryLink;
			}
		// -----------------------------------------------------------------------------------


		// -----------------------------------------------------------------------------------
		// include needed functions
		// -----------------------------------------------------------------------------------
			require_once(DIR_FS_CATALOG .'templates/'.CURRENT_TEMPLATE. '/source/inc/xtc_show_category_superfish.inc.php');
			//require_once(DIR_FS_INC . 'xtc_has_category_subcategories.inc.php');
			//require_once(DIR_FS_INC . 'xtc_count_products_in_category.inc.php');
		// -----------------------------------------------------------------------------------


		// -----------------------------------------------------------------------------------
			$categories_string = '';
			$foo = initCategoriesArray_superfish();

		// Karl Megakategorien festlegen und die Unterkategorien auslesen

///// 	Hier werden die Megakategorien angegeben
/////	z.B. f?r die Hauptkategorie mit der ID 34 - $megacat_ids = array(34);
/////	z.B. f?r die Hauptkategorien mit der ID 34 und 38 - $megacat_ids = array(34, 38);

		$megacats = array();

		if (SUPERFISHMENU_MAXLEVEL > 2 || SUPERFISHMENU_MAXLEVEL === false){

			$megacat_ids = json_decode(SUPERFISH_MEGACATEGORIES);

			foreach ($megacat_ids as $megacat_id) {
				$megacat_id = (int)$megacat_id;
				// Level 2
				if(is_array($foo[$megacat_id]['subcats'])) {
					foreach ($foo[$megacat_id]['subcats'] as $key => $value) {$megas[$megacat_id][] = $value;}
				}
				// Level 3
				foreach ($megas[$megacat_id] as $Key) {
					if (is_array($foo[$Key]['subcats'])) {
						$megas[$megacat_id][] = $foo[$Key]['subcats'];
						if (SUPERFISHMENU_MAXLEVEL > 3 || SUPERFISHMENU_MAXLEVEL === false){
							// Level 4
							foreach($foo[$Key]['subcats'] as $val) {
							if ($val) $megas[$megacat_id][] = $foo[$val]['subcats'];
							}
						}
					}
				}
            	$megas[$megacat_id][] = $megacat_id;
			}

			// Multiarray in einfaches Array umwandeln
			if (isset($megas)) {
				$it = new RecursiveIteratorIterator(new RecursiveArrayIterator($megas));
				foreach($it as $v) {
					$megacats[] = $v;
				}
			}
		}

			xtc_show_category_superfish(0, 0, $foo, '', $megacats);
		// -----------------------------------------------------------------------------------


		// -----------------------------------------------------------------------------------
		// 	NaviListe bekommt die ID "CatNavi"
		// -----------------------------------------------------------------------------------
//	?bernommen in Templatedatei		$CatNaviStart = "\t\t<ul id=\"main\" class=\"nav navbar-nav\">\n";
		// -----------------------------------------------------------------------------------


		// -----------------------------------------------------------------------------------
		// 	H䴴e man auch einfacher machen k?n, aber mit Tabulatoren ist schicker.
		// 	Auߥrdem kann man so leichter nachpr?fen, ob auch wirklich alles korrekt l䵦t.
		// -----------------------------------------------------------------------------------

//			$CatNaviEnd = '';
// Karl wenn mehrere Level beim letzten Men?punkt sind wurden die Listenpunkte nicht mehr geschlossen
//				$CatNaviEnd .= "</li>\n</ul>\n";
		// -----------------------------------------------------------------------------------


		// -----------------------------------------------------------------------------------
		// 	Fertige Liste zusammensetzen
		// -----------------------------------------------------------------------------------
			$box_smarty->assign('BOX_CONTENT', $categories_string);
			if (RESPONSIVEMENU === true && MMENU === false) $box_smarty->assign('BOX_CONTENT2', $categories_string2);
			if (MMENU === true) $box_smarty->assign('BOX_CONTENT3', $categories_string3);
			$box_smarty->assign('language', $_SESSION['language']);
		// -----------------------------------------------------------------------------------
	}
// -----------------------------------------------------------------------------------


// -----------------------------------------------------------------------------------
//	Ausgabe ans Template
// -----------------------------------------------------------------------------------
	if(!$cache) {
		$box_categories = $box_smarty->fetch(CURRENT_TEMPLATE.'/boxes/box_categories_superfish.html');
		if (RESPONSIVEMENU === true && MMENU === false) $box_categories2 = $box_smarty->fetch(CURRENT_TEMPLATE.'/boxes/box_categories_responsivemenu.html');
		if (MMENU === true) $box_categories3 = $box_smarty->fetch(CURRENT_TEMPLATE.'/boxes/box_categories_mmenu.html');
	} else {
		$box_categories = $box_smarty->fetch(CURRENT_TEMPLATE.'/boxes/box_categories_superfish.html',$cache_id);
		if (RESPONSIVEMENU === true && MMENU === false) $box_categories2 = $box_smarty->fetch(CURRENT_TEMPLATE.'/boxes/box_categories_responsivemenu.html',$cache_id);
		if (MMENU === true) $box_categories3 = $box_smarty->fetch(CURRENT_TEMPLATE.'/boxes/box_categories_mmenu.html',$cache_id);
	}
	$smarty->assign('box_CATEGORIES_SUPERFISH',$box_categories);
	if (RESPONSIVEMENU === true && MMENU === false) $smarty->assign('box_CATEGORIES_RESPONSIVEMENU',$box_categories2);
	if (MMENU === true) $smarty->assign('box_CATEGORIES_MMENU',$box_categories3);
// -----------------------------------------------------------------------------------


?>