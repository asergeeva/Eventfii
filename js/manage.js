var MANAGE_EVENT = (function() {
	return {
		init: function() {
			$('.manage_top_nav').live('click', function() {
				$('.section-current').removeAttr('class');
				$(this).parent().attr('class', 'section-current');
			});
		}
	}
})();