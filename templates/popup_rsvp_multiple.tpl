<div class="popup-container" id="rsvp-multiple">
	<div class="popup block" style="width:226px;">
    	<p class="popup-close"><a href="#">X</a></p>
        <div class="pp_guest">
            <h2>Fill out guest info</h2>
            <div class="clear5"></div>
            {if $event->total_rsvps > 0}
                <div class="cont">
                    <div class="c_lft">You plus</div>    
                    <form name="guests_form" id="guests_form">
                    <input type="hidden" name="event_id" id="event_id" value="{$event->eid}" />
                    <div class="c_rgt">
                    	<select id="total_rsvps" name="total_rsvps" onchange="showInviteFields($('#total_rsvps :selected').val());">
                        {section name=foo start=0 loop=($event->total_rsvps+1) step=1}
                        	<option value="{$smarty.section.foo.index}">{$smarty.section.foo.index} extra people</option>
                        {/section}
                    	</select>
                    {if isset($smarty.session.user)}
                    	<input type="hidden" name="guest_name_0" value="{$smarty.session.user->fname}" id="guest_name_0" />
                        <input type="hidden" name="guest_email_0" id="guest_email_0" value="{$smarty.session.user->email}" />
                    {else}
                    	<input type="hidden" name="guest_name_0" value="" id="guest_name_0" />
                        <input type="hidden" name="guest_email_0" id="guest_email_0" value="" />
                    {/if}
                    </div>
                    <div id="error"></div>
                    <div id="showInviteList"></div>
                    </form>
                    <div class="clear5"></div>
                    <a href="javascript:void(0);" class="btn btn-manage fr" onclick="saveRsvps();"><span>&nbsp; RSVP &nbsp;</span></a>
                    <div class="clear5"></div>
                </div>
			{/if}
        </div>
	</div>
</div>
