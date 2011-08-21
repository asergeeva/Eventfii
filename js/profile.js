var PROFILE = (function() {
	return {
		init: function() {
			$('#follow_host').live('click', function() {
				var userId = $(this).attr('href').substring(1);
				$.post(EFGLOBAL.baseUrl + '/user/follow', {
					fid: userId
				});
			}, this.isFollow);
		}, 
		
		isFollow: function(response) {
			
		}
	}
})();

$(document).ready(function() {
	PROFILE.init();
});