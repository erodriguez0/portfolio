$(document).ready(function() {
	
	$('body').scrollspy({
		target: '#navbar',
		offset: 56
	});

	function navbarFade() {
		var navbar = $("#navbar-wrap");

		if($(this).scrollTop() >= 56) {
			navbar.removeClass("bg-dark").addClass("transparent-nav");
			console.log("working");
		} else if($(this).scrollTop() < 56) {
			navbar.addClass("bg-dark").removeClass("transparent-nav");
		}

	}

	// $(window).scroll(function () {
 //        navbarFade();
 //    });

});