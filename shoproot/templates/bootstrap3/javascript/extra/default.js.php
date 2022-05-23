<?php
  /* --------------------------------------------------------------
   $Id: default.js.php 12435 2019-12-02 09:21:20Z GTB $

   modified eCommerce Shopsoftware
   http://www.modified-shop.org

   Copyright (c) 2009 - 2019 [www.modified-shop.org]
   --------------------------------------------------------------
   Released under the GNU General Public License
   --------------------------------------------------------------*/
?>
<script type="text/javascript">
	$(window).on('load',function() {
		$('.show_rating input').change(function () {
			var $radio = $(this);
			$('.show_rating .selected').removeClass('selected');
			$radio.closest('label').addClass('selected');
		});
	});
	$(document).ready(function(){
		// Copyright 2014-2015 Twitter, Inc.
		// Licensed under MIT (https://github.com/twbs/bootstrap/blob/master/LICENSE)
		if (navigator.userAgent.match(/IEMobile\/10\.0/)) {
			var msViewportStyle = document.createElement('style');
			msViewportStyle.appendChild(
				document.createTextNode(
					'@-ms-viewport{width:auto!important}'
				)
			);
			document.querySelector('head').appendChild(msViewportStyle);
		}
<?php if (MMENU == true):
if (MMENU_MAXLEVEL != false): ?>
		// Maxlevel Mmenü
		$("#menu ul").each(function(){var a=$(this).attr("data-level");if(a><?php echo MMENU_MAXLEVEL; ?>)$(this).remove();});
<?php endif; ?>
		// mmenu
		load_mmenu();
		$('nav#menu').mmenu({navbar:{title:'<?php echo $heading_mmenu ?>', titleClass:'<?php echo TOP1_NAVBAR ?>'}});
<?php endif; ?>
<?php if (RESPONSIVEMENU == true):
if (RESPONSIVEMENU_MAXLEVEL != false): ?>
		// Maxlevel Responsivmenü
		$(".responsive-nav ul").each(function(){var a=$(this).attr("data-level");if(a><?php echo RESPONSIVEMENU_MAXLEVEL; ?>)$(this).remove();});
<?php endif; ?>
		// Responsivmenü
		resMenu();
<?php endif; ?>
		// Karussell - Slider auf Startseite
		$('#myCarousel').carousel({interval:4000});
		// prüfen, ob Link in Navbar ansonsten ausblenden
		if(!$(".canvasmenu a").length){$("#content_navbar2").css("display","none");}
		// Bestsellerslider (autoslide -> interval:4000; nur per Klick -> interval:false)
		$('#bsCarousel').carousel({interval:4000});
		// Go to top
		$(window).scroll(function() {if ($(this).scrollTop()) {$('.go2top').fadeIn();} else {$('.go2top').fadeOut();}});$(".go2top").click(function() {$("html, body").animate({scrollTop: 0}, 1000);});
		// Farbe bei Filter wechseln
		$(".filter_bar select option:selected").each(function(){if($(this).val() != "") $(this).parent().addClass('label label-default');});
		// Modalbox
		function loadGallery(){
			var current_image =0,selector=0,total = $('.pd_more_images a.cbimages').last().attr('data-image-id');
			if (total > 1){
				$('#show-next-image, #show-previous-image').click(function(){
					if($(this).attr('id') == 'show-previous-image'){
						current_image--;
						if (current_image < 1)current_image=total;
					} else {
						current_image++;
						if (current_image > total)current_image=1;
					}
		            selector = $('[data-image-id="' + current_image + '"]');
					updateModal(selector,'image');
				});
			}
        	function updateModal(selector,type) {
				var $sel = selector;
				var curtext = "<?php echo str_replace(' ', '&nbsp;', TEXT_COLORBOX_CURRENT); ?>";
				if (type == 'image'){
	            	current_image = $sel.attr('data-image-id');
					var text = curtext.replace('{current}', current_image).replace('{total}', total);
	            	$('.modal-title').text($sel.attr('data-title'));
	            	$('.modal-body').empty().append('<img class="img-responsive center-block" src="'+$sel.attr('data-image')+'">');
					if (total > 1){
		            	$('#show-next-image, #show-previous-image, .modal-footer .counter').show();
	    	        	$('.modal-footer .counter').empty().append(text);
					} else {
	            	$('#show-next-image, #show-previous-image, .modal-footer .counter').hide();
					}
				} else {
	                var windowheight = $(window).height()-225;
	            	$('#show-next-image, #show-previous-image, .modal-footer .counter').hide();
	            	$('.modal-title').text($sel.attr('title'));
	            	$('.modal-body').html('<iframe id="frame" src="'+$sel.data('src')+'" width="100%" height="'+windowheight+'" frameborder="0"></iframe>');
					if (type == 'layer'){
						$('#modal').modal('show');
					}
				}
	        }
	        $('a.cbimages').on('click',function(){
	            updateModal($(this),'image');
	        });
	        $('a.iframe').on('click',function(){
	            updateModal($(this),'iframe');
	        });
			$("#print_order_layer").on('submit', function(event) {
				$(this).attr('data-src',$(this).attr("action") + '&' + $(this).serialize());
	            updateModal($("#print_order_layer"),'layer');
				return false;
			});
	    }
		$("#modal").on('hidden.bs.modal', function () {
            $('.modal-title').empty();
            $('.modal-body').empty();
            $('.modal-footer .counter').empty();
		});
		$('a.iframe').each(function() {
			$(this).attr("data-src", $(this).attr("href"));
			$(this).attr("data-toggle", "modal");
			$(this).attr("data-target", "#modal");
			$(this).attr("href", "#");
		});
    	loadGallery();
		// aktiviere Tabs
		$('#horizontalTab .tab-pane').removeClass('active');
		$('#bs_tabs a:first').tab('show');
		// Accordion
		$('#accordion .panel-collapse').removeClass('in');
		$('#accordion .more-less').toggleClass('glyphicon-triangle-top glyphicon-triangle-bottom');
		$('#accordion .panel-collapse:first').collapse('show');
		function toggleIcon(e) {
			$(e.target)
	        .prev('.panel-heading')
	        .find(".more-less")
	        .toggleClass('glyphicon-triangle-bottom glyphicon-triangle-top');
		}
		$('.panel-group').on('hidden.bs.collapse', toggleIcon);
		$('.panel-group').on('shown.bs.collapse', toggleIcon);
		$('.panel-group').has('[id^="acc"]').on('shown.bs.collapse', function(e) {
			$(this).find('span[data-target="#'+e.target.id+'"] input').prop('checked', true).triggerHandler('click');
		});
		// Tooltip
		$(function () {
			$('[data-toggle="tooltip"]').tooltip();
		});
<?php if (SEARCH_AC_STATUS == 'true') { ?>
		// autocomplete
		var option = $('#suggestions');
		$(document).click(function(e){
			var target = $(e.target);
			if(!(target.is(option) || option.find(target).length)){
				ac_closing();
			}
		});
<?php } ?>
	});
