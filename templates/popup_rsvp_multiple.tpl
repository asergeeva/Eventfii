{if $you_rsvpd eq 'false'}
<div class="popup-container rssvp_popup_seting" id="rsvp-multiple">
	<div class="popup block" style="width:226px;">
    	<p class="popup-close"><a href="#">X</a></p>
        <div class="pp_guest">
            <h2>Fill out guest info</h2>
            <div class="clear5"></div>
            <form name="guests_form" id="guests_form">
            {if $event->total_rsvps > 0}
                <div class="cont">
                    <div class="c_lft">You plus</div>
                    <div class="c_rgt">
                    	<select id="total_rsvps" name="total_rsvps" onchange="showInviteFields($('#total_rsvps :selected').val());">
                        {section name=foo start=0 loop=($event->total_rsvps+1) step=1}
                        	<option value="{$smarty.section.foo.index}">{$smarty.section.foo.index} extra person</option>
                        {/section}
                    	</select>
                    </div>
                    <div id="error" style="font-size:12px; color:red;"></div>
                    <div id="showInviteList"></div>
                    <div class="clear5"></div>
                    <a href="javascript:void(0);" class="btn btn-manage fr" onclick="saveRsvps({if isset($smarty.session.user)}'1'{else}'2'{/if});"><span>&nbsp; RSVP &nbsp;</span></a>
                    <div class="clear5"></div>
                </div>
			{/if}
            <input type="hidden" name="event_id" id="event_id" value="{$event->eid}" />
            <input type="hidden" name="total_guests_last_added" id="total_guests_last_added" value="0" />
            {if isset($smarty.session.user)}
                <input type="hidden" name="guest_name_0" value="{$smarty.session.user->fname}" id="guest_name_0" />
                <input type="hidden" name="guest_email_0" id="guest_email_0" value="{$smarty.session.user->email}" />
                <input type="hidden" name="user_id_posted" id="user_id_posted" value="{$smarty.session.user->id}" />
            {else}
                <input type="hidden" name="guest_name_0" value="" id="guest_name_0" />
                <input type="hidden" name="guest_email_0" id="guest_email_0" value="" />
                <input type="hidden" name="user_id_posted" id="user_id_posted" value="0" />
            {/if}
            </form>
        </div>
	</div>
</div>
{else}
<div class="popup-container rssvp_popup_seting" id="rsvp-multiple">
	<div class="popup block" style="width:226px;">
    	<p class="popup-close"><a href="#">X</a></p>
        <div class="pp_guest">
            <h2>Fill out guest info</h2>
            <div class="clear5"></div>
            <form name="guests_form" id="guests_form">
            {if $event->total_rsvps > 0}
                <div class="cont">
                    <div class="c_lft">You plus</div>
                    <div class="c_rgt">
                    	<select id="total_rsvps" name="total_rsvps" onchange="showInviteFieldsEdit($('#total_rsvps :selected').val());">
                        {section name=foo start=sizeof($user_refered) loop=($event->total_rsvps+1) step=1}
                        	<option value="{$smarty.section.foo.index}" {if sizeof($user_refered) eq $smarty.section.foo.index} selected="selected"{/if}>{$smarty.section.foo.index} extra person</option>
                        {/section}
                    	</select>
                    </div>
                    <div id="error" style="font-size:12px; color:red;"></div>
                    <div id="showInviteList">
                    	{foreach from=$user_refered key=index item=guest}
                        	<div class="clear5" id="clear_{$index+1}"></div>
                            <div class="c_lft" id="guest_id_{$index+1}">Guest #{$index+1}:</div>
                            <div class="c_rgt" id="guest_info_{$index+1}"><input type="text" name="guest_name_{$index+1}" value="{$guest['fname']}" id="guest_name_{$index+1}" readonly="readonly" /> <input type="text" name="guest_email_{$index+1}" id="guest_email_{$index+1}" value="{$guest['email']}" readonly="readonly" /></div>
                        {/foreach}
                    </div>
                    <div class="clear5"></div>
                    <a href="javascript:void(0);" class="btn btn-manage fr" onclick="saveRsvps({if isset($smarty.session.user)}'1'{else}'2'{/if}, 'update');"><span>&nbsp; Update &nbsp;</span></a>
                    <div class="clear5"></div>
                </div>
			{/if}
            <input type="hidden" name="event_id" id="event_id" value="{$event->eid}" />
            <input type="hidden" name="total_guests" id="total_guests" value="{sizeof($user_refered)}" />
            <input type="hidden" name="total_guests_last_added" id="total_guests_last_added" value="{sizeof($user_refered)}" />
            {if isset($smarty.session.user)}
                <input type="hidden" name="guest_name_0" value="{$smarty.session.user->fname}" id="guest_name_0" />
                <input type="hidden" name="guest_email_0" id="guest_email_0" value="{$smarty.session.user->email}" />
                <input type="hidden" name="user_id_posted" id="user_id_posted" value="{$smarty.session.user->id}" />
            {else}
                <input type="hidden" name="guest_name_0" value="" id="guest_name_0" />
                <input type="hidden" name="guest_email_0" id="guest_email_0" value="" />
                <input type="hidden" name="user_id_posted" id="user_id_posted" value="0" />
            {/if}
            </form>
        </div>
	</div>
</div>
{/if}
<div class="popup-container" id="already-rsvpd">
	<div class="popup block" style="width:226px;">
    	<p class="popup-close"><a href="#">X</a></p>
        <div class="pp_guest">
            <h2>You already has RSVP'd to {$event->title}</h2>
        </div>
	</div>
</div>