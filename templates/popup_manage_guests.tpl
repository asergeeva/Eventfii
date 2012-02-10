<div class="popup-container" id="popup-addguest">
	<div class="popup block" style="width:300px;">
    	<p class="popup-close"><a href="#">X</a></p>
            <div class="pp_add_guest">
                <h2>Fill out guest info</h2>
                <div class="clear5"></div>
                <div class="cont">  
                	<div id="error" style="font-size:12px; color:red;"></div>
                    <div id="success" style="font-size:12px; color:green;"></div>
                	<form id="guest_form" name="guest_form">              
                        <div class="clear5"></div>
                        <div class="c_lft">First Name:</div>
                        <div class="c_rgt"><input type="text" name="guest_fname" id="guest_fname" value="" /></div>
                        <div class="clear10"></div>
                        <div class="c_lft">Last Name:</div>                
                        <div class="c_rgt"><input type="text" name="guest_lname" id="guest_lname" value="" /></div>
                        <div class="clear10"></div>
                        <div class="c_lft">Email:</div>
                        <div class="c_rgt"><input type="text" name="guest_email" id="guest_email" value="" /></div>
                        <div class="clear10"></div>
                        <div class="c_lft">&nbsp;</div>
                        <div class="c_rgt"><a href="javascript:void(0);" class="btn btn-small" onClick="saveGuest();"><span>&nbsp; &nbsp; Add &nbsp; &nbsp;</span></a></div>
                        <div class="clear5"></div>
                        <input type="hidden" name="event_id" id="event_id" value="{$smarty.get.eventId}" />
                    </form>
                </div>
            </div>
    </div>