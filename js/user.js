var USER_INFO = ( function() {
	return {
		init: function() {
			$("#user-desc .buttons a").live('click', this.editDesc);
		}
	},

	editDesc: function() {
		
	}
})();

$(document).ready(function() {
	USER_INFO.init();

	$('.edit').editable('user/status/update', {
		 type:'textarea',
         indicator : 'Saving...',
         tooltip   : 'Click to edit...',
		 style: 'border-style: inset; border-width: 2px',
		 onblur: 'submit',
		 callback : function(value, settings) {
			$('#editBtn').show();
			$('#div_2').html(value);
			$('#div_2').css('left','25px');
		 }
     });
	
	$('#uploadPic').live('click',function(){
		//alert("here");
		$("input[name=file]").click();
	});
});
