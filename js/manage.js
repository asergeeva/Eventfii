var MANAGE_EVENT = ( function() {
	return {
		init: function() {
			$('#cp-nav ul li').live('click', function() {
				$('.section-current').removeAttr('class');
				$(this).parent().attr('class', 'section-current');
			});
			
		  $("img[rel]").overlay();
			$("a[rel]").overlay();
		}
	}
})();

$(document).ready(function() {
	MANAGE_EVENT.init();
});