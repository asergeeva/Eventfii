/*
 * Author : Grady Laksmono
 * Email : grady@eventfii.com
 * All code (c) 2011 Eventfii Inc. 
 * All rights reserved
 */
var IMAGE_UPLOADER = (function() {
	var _uploader;
	
	return {
		init: function(eid, uploaderId) {
			if ($('#' + uploaderId) !== undefined) {
				_uploader = new qq.FileUploader({
					element: $('#' + uploaderId)[0],
					action: EFGLOBAL.baseUrl + '/event/image/upload',
					params: {eventId: eid}
				});
			}
		}
	}
})();

var USER_IMAGE_UPLOADER = (function() {
	var _uploader;
	
	return {
		init: function() {
			if ($('#user_image').length>0) {
			//alert("here");
				_uploader = new qq.FileUploader({
					element: $('#user_image')[0],
					action: EFGLOBAL.baseUrl + '/user/image/upload',
					onComplete: function(id, fileName, responseJSON){
					//alert($('#user_pic').attr("src"));
					var iurl="upload/user/"+$('#usersid').html()+".jpg?"+Math.random();
					$('#user_pic').attr("src",iurl);
					$('#user_pic').attr("width",150);
					$('#user_pic').attr("height",150);
					$('.qq-upload-list').html("");
					//alert(responseJSON);
					}
					//params: {userId: uid}
				});
			}
		}
	}
})();


var CSV_UPLOADER = (function() {
	var _uploader;
	
	return {
		init: function(eid, uploaderId) {
			if ($('#' + uploaderId) !== undefined) {
				_uploader = new qq.FileUploader({
					element: $('#' + uploaderId)[0],
					action: EFGLOBAL.baseUrl + '/event/csv/upload',
					params: {eventId: eid}
				});
			}
		}
	}
})();