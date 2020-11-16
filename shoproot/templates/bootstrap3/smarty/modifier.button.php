<?php

/*
Buttons ins Bootstrapdesign umwandeln

Karl 06.02.2014
 */
  require_once ('templates/' . CURRENT_TEMPLATE . '/source/inc/button.inc.php');

  function smarty_modifier_button($Input,$newImage = "") {
		$submit = '';
                   // Bild auslesen
                    preg_match("!src=\"([^\"]*)\"[^>]*>!",$Input,$src);
                    $image = basename($src[1]);
                    // Klasse auslesen
                    preg_match("!class=\"([^\"]*)\"[^>]*>!",$Input,$param);
                    $parameters = $param ? basename($param[1]) : '';
                    // Prüfen ob Input oder Image-Tag und Alt-Tag auslesen
                    if (strpos($Input,'input') == TRUE){
                      $submit=TRUE;
                      preg_match("!<input.*?alt=\"([^\"]*)\"[^>]*>!",$Input,$alt);
                    } else {
                      preg_match("!<img.*?alt=\"([^\"]*)\"[^>]*>!",$Input,$alt);
                    }
                    $alt = $alt[1];

        if (isset($buynow) && $buynow == 'ja') {$button = getBuyNow($pID, $p_name);}
       elseif ($submit==true) {$button = preg_replace("/<input type=\"image.*?alt=(.*?)>/i", get_bootstrap_button($image, $alt, $parameters, $submit), $Input);}
      else {$button = preg_replace("/<img[^>]+\>/i", get_bootstrap_button($image, $alt), $Input);}
              
  return $button;
}
?>
