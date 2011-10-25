{include file="head.tpl"}
<body>

{include file="header.tpl"}
<div id="container">
	{include file="manage_header.tpl"}
	<section id="main">
		{include file="manage_nav.tpl"}
		
		<div class="manage">
			<header class="block notification" style="display:none" id="notification-box">
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
					<dl class="table"> 
						<dt>Response</dt> 
						<dd>#</dd>
						<dt><a href="#">Absolutely - I'll definitely be there!</a></dt> 
						<dd>{$guestConf1}</dd> 
						<dd class="table-extra">
							<ul class="user-list">
								<li>
									<label for="contact-1">
										<img src="{$CURHOST}/images/default_thumb.jpg" width="36px" height="36px" alt="Guest Name" />
										<h3>Guest Name</h3>
										<p>Whatever goes here</p>
									</label>
								</li>
								<li>
									<label for="contact-1">
										<img src="{$CURHOST}/images/default_thumb.jpg" width="36px" height="36px" alt="Guest Name" />
										<h3>Guest Name</h3>
										<p>Whatever goes here</p>
									</label>
								</li>
								<li>
									<label for="contact-1">
										<img src="{$CURHOST}/images/default_thumb.jpg" width="36px" height="36px" alt="Guest Name" />
										<h3>Guest Name</h3>
										<p>Whatever goes here</p>
									</label>
								</li>
								<li>
									<label for="contact-1">
										<img src="{$CURHOST}/images/default_thumb.jpg" width="36px" height="36px" alt="Guest Name" />
										<h3>Guest Name</h3>
										<p>Whatever goes here</p>
									</label>
								</li>
								<li>
									<label for="contact-1">
										<img src="{$CURHOST}/images/default_thumb.jpg" width="36px" height="36px" alt="Guest Name" />
										<h3>Guest Name</h3>
										<p>Whatever goes here</p>
									</label>
								</li>
								<li>
									<label for="contact-1">
										<img src="{$CURHOST}/images/default_thumb.jpg" width="36px" height="36px" alt="Guest Name" />
										<h3>Guest Name</h3>
										<p>Whatever goes here</p>
									</label>
								</li>
								<li>
									<label for="contact-1">
										<img src="{$CURHOST}/images/default_thumb.jpg" width="36px" height="36px" alt="Guest Name" />
										<h3>Guest Name</h3>
										<p>Whatever goes here</p>
									</label>
								</li>
								<li>
									<label for="contact-1">
										<img src="{$CURHOST}/images/default_thumb.jpg" width="36px" height="36px" alt="Guest Name" />
										<h3>Guest Name</h3>
										<p>Whatever goes here</p>
									</label>
								</li>
								<li>
									<label for="contact-1">
										<img src="{$CURHOST}/images/default_thumb.jpg" width="36px" height="36px" alt="Guest Name" />
										<h3>Guest Name</h3>
										<p>Whatever goes here</p>
									</label>
								</li>
								<li>
									<label for="contact-1">
										<img src="{$CURHOST}/images/default_thumb.jpg" width="36px" height="36px" alt="Guest Name" />
										<h3>Guest Name</h3>
										<p>Whatever goes here</p>
									</label>
								</li>
								<li>
									<label for="contact-1">
										<img src="{$CURHOST}/images/default_thumb.jpg" width="36px" height="36px" alt="Guest Name" />
										<h3>Guest Name</h3>
										<p>Whatever goes here</p>
									</label>
								</li>
								<li>
									<label for="contact-1">
										<img src="{$CURHOST}/images/default_thumb.jpg" width="36px" height="36px" alt="Guest Name" />
										<h3>Guest Name</h3>
										<p>Whatever goes here</p>
									</label>
								</li>
								<li>
									<label for="contact-1">
										<img src="{$CURHOST}/images/default_thumb.jpg" width="36px" height="36px" alt="Guest Name" />
										<h3>Guest Name</h3>
										<p>Whatever goes here</p>
									</label>
								</li>
								<li>
									<label for="contact-1">
										<img src="{$CURHOST}/images/default_thumb.jpg" width="36px" height="36px" alt="Guest Name" />
										<h3>Guest Name</h3>
										<p>Whatever goes here</p>
									</label>
								</li>
								<li>
									<label for="contact-1">
										<img src="{$CURHOST}/images/default_thumb.jpg" width="36px" height="36px" alt="Guest Name" />
										<h3>Guest Name</h3>
										<p>Whatever goes here</p>
									</label>
								</li>
							</ul>
						</dd>
						<dt><a href="#">Pretty sure - I'll have to check my schedule</a></dt> 
						<dd>{$guestConf2}</dd> 
						<dd class="table-extra"></dd>						
						<dt><a href="#">50/50 - Interested, but not ready to commit</a></dt> 
						<dd>{$guestConf3}</dd> 
						<dd class="table-extra"></dd>						
						<dt><a href="#">Most likely not - I probably won't go</a></dt> 
						<dd>{$guestConf4}</dd> 
						<dd class="table-extra"></dd>						
						<dt><a href="#">Raincheck - Can't make it this time</a></dt> 
						<dd>{$guestConf5}</dd>
						<dd class="table-extra"></dd>						
						<dt><a href="#">No Response</a></dt> 
						<dd>{$guestNoResp}</dd> 
						<dd class="table-extra"></dd>						
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