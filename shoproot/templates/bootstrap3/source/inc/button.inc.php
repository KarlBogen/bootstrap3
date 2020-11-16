<?php

require_once(DIR_FS_CATALOG.'templates/'.CURRENT_TEMPLATE.'/lang/buttons_'.$_SESSION['language'].'.php');

// Output a function button in the selected language
function get_bootstrap_button($image, $alt = '', $parameters = '', $submit = false) {


    $name           = $image;
    $html           = '';
    $title          = xtc_parse_input_field_data($alt, array('"' => '&quot;'));
    $leer			= '';
    
    // Erklärung: es wird geprüft, welches Buttonbild von Modified aufgerufen wird. Dementsprechend werden neue Attribute zugewiesen.
    // z.B. dem Buttonbild 'button_buy_now.gif' wird zugewiesen:
    //      'Image' => '' (kein Bild - vergleiche cart_del.gif, dort wird das Bild cart_del.gif zugewiesen, damit bleibt der Button ein Bildbutton).
    //      'Text' => IMAGE_BUTTON_IN_CART (der Text der auf dem neuen Button angezeigt wird, in der Regel der Text der Modifiedvariablen '$alt', in unserem Beispiel der Text der in der Languagedatei 'IMAGE_BUTTON_IN_CART' zugewiesen wurde).
    //      'icon' => 'icon-shopping-cart' (das Icon das im Button angezeigt wird - in der Bootstrapdokumentation unter 'Icons by Glyphicons' kann man diese aussuchen).
    //      'iconposition' => 'iconleft' (die Position des Icons im Button - 'iconleft' = links vom Text, 'iconright' = rechts vom Text).
    //      'Class' => '' (hier kann dem Button noch eine zusätzliche CSS-Klasse zugewiesen werden).
    /* Buttons array */
    $buttons = array(
    // PayPal
    'epaypal_'.$_SESSION['language_code'].'.gif'	=> array('Text' => constant('BUTTON_EPAYPAL_'.strtoupper($_SESSION['language_code']).'_TEXT'),	'glyphicon' => '',	'glyphiconposition' => 'right',	'Class' => 'btn btn-paypal btn-sm btn-block mb'),
    'default'                      	=> array('Image' => '',                       'Text' => $alt,                           'glyphicon' => '',                     		'glyphiconposition' => 'left',      'Class' => 'btn btn-default btn-sm'),
    'button_add_address.gif'        => array('Image' => '',                       'Text' => $alt,                           'glyphicon' => 'glyphicon-edit',            'glyphiconposition' => 'left',		'Class' => 'btn btn-default btn-sm'),
    'button_add_quick.gif'          => array('Image' => '',                       'Text' => IMAGE_BUTTON_IN_CART,           'glyphicon' => 'glyphicon-shopping-cart',   'glyphiconposition' => 'left',		'Class' => 'btn btn-default btn-sm'),
    'button_admin.gif'              => array('Image' => '',                       'Text' => $alt,                           'glyphicon' => 'glyphicon-wrench',          'glyphiconposition' => 'left',		'Class' => 'btn btn-default btn-sm'),
    'button_back.gif'               => array('Image' => '',                       'Text' => $alt,                           'glyphicon' => 'glyphicon-arrow-left',      'glyphiconposition' => 'left',		'Class' => 'btn btn-default btn-sm'),
    'button_buy_now.gif'            => array('Image' => '',                       'Text' => '',                             'glyphicon' => 'glyphicon-shopping-cart',   'glyphiconposition' => 'left',		'Class' => 'btn btn-cart btn-default btn-sm'),
    'button_buy_now_rqlist.gif'     => array('Image' => '',                       'Text' => '',                             'glyphicon' => 'glyphicon-shopping-cart',   'glyphiconposition' => 'left',		'Class' => ''),
    'button_change_address.gif'     => array('Image' => '',                       'Text' => $alt,                           'glyphicon' => 'glyphicon-edit',            'glyphiconposition' => 'left',		'Class' => 'btn btn-default btn-sm'),
    'button_checkout.gif'           => array('Image' => '',                       'Text' => $alt,                           'glyphicon' => 'glyphicon-credit-card',     'glyphiconposition' => 'right',		'Class' => 'btn btn-checkout btn-success btn-sm btn-block'),
    'button_confirm.gif'            => array('Image' => '',                       'Text' => $alt,                           'glyphicon' => 'glyphicon-ok',              'glyphiconposition' => 'right',		'Class' => 'btn btn-default btn-sm'),
    'button_confirm_order.gif'      => array('Image' => '',                       'Text' => $alt,                           'glyphicon' => 'glyphicon-ok',   			'glyphiconposition' => 'right',		'Class' => 'btn btn-success pull-right mt'),
    'button_continue.gif'           => array('Image' => '',                       'Text' => $alt,                           'glyphicon' => 'glyphicon-arrow-right',     'glyphiconposition' => 'right',		'Class' => 'btn btn-default btn-sm'),
    'button_in_wishlist.gif'        => array('Image' => '',                       'Text' => $alt,                           'glyphicon' => 'glyphicon-star',            'glyphiconposition' => 'left',		'Class' => 'btn btn-wish btn-info btn-sm btn-block'),
    'button_in_requestlist.gif'     => array('Image' => '',                       'Text' => $alt,                           'glyphicon' => 'glyphicon-question-sign',   'glyphiconposition' => 'right',		'Class' => 'btn btn-request btn-default btn-sm btn-block'),
    'button_requestlist.gif'        => array('Image' => '',                       'Text' => '',                             'glyphicon' => 'glyphicon-question-sign',   'glyphiconposition' => 'right',		'Class' => ''),
    'button_continue_shopping.gif'  => array('Image' => '',                       'Text' => $alt,                           'glyphicon' => 'glyphicon-arrow-left',      'glyphiconposition' => 'left',		'Class' => 'btn btn-shop btn-default btn-sm btn-block'),
    'button_delete.gif'             => array('Image' => '',                       'Text' => $alt,                           'glyphicon' => 'glyphicon-remove',          'glyphiconposition' => 'left',		'Class' => 'btn btn-danger btn-sm'),
    'button_download.gif'           => array('Image' => '',                       'Text' => $alt,                           'glyphicon' => 'glyphicon-download',        'glyphiconposition' => 'left',		'Class' => 'btn btn-default btn-sm'),
    'button_login.gif'              => array('Image' => '',                       'Text' => $alt,                           'glyphicon' => 'glyphicon-user',            'glyphiconposition' => 'left',		'Class' => 'btn btn-default btn-sm'),
    'button_logoff.gif'             => array('Image' => '',                       'Text' => $alt,                           'glyphicon' => '',                     		'glyphiconposition' => 'left',		'Class' => 'btn btn-default btn-sm'),
    'button_in_cart.gif'            => array('Image' => '',                       'Text' => $alt,                           'glyphicon' => 'glyphicon-shopping-cart',   'glyphiconposition' => 'left',		'Class' => 'btn btn-cart btn-default btn-sm btn-block'),
    'button_checkout_express.gif'   => array('Image' => '',                       'Text' => TEXT_CHECKOUT_EXPRESS_INFO_LINK,'glyphicon' => 'icon-opencart',             'glyphiconposition' => 'left',		'Class' => 'btn btn-express btn-primary btn-sm btn-block'),
    'button_login_newsletter.gif'   => array('Image' => '',                       'Text' => '',                             'glyphicon' => 'glyphicon-send',            'glyphiconposition' => 'left',		'Class' => 'btn btn-default btn-sm'),
    'button_print.gif'              => array('Image' => '',                       'Text' => $alt,                           'glyphicon' => 'glyphicon-print',           'glyphiconposition' => 'left',		'Class' => 'btn btn-default btn-sm'),
    'button_product_more.gif'       => array('Image' => '',                       'Text' => $alt,      						'glyphicon' => 'glyphicon-info-sign',       'glyphiconposition' => 'left',		'Class' => 'btn btn-info btn-sm'),
    'button_quick_find.gif'         => array('Image' => '',                       'Text' => $alt,                           'glyphicon' => 'glyphicon-search',          'glyphiconposition' => 'left',		'Class' => 'btn btn-default'),
    'button_redeem.gif'             => array('Image' => '',                       'Text' => $alt,                           'glyphicon' => 'glyphicon-asterisk',        'glyphiconposition' => 'left',		'Class' => 'btn btn-default btn-sm'),
    'button_search.gif'             => array('Image' => '',                       'Text' => $alt,                           'glyphicon' => 'glyphicon-search',          'glyphiconposition' => 'left',		'Class' => 'btn btn-default btn-sm'),
    'button_send.gif'               => array('Image' => '',                       'Text' => $alt,                           'glyphicon' => 'glyphicon-ok',              'glyphiconposition' => 'left',		'Class' => 'btn btn-default btn-sm'),
    'button_send_rental.gif'        => array('Image' => '',                       'Text' => $alt,                           'glyphicon' => 'glyphicon-ok',              'glyphiconposition' => 'left',		'Class' => 'btn btn-rental btn-danger btn-block'),
    'button_login_small.gif'        => array('Image' => '',                       'Text' => $alt,                           'glyphicon' => 'glyphicon-user',            'glyphiconposition' => 'left',		'Class' => 'btn btn-default btn-sm pull-right'),
    'button_update.gif'             => array('Image' => '',                       'Text' => $alt,                           'glyphicon' => 'glyphicon-refresh',         'glyphiconposition' => 'left',		'Class' => 'btn btn-default btn-sm'),
    'button_update_cart.gif'        => array('Image' => '',                       'Text' => $alt,                           'glyphicon' => 'glyphicon-refresh',         'glyphiconposition' => 'left',		'Class' => 'btn btn-default btn-sm'),
    'button_view.gif'               => array('Image' => '',                       'Text' => $alt,                           'glyphicon' => 'glyphicon-eye-open',        'glyphiconposition' => 'left',		'Class' => 'btn btn-default btn-sm'),
    'button_write_review.gif'       => array('Image' => '',                       'Text' => $alt,                           'glyphicon' => 'glyphicon-edit',            'glyphiconposition' => 'left',		'Class' => 'btn btn-info btn-sm'),
    'small_edit.gif'                => array('Image' => '',                       'Text' => $alt,                           'glyphicon' => 'glyphicon-edit',            'glyphiconposition' => 'left',		'Class' => 'btn btn-default btn-sm'),
    'small_express.gif'             => array('Image' => '',                       'Text' => '',                           	'glyphicon' => 'icon-opencart',            	'glyphiconposition' => 'left',		'Class' => 'btn btn-express btn-default btn-sm btn-primary'),
    'small_cart.gif'                => array('Image' => '',                       'Text' => '',                           	'glyphicon' => 'glyphicon-shopping-cart',   'glyphiconposition' => 'left',		'Class' => 'btn btn-incart btn-default btn-sm'),
    'small_delete.gif'              => array('Image' => '',                       'Text' => $alt,                           'glyphicon' => 'glyphicon-remove',          'glyphiconposition' => 'right',		'Class' => 'btn btn-danger btn-sm'),
    'small_view.gif'                => array('Image' => '',                       'Text' => $alt,                           'glyphicon' => 'glyphicon-eye-open',        'glyphiconposition' => 'right',		'Class' => 'btn btn-default btn-sm'),
    'cart_del.gif'                  => array('Image' => '',                       'Text' => '',                             'glyphicon' => 'glyphicon-trash',      		'glyphiconposition' => 'left',		'Class' => ''),
    'wishlist_del.gif'              => array('Image' => '',                       'Text' => '',                             'glyphicon' => 'glyphicon-trash',      		'glyphiconposition' => 'left',		'Class' => 'btn btn-danger btn-sm'),
    'requestlist_del.gif'           => array('Image' => '',                       'Text' => '',                             'glyphicon' => 'glyphicon-trash',      		'glyphiconposition' => 'left',		'Class' => 'btn btn-danger btn-sm'),
    'edit_product.gif'              => array('Image' => '',                       'Text' => $alt,                           'glyphicon' => 'glyphicon-edit',            'glyphiconposition' => 'left',		'Class' => 'btn btn-default btn-sm'),
    'print.gif'                     => array('Image' => '',                       'Text' => TEXT_PRINT,                     'glyphicon' => 'glyphicon-print',           'glyphiconposition' => 'left',		'Class' => 'btn btn-default btn-sm'),
    'button_goto_cart.gif'          => array('Image' => '',                       'Text' => $alt,                           'glyphicon' => 'glyphicon-shopping-cart',   'glyphiconposition' => 'left',		'Class' => 'btn btn-default btn-sm'),
    'button_manufactors_info.gif'   => array('Image' => '',                       'Text' => $alt,                           'glyphicon' => 'glyphicon-list-alt',        'glyphiconposition' => 'left',		'Class' => 'btn btn-default btn-sm'),
    );

    if (!array_key_exists($name, $buttons)) {$name = 'default';}
    // kein Submitbutton
    if (!$submit)
    {
      if ($buttons[$name]['Image']) {
        $html .= xtc_image('templates/'.CURRENT_TEMPLATE.'/buttons/' . $_SESSION['language'] . '/'. $buttons[$name]['Image'], $buttons[$name]['Text'], '', '', $parameters);
      } else {
        $html .= '<span class="';
        if ($buttons[$name]['Class']) {
          $html .= $buttons[$name]['Class'];
        }
        if (xtc_not_null($parameters)) {
          $html .= ' '.$parameters.'">';
        } else {
          $html .= '">';
        }
      if  ($buttons[$name]['Text'] != '') $leer = '&nbsp;&nbsp;';
        if  ($buttons[$name]['glyphiconposition'] == 'left') {
          $html .= '<span class="glyphicon '.$buttons[$name]['glyphicon'].'"></span>'.$leer.$buttons[$name]['Text'];
        }
        elseif ($buttons[$name]['glyphiconposition'] == 'right') {
          $html .= $buttons[$name]['Text'].$leer.'<span class="glyphicon '.$buttons[$name]['glyphicon'].'"></span>';
        }
        else {
          $html .= $buttons[$name]['Text'];
        }
        $html .= '</span>';
      } 
    }

    // wenn Submitbutton
    if ($submit) 
    {
      $html .= '<button class="';
      if ($buttons[$name]['Class']) {
        $html .= $buttons[$name]['Class'];
		}
        if (xtc_not_null($parameters)) {
          $html .= ' '.$parameters.'"';
        } else {
          $html .= '"';
        }
      if ($submit <> true) {
        $html .= ' name="'.$submit.'"';
      }
      if ($submit == true || $submit == "submit"){
        $html .= ' type="submit"';
      }
      $html .= ' title="'.$title.'">';
      if  ($buttons[$name]['Text'] != '') $leer = '&nbsp;&nbsp;';
      if  ($buttons[$name]['glyphiconposition'] == 'left') {
        $html .= '<span class="glyphicon '.$buttons[$name]['glyphicon'].'"></span>'.$leer.$buttons[$name]['Text'];
      }
      elseif ($buttons[$name]['glyphiconposition'] == 'right') {
        $html .= $buttons[$name]['Text'].$leer.'<span class="glyphicon '.$buttons[$name]['glyphicon'].'"></span>';
      }
      else {
        $html .= $buttons[$name]['Text'];
      }
      $html .= '</button>';
    }
    return $html;

  }
  function getBuyNow($id, $name) {
    global $PHP_SELF;
    return '<a href="'.xtc_href_link(basename($PHP_SELF), 'action=buy_now&BUYproducts_id='.$id.'&'.xtc_get_all_get_params(array ('action')), 'NONSSL').'">'.get_bootstrap_button('button_buy_now.gif', TEXT_BUY.$name.TEXT_NOW).'</a>';
  }

?>
