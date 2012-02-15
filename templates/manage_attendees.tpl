{include file="head.tpl"}
<body>

{include file="new_header.tpl"}
<div id="container">
	{include file="manage_header.tpl"}
	<section id="main">
		<aside id="checkin">
			<div class="turtle">
				<p>Check-in to improve trueRSVP’s accuracy!</p>
			</div>
			<p class="checkin-options">Three ways to check in your guests</p>
			<ul>
				<li class="checkin-qr">Scan QR tickets with our trueRSVP mobile app.</li>
				<li class="checkin-manual">Manually check off guests who showed up on this page.</li>
				<li class="checkin-app">Let your guest check themselves in by downloading our trueRSVP app.</li>
			</ul>
			<footer class="links-extra">
				<p><a href="{$CURHOST}/event/manage?eventId={$smarty.session.manage_event->eid}" class="btn btn-manage"><span><span class="btn-back">Back to manage page</span></span></a></p>
			</footer>
		</aside>
		<div id="content">
			<header class="block">
				<p class="message">Confirm who showed up to improve your trueRSVP accuracy for your next event!</p>
			</header>{if ! isset($eventAttendees)}

			<section class="block" id="cp-attendee-list">
				<header class="block">
					<p class="message">No attendees</p>
				</header>
			</section>{else}
			
			<form method="post" action="#" class="quicksearch" id="search-guestlist">
				<fieldset>
					<span class="fl"><input type="text" value="Search by name" /></span>
                    {if date('Y-m-d', strtotime($event->datetime)) eq date('Y-m-d')}
                    	<span class="fr"><a class="btn btn-manage" style="margin-top:4px;" id="show-popup-addguest" href="javascript:void(0);"><span class="btn-back add_guest"><span>Add Guest</span></span></a></span>
                    {/if}
				</fieldset>
			</form>
            <section class="block manage_box" id="cp-attendees">

				<header class="block-collapsable-title">
					<h1>Guest List</h1>
				</header>
				<ul class="list">
					<li class="list-head">
						<div>
                                                    <em id="head-show" class="red">Showed Up?</em><span id="head-name" class="yelo"><a href="#">First Name</a></span><span id="head-name" class="blue"><a href="#">Last Name</a></span><span id="head-name" class="gren"><a href="#">RSVP</a></span><strong id="head-rsvp" class="gray"><a href="#">Plus</a></strong> 
						</div>
					</li>
				</ul>
				<ul class="list" id="attendee-list">
					{foreach from=$eventAttendees key=index item=guest}
                    <li>
						<label for="attendee-{$index}">
                        	{if $guest->is_attending eq 0}
								<em id="check-in-{$index}"><a class="btn btn-manage" href="javascript:void(0);" onClick="markCheckIn('{$smarty.get.eventId}', '{$guest->id}', 'check-in-{$index}', 'uncheck-in-{$index}');"><span class="btn-back">Check In</span></a></em>
                                <em id="uncheck-in-{$index}" style="display:none;"><a class="btn btn-gray_sml" href="javascript:void(0);" onClick="unMarkCheckIn('{$smarty.get.eventId}', '{$guest->id}', 'uncheck-in-{$index}', 'check-in-{$index}');"><span class="btn-back">Checked In</span></a></em>
                            {else}
                            	<em id="uncheck-in-{$index}"><a class="btn btn-gray_sml" href="javascript:void(0);" onClick="unMarkCheckIn({$smarty.get.eventId}, {$guest->id}, 'uncheck-in-{$index}', 'check-in-{$index}');"><span class="btn-back">Checked In</span></a></em>
                                <em id="check-in-{$index}" style="display:none;"><a class="btn btn-manage" href="javascript:void(0);" onClick="markCheckIn({$smarty.get.eventId}, {$guest->id}, 'check-in-{$index}', 'uncheck-in-{$index}');"><span class="btn-back">Check In</span></a></em>
                            {/if}	
                            <span title="{if isset($guest->fname)}{$guest->fname}{/if}">{if isset($guest->fname)}{$guest->fname}{/if}</span>
                            <span title="{if isset($guest->lname)}{$guest->lname}{/if}">{if isset($guest->lname)}{$guest->lname}{/if}</span>
                            <span title="{if isset($guest->fname)}{$guest->fname}{/if} {if isset($guest->lname)}{$guest->lname}{/if} {$guest->confidence}">{$guest->friendly_confidence}</span>
                            <strong title="{$guest->confidence}">{$eventReferred[$index]}</strong>								
						</label>
					</li>
                    {/foreach}
				</ul>
			</section>
			{/if}

		</div>
	</section>
</div>
{include file="footer.tpl"}
{include file="js_global.tpl"}
{include file="js_manage.tpl"}
{include file="popup_manage_guests.tpl"}
</body>
</html>
