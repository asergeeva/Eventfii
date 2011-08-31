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
					action: EFGLOBAL.baseUrl + '/event/csv/upload'
				});
			}
		}
	}
})();

var CONTACTS_UPLOADER = (function() {
	var _uploader;
	
	return {
		init: function() {
			if ($('#csv_upload').length > 0) {
				_uploader = new qq.FileUploader({
					element: $('#csv_upload')[0],
					action: EFGLOBAL.baseUrl + '/user/csv/upload'
				});
			}
		}
	}
})();