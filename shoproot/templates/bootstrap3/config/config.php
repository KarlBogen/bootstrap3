<?php
/* -----------------------------------------------------------------------------------------
   $Id: config.php 14256 2022-04-01 14:43:10Z Tomcraft $

   modified eCommerce Shopsoftware
   http://www.modified-shop.org

   Copyright (c) 2009 - 2013 [www.modified-shop.org]
   -----------------------------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------------------------*/

	/* Version 1.1.4 */

  /*
   *  template specific defines
   */
  

  // logo
  define('SHOP_LOGO', 'img/logo_head.png'); // Dieses Logo wird im Template gezeigt z.B. shoproot/templates/bootstrap3/img/logo_head.png -> 'img/logo_head.png'

  // Javascript im Browser deaktiviert
  define('SHOW_JS_DISABLED', true); // Hinweis anzeigen = true, ansonsten false

  // Erweiterte Javascript Validation im Registrierungsformular - danke an Hanspeter siehe https://www.modified-shop.org/forum/index.php?topic=37218.msg344232#msg344232
  define('ADVANCED_JS_VALIDATION', false); // benutzen = true, ansonsten false

  // Cloud Zoom
  define('USE_CLOUD_ZOOM', true); // Cloud Zoom in der Produkt-Info-Ansicht verwenden = true, ansonsten false

  // Bootstrap-Theme
  define('BOOTSTRAP_THEME', 'flatly'); 	// Folgende Themes stehen dank https://bootswatch.com zur Verfügung (dort können Sie auch die Less- und Sass-Dateien finden):
  										// default, default-theme, cerulean, cosmo, cyborg, darkly, flatly, journal, lumen, paper, readable, sandstone, simplex, slate, spacelab, superhero, united, yeti
										// einfach den jeweiligen Name einsetzen z.B. "define('BOOTSTRAP_THEME', 'flatly');" steht für das Theme 'flatly'.

  // gilt für alle Menüs
  defined('SPECIALS_CATEGORIES') or define('SPECIALS_CATEGORIES', false); // 'true' zeigt den Link "Angebote" im Kategoriebaum / 'false' zeigt die "Angebote" als separate Box
  defined('WHATSNEW_CATEGORIES') or define('WHATSNEW_CATEGORIES', false); // 'true' zeigt den Link "Neue Artikel" im Kategoriebaum / 'false' zeigt die "Neue Artikel" als separate Box
  define('WHATSNEW_SPECIALS_UPPERCASE', true); // Links "Angebote" und "Neue Artikel" in GROßBUCHSTABEN anzeigen = true, ansonsten false

  // categories - das Modified Standardmenü
  define('CATEGORIESMENU_MAXLEVEL', false); // Bis zu welcher Ebene soll der Kategorien-Baum standardmäßig aufklapptbar sein? // false, wenn er komplett ausgeklappt sein soll, ansonsten eine Zahl.

  // responsivemenü - das einfachere Menü für kleine Bildschirme
  define('RESPONSIVEMENU', true); // responsivemenü anzeigen = true, ansonsten false - Achtung: Funktioniert nur, wenn Superfishmenü auf "true" steht, da der Menübaum benutzt wird
  define('SPECIALS_CATEGORIES_RESPONSIVEMENU', true); // Angebote als Link im responsivemenü anzeigen = true, ansonsten false
  define('WHATSNEW_CATEGORIES_RESPONSIVEMENU', true); // Neue Artikel als Link im responsivemenü anzeigen = true, ansonsten false
  define('RESPONSIVEMENU_MAXLEVEL', false); // Bis zu welcher Ebene soll der Kategorien-Baum standardmäßig aufklapptbar sein? // false, wenn er komplett ausgeklappt sein soll, ansonsten eine Zahl.

  // mmenü - das aufwendigere Menü für kleine Bildschirme (zusätzliche JS- und CSS-Datei wird geladen)
  define('MMENU', false); // mmenü anzeigen = true, ansonsten false
  define('SPECIALS_CATEGORIES_MMENU', true); // Angebote als Link im mmenü anzeigen = true, ansonsten false
  define('WHATSNEW_CATEGORIES_MMENU', true); // Neue Artikel als Link im mmenü anzeigen = true, ansonsten false
  define('MMENU_MAXLEVEL', false); // Bis zu welcher Ebene soll der Kategorien-Baum standardmäßig aufklapptbar sein? // false, wenn er komplett ausgeklappt sein soll, ansonsten eine Zahl.

  // Superfishmenü
  define('SUPERFISHMENU', true); // Superfishmenü anzeigen = true, ansonsten false
  define('SUPERFISHMENU_MAXLEVEL', false); // Bis zu welcher Ebene soll der Kategorien-Baum standardmäßig aufklapptbar sein? // false, wenn er komplett ausgeklappt sein soll, ansonsten eine Zahl.
  define('TOUCH_USE', true); // Superfishmenü für Touchscreens nutzbar machen? Ja = true, Nein = false

