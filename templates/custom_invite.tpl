{include file="new_head.tpl"}
<body>

{include file="new_header.tpl"}
<div id="container">
	<nav class="steps step-{$step}" id="create-nav">
		<ol>
			<li>Basic info</li>
			<li>Preferences</li>
			<li>Add guests</li>
			<li>Success!</li>
		</ol>
	</nav>
	<div class="">
		{include file="block_custominvites.tpl"}
	</div>
</div>
{include file="footer.tpl"}
{include file="js_global.tpl"}
<link href="{$JS_PATH}/uploadify/uploadify.css" type="text/css" rel="stylesheet" />
<script src="{$JS_PATH}/uploadify/swfobject.js"></script> 
<script src="{$JS_PATH}/uploadify/jquery.uploadify.v2.1.4.js"></script>
<script src="{$JS_PATH}/jquery.tinyscrollbar.min.js"></script>
<script src="{$JS_PATH}/bas64.js"></script>
<script type="text/javascript">
$(document).ready(function(){
			
	$('#scrollbar1').tinyscrollbar();	
});
</script>
<script language="javascript">
var t = '';
var queSize     = 1;
$(document).ready(function() {
  $('#file').uploadify({
    'uploader'  : '{$JS_PATH}/uploadify/uploadify.swf',
    'script'    : '{$CURHOST}/event/create/invite/upload',
    'cancelImg' : '{$JS_PATH}/uploadify/cancel.png',
    'folder'    : '{$CURHOST}/upload/events/',
    'multi'     : false,
	'auto'		: false,
	'scriptData': {$eventid},
	'buttonImg' : '{$CURHOST}/images/btn_chooseFle_2.png',
	'queueSizeLimit' : queSize,
	'fileExt'   : '*.jpg;*.jpeg;*.gif;*.JPG;*.JPEG;*.GIF',
	'sizeLimit' : '8388608',
	'onComplete'  : function(event, ID, fileObj, response, data) {
		$('#loader').hide();
		queSize--;
		$("#image_view").html('<a href="javascript:void(0);" onclick="return launchEditor(\'image1\', \'{$CURHOST}/upload/events/'+response+'\');"><img id="image1" src="{$CURHOST}/upload/events/'+response+'" alt="photo to edit" /></a>');
		return launchEditor("image1", "{$CURHOST}/upload/events/"+response);
    },
	'onAllComplete' : function(event,data) {
		$("#after_success").html('<strong>Satisfied with your invitation? </strong><a class="btn btn-small" href="javascript:void(0);" onclick="save_image();"><span>&nbsp; Go to step 4 &nbsp;</span></a>');
    },
	'onSelect': function(event,ID,fileObj) {
		$('#loader').show();
    },
	'onError': function(event,ID,fileObj, d)
	{
		alert("Error: "+d.type+"      Info: "+d.info);
	}
	
	
  });
});
function changeImage(id)
{
	var src = $("#"+id).attr('src');
	var splitted = src.split("/");
	src = splitted[(splitted.length-1)];
	$("#image_view").html('<a href="javascript:void(0);" onclick="return launchEditor(\'image1\', \'{$CURHOST}/upload/stock/'+src+'\');"><img id="image1" src="{$CURHOST}/upload/stock/'+src+'" alt="photo to edit" /></a>');
	$("#after_success").html('<strong>Satisfied with your invitation? </strong><a class="btn btn-small" href="javascript:void(0);" onclick="save_image();"><span>&nbsp; Go to step 4 &nbsp;</span></a>');
	return launchEditor("image1", '{$CURHOST}/upload/stock/'+src);
}
function startUpload()
{
	$('#file').uploadifyUpload();	
	//$('#file').uploadifySettings('scriptData', '');
}
function bindScrollBar()
{		
	$('#scrollbar1').tinyscrollbar();
	clearInterval(t);	
}
</script>
<div id="injection_site"></div>
</body>
</html>
<div class="popup_box_main" id="loader" style="display:none;">
    <div class="popup_overlay"></div>
    <div class="popup_box" align="center">
        <div class="popup_box_inr">
    	<div style="width:100%; text-align:center;">Please wait as we load your photo.</div>
        <div style="text-align:center;"><img src="{$CURHOST}/images/loader.gif" /></div>
        </div>
    </div>
</div>
<!-- Load Feather code -->
<script type="text/javascript" src="http://feather.aviary.com/js/feather.js"></script>
<!-- Instantiate Feather -->
<script type="text/javascript">
var featherEditor = new(Aviary.Feather)({
	apiKey: '597cb4bb3',
	apiVersion: 2,
	tools: 'all',
	appendTo: '',
	onSave: function(imageID, newURL) {
		featherEditor.close();
		$("#image_view").html('<a href="javascript:void(0);" onclick="return launchEditor(\''+imageID+'\', \''+newURL+'\');"><img id="'+imageID+'" src="'+newURL+'" alt="photo to edit" /></a>');
	}
});

function launchEditor(id, src) {
	featherEditor.launch({
		image: id,
		url: src
	});
	return false;
}
function save_image()
{
	var data = $.base64.encode($('#image1').attr('src'));
	$.ajax({
		type: 'POST',
		url: '{$CURHOST}/event/create/invite/upload/save',
		data: 'imageURL='+data,
		cache: false,
		success: function()
		{
			window.location = '{$CURHOST}/event/create/guests';
		}	
	});	
}
function showStockImages(stock_id)
{
	var data = "id="+stock_id;
	$.ajax({
		type: 'POST',
		url: '{$CURHOST}/event/create/invite/showstockimages',
		data: data,
		cache: false,
		success: function(response)
		{
			$("#show_images_div").html(response);
			$("ul.left li span").css("font-weight", "normal");
			$("#arrow").remove();
			$("#stock_name_"+stock_id).css("font-weight", 'bold');
			$("#stock_name_"+stock_id).append('<span id="arrow">&nbsp;<img src="{$CURHOST}/images/arrow.jpg" /></span>');
			t = setInterval("bindScrollBar()", 1000);
		}
	});
}
$(document).ready(function(){
	showStockImages(1);
});
</script>