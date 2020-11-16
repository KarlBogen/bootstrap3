<?php
/* -----------------------------------------------------------------------------------------
   $Id$

   modified eCommerce Shopsoftware
   http://www.modified-shop.org

   Copyright (c) 2009 - 2013 [www.modified-shop.org]
   -----------------------------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------------------------*/

defined( '_VALID_XTC' ) or die( 'Direct Access to this location is not allowed.' );

class aa_slider_installer_system {
  var $code, $title, $description, $enabled;

  function __construct() {
     $this->code = 'aa_slider_installer_system';
     $this->title = 'SLIDER-Beispiele für Template Bootstrap3';
     $this->description = 'Dieses Modul dient nur dazu, Beispiel-Bilder in den Banner Manger zu installieren.<br />Nach der Installation einfach die Datei<br /><b>admin/includes/modules/system/aa_slider_installer_system.php</b> löschen.';
     $this->enabled = ((AA_SLIDER_INSTALLER_SYSTEM_STATUS == 'true') ? true : false);

   }

  function process($file) {
  }

  function display() {
    return array('text' => '<br /><div align="center">' . xtc_button(BUTTON_SAVE) .
                           xtc_button_link(BUTTON_CANCEL, xtc_href_link(FILENAME_MODULE_EXPORT, 'set=' . $_GET['set'] . '&module=aa_slider_installer_system')) . "</div>");
  }

  function check() {
    if (!isset($this->_check)) {
      $check_query = xtc_db_query("SELECT configuration_value
                                     FROM " . TABLE_CONFIGURATION . "
                                    WHERE configuration_key = 'AA_SLIDER_INSTALLER_SYSTEM_STATUS'");
      $this->_check = xtc_db_num_rows($check_query);
    }
    return $this->_check;
  }
    
  function install() {

		global $messageStack;

		$install = true;

		// Banner installieren
		xtc_db_query("update " . TABLE_BANNERS . " set status = '0' where banners_group = 'slider'");


        $sql_data_array = array(
          'banners_title' => 'slider1',
          'banners_url' => 'http://www.google.de',
          'banners_image' => 'bild1.jpg',
          'banners_group' => 'slider',
          'banners_html_text' =>
		  '<h2>Beispiel Überschrift</h2>
              <p>Lorem ipsum dolor sit amet consectetuer vestibulum platea at sem Phasellus...</p>
              <p><a class="btn btn-primary" href="#" role="button"> Mehr... </a></p>',
          'languages_id' => '2',
          'status' => '1',
          'date_added' => 'now()'

        );

        if (!xtc_db_perform(TABLE_BANNERS, $sql_data_array)) {
			$install = false;
		}

        $sql_data_array['banners_title'] = 'slider2';
        $sql_data_array['banners_image'] = 'bild3.jpg';
        $sql_data_array['banners_html_text'] =
			'<h2>Beispiel Überschrift 2</h2>
              <p>Lorem ipsum dolor sit amet consectetuer vestibulum platea at sem ...</p>
              <p><a class="btn btn-primary" href="#" role="button"> Mehr... </a></p>';

        if (!xtc_db_perform(TABLE_BANNERS, $sql_data_array)) {
			$install = false;
		}

        $sql_data_array['banners_title'] = 'slider3';
        $sql_data_array['banners_image'] = 'bild4.jpg';
        $sql_data_array['banners_html_text'] =
			'<h2>Beispiel Überschrift 3</h2>
              <p>Lorem ipsum dolor sit amet consectetuer Pellentesque at pretium tincidunt...</p>
              <p><a class="btn btn-primary" href="#" role="button"> Mehr... </a></p>';

        if (!xtc_db_perform(TABLE_BANNERS, $sql_data_array)) {
			$install = false;
		}

        $sql_data_array = array(
          'banners_title' => 'slider1',
          'banners_url' => 'http://www.google.de',
          'banners_image' => 'bild1.jpg',
          'banners_group' => 'slider',
          'banners_html_text' =>
		  '<h2>Sample title</h2>
              <p>Lorem ipsum dolor sit amet consectetuer vestibulum platea at sem Phasellus...</p>
              <p><a class="btn btn-primary" href="#" role="button"> Mehr... </a></p>',
          'languages_id' => '1',
          'status' => '1',
          'date_added' => 'now()'

        );

        if (!xtc_db_perform(TABLE_BANNERS, $sql_data_array)) {
			$install = false;
		}

        $sql_data_array['banners_title'] = 'slider2';
        $sql_data_array['banners_image'] = 'bild3.jpg';
        $sql_data_array['banners_html_text'] =
			'<h2>Sample title</h2>
              <p>Lorem ipsum dolor sit amet consectetuer vestibulum platea at sem ...</p>
              <p><a class="btn btn-primary" href="#" role="button"> Mehr... </a></p>';

        if (!xtc_db_perform(TABLE_BANNERS, $sql_data_array)) {
			$install = false;
		}

        $sql_data_array['banners_title'] = 'slider3';
        $sql_data_array['banners_image'] = 'bild4.jpg';
        $sql_data_array['banners_html_text'] =
			'<h2>Sample title</h2>
              <p>Lorem ipsum dolor sit amet consectetuer Pellentesque at pretium tincidunt...</p>
              <p><a class="btn btn-primary" href="#" role="button"> Mehr... </a></p>';

        if (!xtc_db_perform(TABLE_BANNERS, $sql_data_array)) {
			$install = false;
		}

		if ($install == true){
			$messageStack->add_session('Installation beendet!<br />Versuche die Datei <strong>admin/includes/modules/system/aa_slider_installer_system.php</strong> zu l&ouml;schen.', 'success');
			unlink(__FILE__);
		} else {
			$messageStack->add_session('Ein Fehler ist aufgetreten! Bitte erneut versuchen!', 'error');
		}


  }

  function remove() {
  }

  function keys() {
  }
}
?>