/*
!!!!!!!!!!!!!!! Bitte beachten !!!!!!!!!!!!!!!!!!!!!
Dies ist eine erweiterte Version - angepasst an das Bootstrap-Framework 3 und
die Möglichkeit bestimmte Kategorien als Megakategorien festzulegen.

Es ist wichtig, dass bei diesen Megakategorien bestimmte Regeln eingehalten werden.

Möglichkeit 1:
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

Möglichkeit 2:
- Hauptkategorie Level 1
	+ Unterkategorie Level 2
		++ Unterkategorie Level 3
			++++ Unterkategorie Level 4
			++++ Unterkategorie Level 4
			++++ Unterkategorie Level 4 ...
		++ Unterkategorie Level 3
			++++ Unterkategorie Level 4
			++++ Unterkategorie Level 4
			++++ Unterkategorie Level 4 ...
		++ Unterkategorie Level 3
			++++ Unterkategorie Level 4 ...
	+ Unterkategorie Level 2
		++ Unterkategorie Level 3
			++++ Unterkategorie Level 4
			++++ Unterkategorie Level 4 ...
		++ Unterkategorie Level 3
			++++ Unterkategorie Level 4 ...
		++ Unterkategorie Level 3
	+ Unterkategorie Level 2 ...
		++ Unterkategorie Level 3
			++++ Unterkategorie Level 4
			++++ Unterkategorie Level 4 ...
		++ Unterkategorie Level 3
			++++ Unterkategorie Level 4 ...
		++ Unterkategorie Level 3 ...

Weitere Level erzeugen Fehler
Gibt es einen 3. Level müssen alle Unterkategorien Level 2 über Unterkategorien Level 3 verfügen.

Beispiel - Kategorien 3 und 44 sollen Megakategorien werden:
$SUPERFISH_MEGACATEGORIES = array(3,44);

*/
$SUPERFISH_MEGACATEGORIES = array();

// KK-Megamenü
$KK_MEGAS = array(); // falls gewünscht, hier die Kategorien und Anzahl der Reihen gem. den Beispielen eintragen
/*
Beispiele KK-Megamenü:

Alle Kategorien sollen als Mega-Menü mit 3 Spalten dargestellt werden.
$KK_MEGAS = array('main-3');
Eingetragen wird die ID der Navbar 'main'.

Es sollen die Kategorien mit der ID 5 (3-spaltig) und ID 22 (4-spaltig) als Mega-Menü dargestellt werden.
$KK_MEGAS = array('Cat5-3','Cat22-4');
Eingetragen werden die ID's der Kategorienlinks 'Cat5-3' und 'Cat22-4' - die Schreibweise ist hier wichtig.

Ende KK-Megamenü */

  // Produktdetailansicht Boxen anzeigen oder volle Breite
  defined('PROD_DETAIL_FULLCONTENT') or define('PROD_DETAIL_FULLCONTENT', true); // 'true' zeigt die Detailansicht in voller Breite / 'false' zeigt die Detailansicht mit Boxen

  define('PRODUCT_LIST_BOX', ((isset($_SESSION['listbox'])) ? $_SESSION['listbox'] : 'true')); // 'true' zeigt Artikel in Kategorie-Navigation (product_listing) als Box-Ansicht / 'false' zeigt Listen-Ansicht
  define('PRODUCT_LIST_BOX_STARTPAGE', 'true'); // 'true' zeigt "Unsere TOP-Artikel" als Box-Ansicht / 'false' zeigt als Listen-Ansicht
  define('PRODUCT_INFO_BOX', 'true'); // 'true' zeigt Cross-Selling-, Reverse-Cross-Selling- & Also-Purchased-Artikel auf Artikel-Detailseite als Box-Ansicht / 'false' zeigt als Listen-Ansicht

  // check specials
  if (SPECIALS_CATEGORIES === true) {
    require_once (DIR_FS_INC.'check_specials.inc.php');
    define('SPECIALS_EXISTS', check_specials());
  }
  
  // check whats new
  /*
  if (WHATSNEW_CATEGORIES === true) {
    require_once (DIR_FS_INC.'check_whatsnew.inc.php');
    define('WHATSNEW_EXISTS', check_whatsnew());
  }
  */
  
