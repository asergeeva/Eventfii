<div class="popup-container" id="log-in">
	<div class="popup block">
		<p class="popup-close"><a href="#">X</a></p>
		{include file="login_form.tpl"}
	</div>
</div>
<div class="popup-container" id="upload-progress">
	<div class="popup block">
		<p class="popup-close"><a href="javascript:void(0);" onclick="$('#upload-progress').fadeOut(500);">X</a></p>
        <div class="popup_box">
            <div class="delete_photo">
                <p>Please wait as we are uploading your photos.</p>                    
                <div class="loading_out">
                    <div class="loading_inr" id="loadingPercentage" style="width:0%"></div>
                </div>
                <span class="prcnt_comp"><span id="upload-completed">0</span>/<span id="upload-total">0</span> completed</span></span>
            </div>
        </div>
	</div>
</div>
<div class="popup-container" id="upload-error">
	<div class="popup block">
    	<p class="popup-close"><a href="javascript:void(0);" onclick="$('#upload-error').fadeOut(500);">X</a></p>
        <div class="popup_box">
            <div class="delete_photo">
                <p>Sorry, your images must be less than 5MB each.</p>                    
                <p class="lnk_blu20"><a href="javascript:void(0);" onclick="$('#upload-error').fadeOut(500);">Give it another shot?</a></p>
            </div>
        </div>
    </div>
</div>
<div class="popup-container" id="upload-complete">
	<div class="popup block">
        <div class="delete_photo">
            <p>Congrats! Your photos have been uploaded!</p>                    
            <div class="loading_out">
                <div class="loading_inr" style="width:99%; display: none;"></div>
                <div class="loading_inr_full" style="width:100%"><span id="upload-completed">0</span>/<span id="upload-total">0</span> completed</div>
            </div>
            <span class="prcnt_comp lnk_blu20" style="text-align: center;"><a href="javascript:void(0);" onclick="$('#upload-complete').fadeOut(500);">Close and view photos</a></span>
        </div>
	</div>
</div>