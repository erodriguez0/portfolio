$(document).ready(function() {
	function scrollToDiv(id) {
		$('html, body').animate({scrollTop: $("#"+id).offset().top});
	}

	$("#projects-btn").click(function() {
		scrollToDiv("projects");
	});
});