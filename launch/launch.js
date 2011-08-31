$(document).ready(function() {
	$('#email_submit_btn').live('click', function() {
		$('#inputbox').fadeOut('slow');
		$.post('launch.php', {
			email: $('#email').val(),
			dislike: $('#dislike_text').val()
		}, function(responsePage) {
			$('#inputbox').html(responsePage).ready(function() {
				$('#inputbox').fadeIn('slow');
			});
		});
	});
});