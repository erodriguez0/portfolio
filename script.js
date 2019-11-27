$(document).ready(function() {
	
	$(function(){ 
	     var navMain = $(".navbar-collapse"); // avoid dependency on #id
	     // "a:not([data-toggle])" - to avoid issues caused
	     // when you have dropdown inside navbar
	     navMain.on("click", "a:not([data-toggle])", null, function () {
	         navMain.collapse('hide');
	     });
	 });
	
	if($(window).width() < 992) {
		$(function() {
		    // Desired offset, in pixels
		    var offset = 55;
		    // Desired time to scroll, in milliseconds
		    var scrollTime = 500;

		    $('a[href^="#"]').click(function() {
		        // Need both `html` and `body` for full browser support
		        $("html, body").animate({
		            scrollTop: $( $(this).attr("href") ).offset().top - offset 
		        }, scrollTime);

		        // Prevent the jump/flash
		        return false;
		    });
		});
	}
	
	// $('body').scrollspy({
	// 	target: '#navbar',
	// 	offset: 56
	// });

	// function navbarFade() {
	// 	var navbar = $("#navbar-wrap");

	// 	if($(this).scrollTop() >= 56) {
	// 		navbar.removeClass("bg-dark").addClass("transparent-nav");
	// 		console.log("working");
	// 	} else if($(this).scrollTop() < 56) {
	// 		navbar.addClass("bg-dark").removeClass("transparent-nav");
	// 	}

	// }

	// $(window).scroll(function () {
 //        navbarFade();
 //    });

});