<?php if (strpos($PHP_SELF, 'checkout') !== false) { ?>
		$('#button_checkout_confirmation').on('click',function() {
			$(this).hide();
		});
<?php } ?>
<?php if (SEARCH_AC_STATUS == 'true') { ?>
		var ac_pageSize = 6;
		var ac_page = 1;
		var ac_result = 0;
		var ac_show_page = '<?php echo AC_SHOW_PAGE; ?>';
		var ac_show_page_of = '<?php echo AC_SHOW_PAGE_OF; ?>';

		function ac_showPage(ac_page) {
			ac_result = Math.ceil($("#autocomplete_main").children().length/ac_pageSize);
			$('.autocomplete_content').hide();
			$('.autocomplete_content').each(function(n) {
				if (n >= (ac_pageSize * (ac_page - 1)) && n < (ac_pageSize * ac_page)) {
					$(this).show();
				}
			});
			$('#autocomplete_next').css('visibility', 'hidden');
			$('#autocomplete_prev').css('visibility', 'hidden');
			if (ac_page > 1) {
				$('#autocomplete_prev').css('visibility', 'visible');
			}
			if (ac_page < ac_result && ac_result > 1) {
				$('#autocomplete_next').css('visibility', 'visible');
			}
			$('#autocomplete_count').html(ac_show_page+ac_page+ac_show_page_of+ac_result);
		}
		function ac_prevPage() {
			if (ac_page == 1) {
				ac_page = ac_result;
			} else {
				ac_page--;
			}
			if (ac_page < 1) {
				ac_page = 1;
			}
			ac_showPage(ac_page);
		}
		function ac_nextPage() {
			if (ac_page == ac_result) {
				ac_page = 1;
			} else {
				ac_page++;
			}
			ac_showPage(ac_page);
		}
		function ac_lookup(inputString) {
			if(inputString.length == 0) {
				$('#suggestions').hide();
			} else {
				var post_params = $('#quick_find').serialize();
				post_params = post_params.replace("keywords=", "queryString=");
				$.post("<?php echo xtc_href_link('api/autocomplete/autocomplete.php', '', $request_type); ?>", post_params, function(data) {
					if(data.length > 0) {
						$('#suggestions').slideDown();
						$('#autoSuggestionsList').html(data);
						ac_showPage(1);
						$('#autocomplete_prev').click(ac_prevPage);
						$('#autocomplete_next').click(ac_nextPage);
					}
				});
			}
		}
		$('#cat_search').on('change', function () {
			$('#inputString').val('');
		});
<?php } ?>
<?php if (SEARCH_AC_STATUS == 'true' || (basename($PHP_SELF) != FILENAME_SHOPPING_CART && strpos($PHP_SELF, 'checkout') === false)) { ?>
		function ac_closing() {
			setTimeout("$('#suggestions').slideUp();", 100);
			ac_page = 1;
		}
<?php } ?>
  function alert(message, title) {
    title = title || "<?php echo TEXT_LINK_TITLE_INFORMATION; ?>";
    $.alertable.alert('<span id="alertable-title"></span><span id="alertable-content"></span>', {
      html: true
    });
    $('#alertable-content').html(message);
    $('#alertable-title').html(title);
  }
