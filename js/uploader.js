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