{include file="new_head.tpl"}
<body>
{include file="new_header.tpl"}
<div id="container">
	{include file="manage_header.tpl"}
	<section id="main">
    	<header id="header">
            <nav style="float:left;">
                <ul>
                    <li id="manage" {if !isset($smarty.get.type)}class="current"{/if}><a href="{$CURHOST}/event/manage/edit?eventId={$smarty.session.manage_event->eid}"><span>Edit Event Details</span></a></li>
                    <li id="edit" {if isset($smarty.get.type)}class="current"{/if}><a id="update_event_edit" href="{$CURHOST}/event/manage/edit?eventId={$smarty.session.manage_event->eid}&type=invitation"><span>Edit Invitation</span></a></li>
                </ul>
            </nav>
        </header>
        <div style="clear:both;"></div>
        {if !isset($smarty.get.type)}
		<div class="form">
			<section class="block">{if isset($error)}

				<header class="block error">
					<p class="message">Please fix the errors below to update your event.</p>
				</header>{elseif isset($saved)}

				<header class="block notification">
					<p class="message">Event Updated.</p>
				</header>{/if}
				<form method="post" action="{$CURHOST}/event/manage/edit?eventId={$smarty.session.manage_event->eid}">
					{include file="create_form.tpl"}
				</form>
			</section>
		</div>
        {else}
        <div class="form">
			<section class="block" style="padding:0; padding-bottom:15px;">
				<form method="post" action="{$CURHOST}/event/manage/edit?eventId={$smarty.session.manage_event->eid}">
					{include file="block_edit_custom_invite.tpl"}
				</form>
			</section>
		</div>
        {/if}
	</section>
</div>
<img src="{$CURHOST}/upload/events/{$event_image}" id="image1" style="display:none;" />
{include file="footer.tpl"}

{include file="js_global.tpl"}
{include file="js_manage.tpl"}
{include file="js_create.tpl"}
<script src="{$JS_PATH}/md5-min.js" type="text/javascript" charset="utf-8"></script>
<script src="{$JS_PATH}/login.js" type="text/javascript" charset="utf-8"></script>
<script src="{$JS_PATH}/edit.js" type="text/javascript" charset="utf-8"></script>
<link href="{$JS_PATH}/uploadify/uploadify.css" type="text/css" rel="stylesheet" />
<script src="{$JS_PATH}/uploadify/swfobject.js"></script> 
<script src="{$JS_PATH}/uploadify/jquery.uploadify.v2.1.4.js"></script>
<script src="{$JS_PATH}/jquery.tinyscrollbar.min.js"></script>
<script src="{$JS_PATH}/bas64.js"></script>
<script type="text/javascript">
var t = '';
function bindScrollBar()
{		
	$('#scrollbar1').tinyscrollbar();
	clearInterval(t);
}
</script>
<script language="javascript">
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
	'sizeLimit' : '5242880',
	'onComplete'  : function(event, ID, fileObj, response, data) {
		$('#loader').hide();
		queSize--;
		$("#image1").attr('src', '{$CURHOST}/upload/events/'+response);
		//$("#image_view").html('<a href="javascript:void(0);" onclick="return launchEditor(\'image1\', \'{$CURHOST}/upload/events/'+response+'\');"><img id="image1" src="{$CURHOST}/upload/events/'+response+'" alt="photo to edit" /></a>');
		return launchEditor("image1", "{$CURHOST}/upload/events/"+response);
    },
	'onAllComplete' : function(event,data) {
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
	$("#image1").attr('src', '{$CURHOST}/upload/stock/'+src);
	//$("#image_view").html('<a href="javascript:void(0);" onclick="return launchEditor(\'image1\', \'{$CURHOST}/upload/stock/'+src+'\');"><img id="image1" src="{$CURHOST}/upload/stock/'+src+'" alt="photo to edit" /></a>');
	return launchEditor("image1", '{$CURHOST}/upload/stock/'+src);
}
function startUpload()
{
	$('#file').uploadifyUpload();	
	//$('#file').uploadifySettings('scriptData', '');
}
</script>
<div id="injection_site"></div>
</body>
</html>
<div class="popup_box_main" id="loader" style="display:none;">
    <div class="popup_overlay"></div>
    <div class="popup_box" align="center">
        <div class="popup_box_inr">
    	<div style="width:100%; text-align:center;">Please wait as we are uploading your photo.</div>
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
		$("#image_view").html('<a href="javascript:void(0);" onclick="return launchEditor(\''+imageID+'\', \''+newURL+'\');"><img id="image1_after_save" src="'+newURL+'" alt="photo to edit" /></a>');
		$("#image1").attr('src', newURL);
		$("#after_success").html('<strong>Satisfied with your invitation? </strong><a class="btn btn-small" href="javascript:void(0);" onclick="save_image();"><span>&nbsp; Save Image &nbsp;</span></a>');
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
	var data = $.base64.encode($('#image1_after_save').attr('src'));
	$.ajax({
		type: 'POST',
		url: '{$CURHOST}/event/create/invite/upload/save',
		data: 'imageURL='+data+"&eid={$smarty.session.manage_event->eid}",
		cache: false,
		success: function()
		{
			$("#after_success").remove();
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
showStockImages(1);
</script>