<?php if (basename($PHP_SELF) != FILENAME_SHOPPING_CART && strpos($PHP_SELF, 'checkout') !== false) { ?>
		$(function() {
			$('#toggle_cart').click(function() {
				$('.toggle_cart').slideToggle('slow');
				$('.toggle_wishlist').slideUp('slow');
				ac_closing();
				return false;
			});
			$('html').on('click', function(e) {
				if (!$(e.target).closest('.toggle_cart').length > 0 ) {
					$('.toggle_cart').slideUp('slow');
				}
			});
<?php if (DISPLAY_CART == 'false' && isset($_SESSION['new_products_id_in_cart'])) {
				unset($_SESSION['new_products_id_in_cart']); ?>
				$('.toggle_cart').slideToggle('slow');
				timer = setTimeout(function(){$('.toggle_cart').slideUp('slow');}, 3000);
				$('.toggle_cart').mouseover(function() {clearTimeout(timer);});
<?php } ?>
		});
		$(function() {
			$('#toggle_wishlist').click(function() {
				$('.toggle_wishlist').slideToggle('slow');
				$('.toggle_cart').slideUp('slow');
				ac_closing();
				return false;
			});
			$('html').on('click', function(e) {
				if (!$(e.target).closest('.toggle_wishlist').length > 0 ) {
					$('.toggle_wishlist').slideUp('slow');
				}
			});
<?php if (DISPLAY_CART == 'false' && isset($_SESSION['new_products_id_in_wishlist'])) {
		unset($_SESSION['new_products_id_in_wishlist']);
?>
			$('.toggle_wishlist').slideToggle('slow');
			timer = setTimeout(function(){$('.toggle_wishlist').slideUp('slow');}, 3000);
			$('.toggle_wishlist').mouseover(function() {clearTimeout(timer);});
<?php } ?>
		});
<?php } else {
		unset($_SESSION['new_products_id_in_cart']);
		unset($_SESSION['new_products_id_in_wishlist']);
} ?>
<?php if (TOUCH_USE == true) { ?>
		$(function () {
			$('#navbar').doubleTapToGo();
		});
<?php } ?>
<?php if (ADVANCED_JS_VALIDATION == true && (strpos($PHP_SELF, FILENAME_CREATE_ACCOUNT) !== false || strpos($PHP_SELF, FILENAME_CREATE_GUEST_ACCOUNT) !== false)) {
		require_once (DIR_FS_EXTERNAL.'password_policy/password_policy.php');
?>
		$(function () {
			var key, hasWarnings;
			var classWarnings = "text-danger";
			var strWarnings = $("#create_account .alert-warning").html();
			var warnings = {
<?php
	if (defined('ENTRY_GENDER_ERROR')) echo '				"' . html_entity_decode(ENTRY_GENDER_ERROR) . '" : "gender",' . PHP_EOL;
	if (defined('ENTRY_FIRST_NAME_ERROR')) echo '				"' . html_entity_decode(ENTRY_FIRST_NAME_ERROR) . '" : "firstname",' . PHP_EOL;
	if (defined('ENTRY_LAST_NAME_ERROR')) echo '				"' . html_entity_decode(ENTRY_LAST_NAME_ERROR) . '" : "lastname",' . PHP_EOL;
	if (defined('ENTRY_DATE_OF_BIRTH_ERROR')) echo '				"' . html_entity_decode(ENTRY_DATE_OF_BIRTH_ERROR) . '" : "dob",' . PHP_EOL;
	if (defined('ENTRY_EMAIL_ADDRESS_ERROR')) echo '				"' . html_entity_decode(ENTRY_EMAIL_ADDRESS_ERROR) . '" : "email_address",' . PHP_EOL;
	if (defined('ENTRY_EMAIL_ADDRESS_CHECK_ERROR')) echo '				"' . html_entity_decode(ENTRY_EMAIL_ADDRESS_CHECK_ERROR) . '" : "email_address",' . PHP_EOL;
	if (defined('ENTRY_EMAIL_ADDRESS_ERROR_EXISTS')) echo '				"' . html_entity_decode(ENTRY_EMAIL_ADDRESS_ERROR_EXISTS) . '" : "email_address",' . PHP_EOL;
	if (defined('ENTRY_EMAIL_ERROR_NOT_MATCHING')) echo '				"' . html_entity_decode(ENTRY_EMAIL_ERROR_NOT_MATCHING) . '" : "confirm_email_address",' . PHP_EOL;
	if (defined('ENTRY_STREET_ADDRESS_ERROR')) echo '				"' . html_entity_decode(ENTRY_STREET_ADDRESS_ERROR) . '" : "street_address",' . PHP_EOL;
	if (defined('ENTRY_POST_CODE_ERROR')) echo '				"' . html_entity_decode(ENTRY_POST_CODE_ERROR) . '" : "postcode",' . PHP_EOL;
	if (defined('ENTRY_CITY_ERROR')) echo '				"' . html_entity_decode(ENTRY_CITY_ERROR) . '" : "city",' . PHP_EOL;
	if (defined('ENTRY_STATE_ERROR')) echo '				"' . html_entity_decode(ENTRY_STATE_ERROR) . '" : "state",' . PHP_EOL;
	if (defined('ENTRY_STATE_ERROR_SELECT')) echo '				"' . html_entity_decode(ENTRY_STATE_ERROR_SELECT) . '" : "state",' . PHP_EOL;
	if (defined('ENTRY_COUNTRY_ERROR')) echo '				"' . html_entity_decode(ENTRY_COUNTRY_ERROR) . '" : "country",' . PHP_EOL;
	if (defined('ENTRY_TELEPHONE_NUMBER_ERROR')) echo '				"' . html_entity_decode(ENTRY_TELEPHONE_NUMBER_ERROR) . '" : "telephone",' . PHP_EOL;
	if (defined('ENTRY_PASSWORD_ERROR')) echo '				"' . sprintf(html_entity_decode(ENTRY_PASSWORD_ERROR), ENTRY_PASSWORD_MIN_LENGTH) . '" : "password",' . PHP_EOL;
	if (defined('ENTRY_PASSWORD_ERROR_MIN_LOWER')) echo '				"' . sprintf(html_entity_decode(ENTRY_PASSWORD_ERROR_MIN_LOWER), POLICY_MIN_LOWER_CHARS) . '" : "password",' . PHP_EOL;
	if (defined('ENTRY_PASSWORD_ERROR_MIN_UPPER')) echo '				"' . sprintf(html_entity_decode(ENTRY_PASSWORD_ERROR_MIN_UPPER), POLICY_MIN_UPPER_CHARS) . '" : "password",' . PHP_EOL;
	if (defined('ENTRY_PASSWORD_ERROR_MIN_NUM')) echo '				"' . sprintf(html_entity_decode(ENTRY_PASSWORD_ERROR_MIN_NUM), POLICY_MIN_NUMERIC_CHARS) . '" : "password",' . PHP_EOL;
	if (defined('ENTRY_PASSWORD_ERROR_MIN_CHAR')) echo '				"' . sprintf(html_entity_decode(ENTRY_PASSWORD_ERROR_MIN_CHAR), POLICY_MIN_SPECIAL_CHARS) . '" : "password",' . PHP_EOL;
	if (defined('ENTRY_PASSWORD_ERROR_NOT_MATCHING')) echo '				"' . html_entity_decode(ENTRY_PASSWORD_ERROR_NOT_MATCHING) . '" : "confirmation",' . PHP_EOL;
	if (defined('ENTRY_PRIVACY_ERROR')) echo '				"' . html_entity_decode(ENTRY_PRIVACY_ERROR) . '" : "privacy",' . PHP_EOL;
	if (defined('ERROR_VVCODE_POPUP')) echo '				"' . html_entity_decode(ERROR_VVCODE_POPUP) . '" : "vvcode",' . PHP_EOL;
?>
			};
<?php
    echo '        $("#create_account [name=\'street_address\']").blur(function() {if(!/[1-9]/.test(this.value) && this.value.length >= ' . ENTRY_STREET_ADDRESS_MIN_LENGTH . '){$(\'#number-error\'). slideDown(200)}else{$(\'#number-error\').slideUp(200)}});' . PHP_EOL;
    if ($_SESSION['language_code'] == 'de') {
        echo '        $("#create_account [name=\'street_address\']").parent().append(\'<div id="number-error" style="width: 95%; float: left; display: none;"><span class="\' + classWarnings + \'">Hausnummer fehlt!</span> Ignorieren Sie diese Meldung wenn Sie keine haben.</div>\');' . PHP_EOL;
    } else if ($_SESSION['language_code'] == 'en') {
        echo '        $("#create_account [name=\'street_address\']").parent().append(\'<div id="number-error" style="width: 95%; float: left; display: none;"><span class="\' + classWarnings + \'">House number is missing!</span> Ignore this message if you have no house number.</div>\');' . PHP_EOL;
    }  else {
        echo '        $("#create_account [name=\'street_address\']").parent().append(\'<div id="number-error" style="width: 95%; float: left; display: none;"><span class="\' + classWarnings + \'">House number is missing!</span> Ignore this message if you have no house number.</div>\')' . PHP_EOL;
    }
?>
        for (key in warnings) {
            if (typeof strWarnings != "undefined" && strWarnings.indexOf(key) != -1) {
                $("#create_account [name='" + warnings[key] + "']").parent().append('<div class="create-account-warning-text" style="width: 95%; float: left;">' + key + '</div>').addClass(classWarnings);
                if (hasWarnings != 1) {
                    $("#create_account .alert-warning").css("display", "none");
                    $("#create_account [name='password'], #create_account [name='confirmation'], #create_account [name='vvcode']").parent().addClass(classWarnings);
                }
                hasWarnings = 1;
            }
        }
    });
<?php } ?>
<?php
// KK-Megamenü
if(!empty($KK_MEGAS)) {
?>
(function($){
	//define the defaults
	$.fn.KKMega = function(options){
		//set default options
		var defaults = {
			rows: 3,
		};

		//call in the default otions
		var options = $.extend(defaults, options);
		var $KKMegaObj = this;

		return $KKMegaObj.each(function(options){
			megaSetup();

			function megaSetup(){

				$($KKMegaObj).each(function(){
					if (this.id != 'main') {
						$(this).addClass('kk-mega');
					}
						$('li',this).addClass('kk-mega');
						$('ul',this).addClass('kk-mega');
						var percent = Math.floor(100/defaults.rows);
	                    $('li.kk-mega.level2',this).css( "width", percent +"%" );
						$('ul.level2 ul',this).removeClass('dropdown-menu');
						$('span.caret-right',this).remove();
				});

			}
		});
	};

})(jQuery);

$(document).ready(function(){

<?php foreach ($KK_MEGAS as $megas) {
		$mega = explode("-", $megas);
?>

	$('#<?php echo $mega[0]; ?>').KKMega({
		rows: '<?php echo $mega[1]; ?>',
	});

<?php } ?>
});

<?php
}
// Ende KK-Megamenü
?>
</script>