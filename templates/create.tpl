{include file="head.tpl"}
<body>

{include file="header.tpl"}
<div id="container">
	<nav class="steps step-{$step}" id="create-nav">
		<ol>
			<li>Basic info</li>
			<li>Preferences</li>
			<li>Add guests</li>
			<li>Success!</li>
		</ol>
	</nav>
	<div class="create">{if $step == 1}

		<section class="block">{if isset($error)}

			<header class="block error">
				<p class="message">Please fix the errors below before continuing.</p>
			</header>{/if}

			<div class="form" id="event_create">
				<form method="post" action="{$CURHOST}/event/create">
					<fieldset>
						<legend>Create Event</legend> 
						<dl>
							<dt>
								<label for="title">What are you planning?<span>*</span></label>
							</dt>
							<dd{if isset($error.title)} class="error"{/if}>
								<input type="text" name="title" value="{if isset($event_field.title)}{$event_field.title}{elseif isset($smarty.post.title)}{$smarty.post.title}{else}Name of Event{/if}" class="inputbox{if ! isset($event_field.title) && ! isset($smarty.post.title)} default{/if}" id="title" />{if isset($error.title)}

								<em>{$error.title}</em>{/if}

							</dd>
							<dt>
								<label for="date">When<span>*</span></label> 
							</dt>
							<dd{if isset($error.date) || isset($error.time)} class="error"{/if}>
								<input type="text" name="date" value="{$event_field.date}" class="inputbox datebox" id="date" /> <select name="time" class="timebox" id="time">{include file="timeselect.tpl" time=$event_field.time}</select>{if isset($error.date) && isset($error.time)}

								<em>{$error.time}</em>{elseif isset($error.date)}

								<em>{$error.date}</em>{elseif isset($error.time)}

								<em>{$error.time}</em>{/if}{if ! isset($event_field.end_date) or $event_field.end_date == ""}
								
								<p><a href="#" id="end-date">Add End Time</a></p>{/if}

							</dd>
							<dt id="add-end-time-title"{if ! isset($event_field.end_date) || $event_field.end_date == ""} style="display: none"{/if}>
								<label for="end_date">Until When</label>
								<em>Date &amp; Time</em>
							</dt>
							<dd{if isset($error.end_date) || isset($error.end_time)} class="error"{/if} id="add-end-time"{if ! isset($event_field.end_date) || $event_field.end_date == ""} style="display: none"{/if}>
								<input type="text" name="end_date" value="{$event_field.end_date}" class="inputbox datebox" id="end_date" /> <select name="end_time" class="timebox" id="end_time">{include file="timeselect.tpl" time=$event_field.end_time}</select>{if isset($error.end_date) && isset($error.end_time)}

								<em>{$error.end_time}</em>{elseif isset($error.end_date)}

								<em>{$error.end_date}</em>{elseif isset($error.end_time)}

								<em>{$error.end_time}</em>{/if}

							</dd>
							<dt>
								<label for="address">Where<span>*</span></label> 
							</dt> 
							<dd{if isset($error.address)} class="error"{/if}>
								<input type="text" name="address" value="{if isset($event_field.address)}{$event_field.address}{else}Ex: 1234 Maple St, Los Angeles, CA 90007{/if}" class="inputbox{if ! isset($event_field.address)} default{/if}" id="address" />{if isset($error.address)}

								<em>{$error.address}</em>{/if}{if ! isset($event_field.location)}
								
								<p><a href="#" id="add-location">Add Location Name</a></p>{/if}

							</dd>
							<dt id="add-location-name-title"{if ! isset($event_field.location)} style="display: none"{/if}>
								<label for="location">Name of Location</label>
							</dt>
							<dd{if isset($error.location)} class="error"{/if} id="add-location-name"{if ! isset($event_field.location)} style="display: none"{/if}>
								<input type="text" name="location" value="{if isset($event_field.location)}{$event_field.location}{else}Ex: Jim's House{/if}" class="inputbox{if ! isset($event_field.location)} default{/if}" id="location" />{if isset($error.location)}

								<em>{$error.location}</em>{/if}

							</dd>
							<dt>
								<label for="goal">Max attendance<span>*</span></label> 
							</dt>
							<dd{if isset($error.goal)} class="error"{/if}>
								<input type="text" name="goal" value="{if isset($smarty.post.goal)}{$smarty.post.goal}{else}In # of Attendees{/if}" class="inputbox{if ! isset($smarty.post.goal)} default{/if}" id="goal" />{if isset($error.goal)}

								<em>{$error.goal}</em>{/if}

							</dd>
							<dt>
								<label for="early"><input type="checkbox" id="early" /> Send my most reliable guests an early invite</label>
							</dt>
							<dt>
								<p>Delay the rest of the invitations for <select name="delay"><option value="0">Please select</option> <option value="1">1 hour</option> <option value="2">2 hours</option> <option value="3">3 hours</option> <option value="4">4 hours</option> <option value="5">5 hours</option> <option value="6">6 hours</option> <option value="12">12 hours</option> <option value="16">16 hours</option> <option value="24">24 hours</option> <option value="48">48 hours</option></select></p>
							</dt> 
						</dl>{if ! isset($error.goal)}

						<aside class="turtle">
							<p>I'm so excited to see your event!</p>
						</aside>{/if}

						<footer class="buttons buttons-submit">
							<p><span class="btn btn-med"><input type="submit" name="step1" value="Next" /></span></p> 
						</footer> 
					</fieldset>
				</form>
			</div>
		</section>{elseif $step == 2}

		<section class="block">{if isset($error)}

			<header class="block error">
				<p class="message">Please fix the errors below before continuing.</p>
			</header>{/if}

			<div class="form" id="event_create">
				<form method="post" action="{$CURHOST}/event/create">
					<fieldset>
						<legend>Create Event</legend> 
						<dl>
							<dt> 
								<label for="type">Event Type<span>*</span></label>
							</dt>
							<dd{if isset($error.type)} class="error"{/if}>
								<select name="type" id="type"> 
									<option value="0">Please Select</option> 
									<optgroup label="Personal"> 
										<option value="1"{if $event_field.type eq '1'} selected{/if}>Birthday</option> 
										<option value="2"{if $event_field.type eq '2'} selected{/if}>Other party</option> 
										<option value="3"{if $event_field.type eq '3'} selected{/if}>Dinner</option> 
										<option value="4"{if $event_field.type eq '4'} selected{/if}>Social gathering</option> 
										<option value="5"{if $event_field.type eq '5'} selected{/if}>Shared travel/trip</option> 
										<option value="6"{if $event_field.type eq '6'} selected{/if}>Wedding related</option> 
										<option value="17"{if $event_field.type eq '17'} selected{/if}>Other</option>
									</optgroup> 
									<optgroup label="Educational"> 
										<option value="7"{if $event_field.type eq '7'} selected{/if}>Club meetup</option> 
										<option value="8"{if $event_field.type eq '8'} selected{/if}>Educational event</option> 
										<option value="9"{if $event_field.type eq '9'} selected{/if}>Recruiting/career</option> 
										<option value="10"{if $event_field.type eq '10'} selected{/if}>School-sponsored event</option> 
										<option value="11"{if $event_field.type eq '11'} selected{/if}>Greek</option> 
										<option value="18"{if $event_field.type eq '18'} selected{/if}>Other</option>
									</optgroup> 
									<optgroup label="Professional"> 
										<option value="12"{if $event_field.type eq '12'} selected{/if}>Fundraiser</option> 
										<option value="13"{if $event_field.type eq '13'} selected{/if}>Professional event/networking</option> 
										<option value="14"{if $event_field.type eq '14'} selected{/if}>Meeting</option> 
										<option value="15"{if $event_field.type eq '15'} selected{/if}>Club</option> 
										<option value="16"{if $event_field.type eq '16'} selected{/if}>Conference</option> 
										<option value="19"{if $event_field.type eq '19'} selected{/if}>Other</option>
									</optgroup> 
								</select>{if isset($error.type)}

								<em>{$error.type}</em>{/if}

							</dd>
							<dt>
								<label for="description">Event Details<span>*</span></label> 
							</dt>
							<dd{if isset($error.desc)} class="error"{/if}>
								<textarea name="description" class="inputbox{if ! isset($event_field.description)} default{/if}" id="description">{if isset($event_field.description)}{$event_field.description}{else}What should your guests know?{/if}</textarea>{if isset($error.desc)}

								<em>{$error.desc}</em>{/if}

							</dd>
							<dt>
								<label for="deadline">Last day to RSVP</label> 
								<em>Last day for anyone to reserve a spot</em>
							</dt>
							<dd{if isset($error.deadline)} class="error"{/if}>
								<input type="text" name="deadline" value="{if ! isset($event_field.deadline)}{$smarty.post.date}{else}{$event_field.deadline|escape:'htmlall'}{/if}" class="inputbox datebox" id="deadline" />{if isset($error.deadline)}

								<em>{$error.deadline}</em>{/if}

							</dd>
							<dt>
								<label>What happens when you reach your goal?</label>
							</dt>
							<dd>
								<label for="reach_goal_1">
									<input type="radio" name="reach_goal" value="1"{if $event_field.reach_goal eq '1' or ! isset($event_field.reach_goal)} checked="checked"{/if} id="reach_goal_1" /> <span>Continue to allow RSVPs</span>
								</label> 
								<label for="reach_goal_2">
									<input type="radio" name="reach_goal" value="2"{if $event_field.reach_goal eq '2'} checked="checked"{/if} id="reach_goal_2" /> <span>Don't allow anymore RSVPs</span>
								</label> 
								<!--label for="reach_goal_3">
									<input type="radio" name="reach_goal" value="3"{if $event_field.reach_goal eq '3'} checked="checked"{/if} id="reach_goal_3" /> <span>Start a waitlist</span>
								</label-->
							</dd>
							<dt>
								<label>Event Permissions</label>
							</dt>
							<dd>
								<label for="is_public_yes">
									<input type="radio" name="is_public" value="1"{if $event_field.is_public eq '1' or ! isset($event_field.is_public)} checked="checked"{/if} id="is_public_yes" /> <span>Anyone can sign up and invite others</span>
								</label> 
								<label for="is_public_no">
									<input type="radio" name="is_public" value="0"{if $event_field.is_public eq '0'} checked="checked"{/if} id="is_public_no" /> <span>Only people you invite can attend</span>
								</label>
							</dd>
							<dt>
								<label>Twitter Hash Tag</label>
								<em>To share tweets &amp; photos</em>
							</dt>
							<dd{if isset($error.twitter)} class="error"{/if}>
								<input type="text" name="twitter" value="{if isset($event_field.twitter)}{$event_field.twitter}{else}Ex: #TurtlesRock{/if}" class="inputbox{if ! isset($event_field.twitter)} default{/if}" id="twitter" />{if isset($error.twitter)}

								<em>{$error.twitter}</em>{/if}
							</dd>
						</dl>
						<aside class="turtle">
							<p>You can always come back and edit these later!</p>
						</aside>
						<footer class="buttons buttons-submit">
							<input type="hidden" name="title" value="{$smarty.post.title}" />{if isset($smarty.post.location)}<input type="hidden" name="location" value="{$smarty.post.location}" />{/if}

							<input type="hidden" name="address" value="{$smarty.post.address}" />
							<input type="hidden" name="date" value="{$smarty.post.date}" />
							<input type="hidden" name="time" value="{$smarty.post.time}" />{if isset($smarty.post.end_date)}

							<input type="hidden" name="end_date" value="{$smarty.post.end_date}" />{/if}{if isset($smarty.post.end_time)}

							<input type="hidden" name="end_time" value="{$smarty.post.end_time}" />{/if}

							<input type="hidden" name="goal" value="{$smarty.post.goal}" />

							<p><span class="btn btn-med"><input type="submit" name="step2" value="Next" /></span></p> 
						</footer> 
					</fieldset>{/if}

				</form>
			</div>
		</section>
	</div>
</div>
{include file="footer.tpl"}

{include file="js_global.tpl"}
{include file="js_create.tpl"}

</body>
</html>