// -----------------------------------------------------------------------------------
// 	Ab hier nichts ändern
// -----------------------------------------------------------------------------------

  // paths
  define('DIR_FS_BOXES', DIR_FS_CATALOG .'templates/'.CURRENT_TEMPLATE. '/source/boxes/');
  define('DIR_FS_BOXES_INC', DIR_FS_CATALOG .'templates/'.CURRENT_TEMPLATE. '/source/inc/');

  // popup
  define('TPL_POPUP_SHIPPING_LINK_PARAMETERS', '');
  define('TPL_POPUP_SHIPPING_LINK_CLASS', 'iframe');
  define('TPL_POPUP_CONTENT_LINK_PARAMETERS', '');
  define('TPL_POPUP_CONTENT_LINK_CLASS', 'iframe');
  define('TPL_POPUP_PRODUCT_LINK_PARAMETERS', '');
  define('TPL_POPUP_PRODUCT_LINK_CLASS', 'iframe');
  define('TPL_POPUP_COUPON_HELP_LINK_PARAMETERS', '');
  define('TPL_POPUP_COUPON_HELP_LINK_CLASS', 'iframe');
  define('TPL_POPUP_PRODUCT_PRINT_SIZE', '');
  define('TPL_POPUP_PRINT_ORDER_SIZE', '');

  // template output
  define('TEMPLATE_ENGINE', 'smarty_4'); // 'smarty_4' oder 'smarty_3' oder 'smarty_2' -> Nicht ändern! (Nur "smarty_4" oder "smarty_3" unterstützt die custom Sprachdateien (lang_english.custom & lang_german.custom) aus dem Ordner "../lang/" des Templates!)
  define('TEMPLATE_HTML_ENGINE', 'html5'); // 'html5' oder 'xhtml' -> Nicht ändern!
  define('TEMPLATE_RESPONSIVE', 'true'); // 'true' oder 'false' -> Nicht ändern!
  defined('COMPRESS_JAVASCRIPT') or define('COMPRESS_JAVASCRIPT', false); // 'true' kombiniert & komprimiert die zusätzliche JS-Dateien / 'false' bindet alle JS-Dateien einzeln ein

  // set base
  defined('DIR_WS_BASE') OR define('DIR_WS_BASE', xtc_href_link('', '', $request_type, false, false));

  // css buttons
  if (is_file(DIR_FS_CATALOG.'templates/'.CURRENT_TEMPLATE.'/source/inc/css_button.inc.php')) {
    require_once(DIR_FS_CATALOG.'templates/'.CURRENT_TEMPLATE.'/source/inc/css_button.inc.php');
  }

  define('SUPERFISH_MEGACATEGORIES', json_encode($SUPERFISH_MEGACATEGORIES)); // hier nichts ändern

	switch (BOOTSTRAP_THEME) {
		case 'default':
		case 'default-theme':
    	case 'cerulean':
    	case 'united':
		case 'darkly':
			define('TOP1_NAVBAR', 'inverse'); define('TOP2_NAVBAR', 'default'); define('FOOTER_NAVBAR', 'inverse');
			break;
		case 'sandstone':
		default:
			define('TOP1_NAVBAR', 'inverse'); define('TOP2_NAVBAR', 'default'); define('FOOTER_NAVBAR', 'default');
			break;
		case 'readable':
			define('TOP1_NAVBAR', 'default'); define('TOP2_NAVBAR', 'inverse'); define('FOOTER_NAVBAR', 'inverse');
        	break;
    	case 'cosmo':
		case 'flatly':
		case 'journal':
		case 'lumen':
		case 'paper':
		case 'simplex':
		case 'slate':
		case 'spacelab':
		case 'superhero':
			define('TOP1_NAVBAR', 'default'); define('TOP2_NAVBAR', 'inverse'); define('FOOTER_NAVBAR', 'default');
        	break;
		case 'yeti':
			define('TOP1_NAVBAR', 'default'); define('TOP2_NAVBAR', 'default'); define('FOOTER_NAVBAR', 'default');
        	break;
    	case 'cyborg':
			define('TOP1_NAVBAR', 'inverse'); define('TOP2_NAVBAR', 'inverse'); define('FOOTER_NAVBAR', 'inverse');
        	break;
}

?>