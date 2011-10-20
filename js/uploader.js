/*
 * Author : Grady Laksmono
 * Email : grady@truersvp.com
 * All code (c) 2011 trueRSVP Inc. 
 * All rights reserved
 */
var USER_IMAGE_UPLOADER = (function() {
	var _uploader;
	
	return {
		init: function() {
			if ($('#user_image').length > 0) {
				_uploader = new qq.FileUploader({
					element: $('#user_image')[0],
					action: EFGLOBAL.baseUrl + '/user/image/upload',
					onComplete: function(id, fileName, responseJSON){
						$('#user_pic').attr("src",responseJSON.file);
						$('.qq-upload-list').html("");
					}
				});
			}
		}
	}
})();


var CSV_UPLOADER = (function() {
	var _uploader;
	
	return {
		init: function() {
			if ($('#csv_upload').length > 0) {
				_uploader = new qq.FileUploader({
					element: $('#csv_upload')[0],
					action: EFGLOBAL.baseUrl + '/event/csv/upload',
					onComplete: function(id, fileName, csvList) {
						if (csvList == false) {
							$('#csv_container').html('No more new guests can be added from this CSV');
							$('#csv_container').css({'text-align': 'center'});
						} else {
							$('#csv_upload').css('display', 'none');
							$('#add_import_contact_list').css({'display': 'block', 'text-align': 'center'});
						
							$('#csv_container').html(csvList);
							$('.contact-email').attr('checked', 'checked');
						
							OPENINVITER.listFilter($("#contacts-header"), $("#contacts-list"));
						}
					}
				});
			}
		}
	}
})();