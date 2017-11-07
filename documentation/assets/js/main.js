'use strict';
var Main = function() {
	var $html = $('html'), $win = $(window), wrap = $('.app-aside'), MEDIAQUERY = {};

	MEDIAQUERY = {
		desktopXL : 1200,
		desktop : 992,
		tablet : 768,
		mobile : 480
	};
	// function for automatic Copyright Year
	var currentYearHandler = function() {
		$(".current-year").text((new Date).getFullYear());
	};

	// function to handle Scroll Top button
	var scrollTopHandler = function() {
		var scroll_top = $win.scrollTop();
		var scroll_top_button = $('#scroll-top');
		if (scroll_top > 300) {
			scroll_top_button.fadeIn();
		} else {
			scroll_top_button.fadeOut();
		};
		scroll_top_button.off("click").on("click", function(e) {
			$("html, body").animate({
				scrollTop : 0
			}, "slow");
			e.preventDefault();
		});
	};

	// Window Scroll Function
	var windowScrollHandler = function() {
		$win.scroll(function() {
			scrollTopHandler();
		});
	};

	//function to get viewport/window size (width and height)
	var viewport = function() {
		var e = window, a = 'inner';
		if (!('innerWidth' in window)) {
			a = 'client';
			e = document.documentElement || document.body;
		}
		return {
			width : e[a + 'Width'],
			height : e[a + 'Height']
		};
	};

	function isSmallDevice() {
		return viewport().width < MEDIAQUERY.desktop;
	}

	function isExtraSmallDevice() {
		return viewport().width < MEDIAQUERY.tablet;
	}

	function isMobile() {
		if (/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent)) {
			return true;
		} else {
			return false;
		};
	}

	return {
		init : function() {
			windowScrollHandler();
			currentYearHandler();
		}
	};
}();
