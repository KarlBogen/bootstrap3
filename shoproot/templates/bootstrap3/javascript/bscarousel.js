	$(function(){
		var a = "#bsCarousel", b = " .cols", c = "<div class=\"item\"><div class=\"row\"></div></div>";
		$(a + " .carousel-controls").removeClass("hidden");
		$(a+b).removeClass("col-xs-3 col-sm-2")
		function makeWrap(n,m){
			var divs = $(a+b);
			for(var i = 0; i < divs.length; i+=n) {
				divs.slice(i, i+n).wrapAll(c);
			}
			$(a+" .item").first().addClass("active");
			$(b).addClass("col-xs-"+m);
		}
		function checkWindow (){
			var w = $(window).width();
				if (w < 440){
					// 2 Bilder
					makeWrap(2,6);
				}
				else if (w < 600){
				// 3 Bilder
					makeWrap(3,4);
				}
				else if (w < 1200){
				// 4 Bilder
					makeWrap(4,3);
				}
				else {
					makeWrap(6,2);
				}
		}
		checkWindow();
	});
