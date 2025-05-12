// Ein- und Ausklappen
function toggle_display(frameid) {
	// blendet ein Frame (<div>, <span> etc.) ein oder aus
	var frame = document.getElementById(frameid);
	if (frame.style.display == "block") {
		frame.style.display = "none";
		return false;
	}
	else {
		frame.style.display = "block";
		return true;
	}
}
	
function toggle_arrow(frameid) {
	var element = document.getElementById(frameid);
	element.classList.toggle("arrow-down");
} 

jQuery(document).ready(function($) {
	window.toggle_dropdown = function(clicked_button, scroll=true) {
		var element = $(clicked_button).parent() // jQuery syntax
		if (scroll) scroll = !element.hasClass('active')
		element.toggleClass('active')
		if (scroll) {
			var scroll_offset = $(element).offset().top - $("#header").height();
			$("html, body").animate({scrollTop: scroll_offset}, "slow");
		}
	}
	
	// adjusts GUI depending on the users scroll behaviour
	var old_scroll = 0
	$(window).scroll(function(e) {
		const s_bp = 1280; // px breakpoint for users with a small screen
		/////////////////////////////
		// navbar scroll functions //
		/////////////////////////////
		// collapse sub-menu 
		if (old_scroll < $(this).scrollTop()) { // user is scrolling down
			if (old_scroll + 300 < $(this).scrollTop()) {
				if (screen.width < s_bp) {
					$("#header").hide()
				}
				$("header #hamburg").prop( "checked", false );
				old_scroll = $(this).scrollTop() // reset scroll position
			}
		}
		else { // user is scrolling up
			if (screen.width < s_bp) {
				$("#header").show()
			}
			old_scroll = $(this).scrollTop() // reset scroll position
		}
		// make GUI sticky on scroll down
		var threshold = $(this).scrollTop() > 0 ? true : false;
		// if (threshold) $("#header").addClass("header-up")
		// else $("#header").removeClass("header-up")
		var $cw = $(".control-wrapper"); 
		if ($cw.length > 0) {
			var isPositionFixed = ($cw.css('position') == 'sticky');
			if (threshold && !isPositionFixed) { 
				$cw.addClass("scrolled")
			}
			if (!threshold && isPositionFixed) {
				$cw.removeClass("scrolled")
			}
		}
		
		// scroll up button
		threshold = $(this).scrollTop() > 2000 ? true : false;
		if (threshold) $("#back-to-top").show();
		else $("#back-to-top").hide();
	})
	$(function() {
		$("body").append("<a id='back-to-top'><del></del></a>")
		$("#back-to-top").hide()
		$("#back-to-top").click(function () {
			$('body,html').animate({
				scrollTop: 0
			}, 800);
			return false;
		});
	})
	
	window.pop_up = function(clicked_button) {
		var ele = $(clicked_button).closest(".dd-wrapper")
		ele.find('.dd').toggleClass('hidden')
		ele.toggleClass('active')
	}
	
	// fixes the Bauteil/Pflanzungen Breadcrumb to redirect to the cat overview
	$(function () {
		const sub_types = ["bauteile", "pflanzungen"]
		sub_types.forEach(st => {
			try {
				var $ele = $("nav.breadcrumb a[href$='"+st+"/']")
				$ele.attr("href", $ele.attr("href").replace("/category", ""))
			}
			catch(e) {}
		})
	})
	
	// copies the value of a sibling-input field
	window.copyInput = function(button) {
		$parent = $(button).parent()
		$parent.find("input").show().select()
		document.execCommand("copy")
		$parent.find(".success").show()
	}
	
	// fixes for the auto scroll on frontpage
	$(window).on("unload", function() {
		localStorage.setItem("rla-intra-history", $(location).attr('href'));
	});
	$(function() {
		$("header a").not("#logo").click(function() {
			localStorage.setItem("rla-intra-last-dd", null);
		})
	})
})