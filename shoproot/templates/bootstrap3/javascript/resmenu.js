function resMenu(){
	function prepMenu(){
		var a=".responsive-nav ", b="ul:not(:has(ul))";
		$(a+" ul").each(function() {
			$(a + b).each(function(){
				var c = $( this ).attr('class'), d = $(this).parent().closest("ul").attr("class"),e = $(this).attr("title");
				$( this ).addClass("xx"),
				$(this).attr("id", d);
				if (typeof e !== typeof undefined && e !== false) {
				}else{
					var f = $(this).prev("a").clone().children().remove().end().text().trim();
					$(this).attr("title", f);
				}
				$( this ).parent('li').append('<span class="next" data-target="menu-'+c+'"><span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span></span>');
			});
    		$(a+" ul").not('.xx').find(b).appendTo("nav.responsive-nav");
		});
		$(a+" ul").each(function() {
			$( this ).removeClass('xx');
			var i = $( this ).attr('id'), g = $( this ).attr('class'), t = $( this ).attr('title');
			if (typeof i == "undefined") {$(this).wrap('<div class="menu root-menu active" id="menu-'+g+'" data-name="<?php echo $heading_responsivemenu ?>"></div>');
			}else{
				$(this).wrap('<div class="menu" id="menu-'+g+'" data-parent="menu-'+i+'" data-name="'+t+'"></div>');
			}
		});
	}

	function set_sel(){
	var w = $("#" + $(".responsive-nav li.Selected").closest("div").attr("id"));
		if (w.data(B)){
    		$(C).text(w.data(F)),
    		$(D).attr(E, w.data(B));
			set_act(w);
		}
	}

	function set_act(x){
    	$(G+A).removeClass(A),
    	$(x).addClass(A);
    	if (!$(".root-menu").hasClass(A)) {
        	$(D).addClass(A);
    	} else {
        	$(D).removeClass(A);
    	}
	}

	var A = "active"
		, B = "parent"
		, C = "#currentMenu"
		, D = "#back"
		, E = "data-target"
		, F = "name"
		, G = ".menu.";

	prepMenu();
	set_sel();

	$(".next").click(function () {
    	var y = $("#" + $(this).data("target"));
    	$(C).text(y.data(E)),
    	$(D).attr(E, $(G+A).attr("id")),
		set_act(y);
	});

	$("#back").click(function () {
    	var z = $("#" + $(G+A).data(B));
    	$(C).text(z.data(E)),
    	$(D).attr(E, $(z).attr("id")),
		set_act(z);
	});
}