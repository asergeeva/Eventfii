{include file="head.tpl"}
<body>

{include file="header.tpl"}
<div id="container">
	{include file="manage_header.tpl"}
	<section id="main">
		{include file="manage_nav.tpl"}
		
		<div id="content">
			<header class="block notification" style="display: none;" id="notification-box">
				<p class="message" id="notification-message">Manage event notification</p>
			</header>{if $smarty.session.manage_event->days_left > 0}

			<header class="block">{if $smarty.session.manage_event->days_left == 1}

				<p class="message"><em>1</em> day left until the event. Get excited!</p>{else}

				<p class="message"><em>{$smarty.session.manage_event->days_left}</em> days left until the event.</p>{/if}

			</header>{/if}

			<section class="block" id="cp-manage">
				<header class="turtle">
					<p><strong>Your trueRSVP #</strong> = our calculation of how many people will show up!</p>
				</header>
				<div class="rsvp-progress">
					<div class="meter" style="width: {$trsvpVal / $smarty.session.manage_event->goal * 100}%">
						<p class="trueRSVP"><em>{$trsvpVal}</em> <span>Your trueRSVP</span></p>
					</div>
					<p class="goal"><em>{$smarty.session.manage_event->goal}</em> <span>Your Goal</span></p>
				</div>
				<p class="message">Click on the response type to see who has RSVP’d to your event.</p>
				<section class="block" id="cp-breakdown">
					<header class="block-collapsable-title">
						<h1>RSVP Breakdown</h1>
					</header>
					<dl class="responses"> 
						<dt>Response</dt> 
						<dd>#</dd>
						<dt><a href="#" {if $guestConf1Count > 0}class="manage-accord"{/if}>Absolutely - I'll definitely be there!</a></dt> 
						<dd>{$guestConf1Count}</dd> 
						<dd class="responses-extra">
							<ul class="user-list">{foreach from=$guestConf1 item=guest}

								<li>
									<label for="{$guest.email}">
										<img src="{if isset($guest.pic)}{$guest.pic}{else}{$IMG_PATH}/default_thumb.jpg{/if}" width="36px" height="36px" alt="Guest Name" />
										<h3>{$guest.fname} {$guest.lname}</h3>
										<span>{$guest.email}</span>
									</label>
								</li>{/foreach}

							</ul>
						</dd>
						<dt><a href="#" {if $guestConf2Count > 0}class="manage-accord"{/if}>Pretty sure - I'll have to check my schedule</a></dt> 
						<dd>{$guestConf2Count}</dd> 
						<dd class="responses-extra">
							<ul class="user-list">{foreach from=$guestConf2 item=guest}

								<li>
									<label for="{$guest.email}">
										<img src="{if isset($guest.pic)}{$guest.pic}{else}{$IMG_PATH}/default_thumb.jpg{/if}" width="36px" height="36px" alt="Guest Name" />
										<h3>{$guest.fname} {$guest.lname}</h3>
										<span>{$guest.email}</span>
									</label>
								</li>{/foreach}

							</ul>
						</dd>						
						<dt><a href="#" {if $guestConf3Count > 0}class="manage-accord"{/if}>50/50 - Interested, but not ready to commit</a></dt> 
						<dd>{$guestConf3Count}</dd> 
						<dd class="responses-extra">
							<ul class="user-list">{foreach from=$guestConf3 item=guest}

								<li>
									<label for="{$guest.email}">
										<img src="{if isset($guest.pic)}{$guest.pic}{else}{$IMG_PATH}/default_thumb.jpg{/if}" width="36px" height="36px" alt="Guest Name" />
										<h3>{$guest.fname} {$guest.lname}</h3>
										<span>{$guest.email}</span>
									</label>
								</li>{/foreach}

							</ul>
						</dd>						
						<dt><a href="#" {if $guestConf4Count > 0}class="manage-accord"{/if}>Most likely not - I probably won't go</a></dt> 
						<dd>{$guestConf4Count}</dd> 
						<dd class="responses-extra">
							<ul class="user-list">{foreach from=$guestConf4 item=guest}

								<li>
									<label for="{$guest.email}">
										<img src="{if isset($guest.pic)}{$guest.pic}{else}{$IMG_PATH}/default_thumb.jpg{/if}" width="36px" height="36px" alt="Guest Name" />
										<h3>{$guest.fname} {$guest.lname}</h3>
										<span>{$guest.email}</span>
									</label>
								</li>{/foreach}

							</ul>
						</dd>						
						<dt><a href="#" {if $guestConf5Count > 0}class="manage-accord"{/if}>Raincheck - Can't make it this time</a></dt> 
						<dd>{$guestConf5Count}</dd>
						<dd class="responses-extra">
							<ul class="user-list">{foreach from=$guestConf5 item=guest}

								<li>
									<label for="{$guest.email}">
										<img src="{if isset($guest.pic)}{$guest.pic}{else}{$IMG_PATH}/default_thumb.jpg{/if}" width="36px" height="36px" alt="Guest Name" />
										<h3>{$guest.fname} {$guest.lname}</h3>
										<span>{$guest.email}</span>
									</label>
								</li>{/foreach}

							</ul>
						</dd>						
						<dt><a href="#" {if $guestNoRespCount > 0}class="manage-accord"{/if}>No Response</a></dt> 
						<dd>{$guestNoRespCount}</dd> 
						<dd class="responses-extra">
							<ul class="user-list">{foreach from=$guestNoResp item=guest}

								<li>
									<label for="{$guest.email}">
										<img src="{if isset($guest.pic)}{$guest.pic}{else}{$IMG_PATH}/default_thumb.jpg{/if}" width="36px" height="36px" alt="Guest Name" />
										<h3>{$guest.fname} {$guest.lname}</h3>
										<span>{$guest.email}</span>
									</label>
								</li>{/foreach}

							</ul>
						</dd>						
					</dl>
					<div id="rsvp" style="display:none;">{{$guestConf1}+{$guestConf2}+{$guestConf3}}</div>
					<div id="goal" style="display:none;">{$smarty.session.manage_event->goal}</div>
				</section>
			</section>
		</div>
	</section>
</div>
{include file="footer.tpl"}

{include file="js_global.tpl"}
{include file="js_manage.tpl"}

</body>
</html>