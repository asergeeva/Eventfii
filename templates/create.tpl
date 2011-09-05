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

			<header class="block notification">
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
							<dd>
								<input type="text" name="title" value="{if isset($event_field.title)}{$event_field.title}{else}Name of Event{/if}" class="inputbox autowidth{if ! isset($event_field.title)} default{/if}" id="title" />{if isset($error.title)}

								<p class="message-error">{$error.title}</p>{/if}

							</dd> 
							<dt>
								<label for="description">Event Details<span>*</span></label> 
							</dt>
							<dd>
								<textarea name="description" class="inputbox autowidth{if ! isset($event_field.description)} default{/if}" id="description">{if isset($event_field.description)}{$event_field.description}{else}What should your guests know?{/if}</textarea>{if isset($error.desc)}

								<p class="message-error">{$error.desc}</p>{/if}

							</dd>
							<dt> 
								<label for="type">Event Type<span>*</span></label>
							</dt>
							<dd>
								<select name="type" class="autowidth" id="type"> 
									<option value="0">Please Select</option> 
									<optgroup label="Personal"> 
										<option value="1"{if $event_field.type eq '1'} selected{/if}>Birthday</option> 
										<option value="2"{if $event_field.type eq '2'} selected{/if}>Other party</option> 
										<option value="3"{if $event_field.type eq '3'} selected{/if}>Dinner</option> 
										<option value="4"{if $event_field.type eq '4'} selected{/if}>Social gathering</option> 
										<option value="5"{if $event_field.type eq '5'} selected{/if}>Shared travel/trip</option> 
										<option value="6"{if $event_field.type eq '6'} selected{/if}>Wedding related</option> 
									</optgroup> 
									<optgroup label="Educational"> 
										<option value="7"{if $event_field.type eq '7'} selected{/if}>Club meetup</option> 
										<option value="8"{if $event_field.type eq '8'} selected{/if}>Educational event</option> 
										<option value="9"{if $event_field.type eq '9'} selected{/if}>Recruiting/career</option> 
										<option value="10"{if $event_field.type eq '10'} selected{/if}>School-sponsored event</option> 
										<option value="11"{if $event_field.type eq '11'} selected{/if}>Greek</option> 
									</optgroup> 
									<optgroup label="Professional"> 
										<option value="12"{if $event_field.type eq '12'} selected{/if}>Fundraiser</option> 
										<option value="13"{if $event_field.type eq '13'} selected{/if}>Professional event/networking</option> 
										<option value="14"{if $event_field.type eq '14'} selected{/if}>Meeting</option> 
										<option value="15"{if $event_field.type eq '15'} selected{/if}>Club</option> 
										<option value="16"{if $event_field.type eq '16'} selected{/if}>Conference</option> 
									</optgroup> 
								</select>{if isset($error.type)}

								<p class="message-error">{$error.type}</p>{/if}

							</dd>
							<dt>
								<label for="location">Name of Location</label>
							</dt>
							<dd>
								<input type="text" name="location" value="{if isset($event_field.location)}{$event_field.location}{else}Ex: Jim's House{/if}" class="inputbox autowidth{if ! isset($event_field.location)} default{/if}" id="location" />{if isset($error.location)}

								<p class="message-error">{$error.location}</p>{/if}

							</dd>
							<dt>
								<label for="address">Address<span>*</span></label> 
							</dt> 
							<dd>
								<input type="text" name="address" value="{if isset($event_field.address)}{$event_field.address}{else}Ex: 1234 Maple St, Los Angeles, CA 90007{/if}" class="inputbox autowidth{if ! isset($event_field.address)} default{/if}" id="address" />{if isset($error.address)}

								<p class="message-error">{$error.address}</p>{/if}

							</dd>
							<dt>
								<label for="date">When<span>*</span></label> 
							</dt>
							<dd>
								<p><input type="text" name="date" value="{$event_field.date}" class="inputbox datebox" id="date" /> <select name="time" class="timebox" id="time">{include file="timeselect.tpl" time=$event_field.time}</select></p>{if isset($error.date)}

								<p class="message-error">{$error.date}</p>{/if}{if isset($error.time)}

								<p class="message-error">{$error.time}</p>{/if}{if ! isset($event_field.end_date) or $event_field.end_date == ""}
								
								<p><a href="#" id="end-date">Add End Time</a></p>{/if}

							</dd>
							<dt id="add-end-time-title"{if ! isset($event_field.end_date) or $event_field.end_date == ""} style="display: none"{/if}>
								<label for="end_date">Until When</label>
								<em>Date &amp; Time</em>
							</dt>
							<dd id="add-end-time"{if ! isset($event_field.end_date) or $event_field.end_date == ""} style="display: none"{/if}>
								<p><input type="text" name="end_date" value="{$event_field.end_date}" class="inputbox datebox" id="end_date" /> <select name="end_time" class="timebox" id="end_time">{include file="timeselect.tpl" time=$event_field.end_time}</select></p>{if isset($error.end_date)}

								<p class="message-error">{$error.end_date}</p>{/if}{if isset($error.end_time)}

								<p class="message-error">{$error.end_time}</p>{/if}

							</dd>
							<dt>
								<label for="deadline">Deadline to sign up</label> 
								<em>Last day for anyone to reserve a spot</em>
							</dt>
							<dd>
								<input type="text" name="deadline" value="{$event_field.deadline|escape:'htmlall'}" class="inputbox datebox" id="deadline" />{if isset($error.deadline)}

								<p class="message-error">{$error.deadline}</p>{/if}

							</dd>
						</dl>
						<footer class="buttons buttons-submit">
							<p><span class="btn btn-med"><input type="submit" name="step1" value="Next" /></span></p> 
						</footer> 
					</fieldset>
				</form>
			</div>
		</section>{elseif $step == 2}

		<section class="block">{if isset($error)}

			<header class="block notification">
				<p class="message">Please fix the errors below before continuing.</p>
			</header>{/if}

			<div class="form" id="event_create">
				<form method="post" action="{$CURHOST}/event/create">
					<fieldset>
						<legend>Create Event</legend> 
						<dl> 
							<dt>
								<label for="goal">Attendance Goal<span>*</span></label> 
							</dt>
							<dd>
								<input type="text" name="goal" value="{if isset($smarty.post.goal)}{$smarty.post.goal}{else}In # of Attendees{/if}" class="inputbox autowidth{if ! isset($smarty.post.goal)} default{/if}" id="goal" />{if isset($error.goal)}

								<p class="message-error">{$error.goal}</p>{/if}

							</dd>
							<dt>
								<label>What happens when you reach your goal?</label>
							</dt>
							<dd>
								<label for="reach_goal_1">
									<p><input type="radio" name="reach_goal" value="1"{if $event_field.reach_goal eq '1' or ! isset($event_field.reach_goal)} checked="checked"{/if} id="reach_goal_1" /> Continue to allow RSVPs</p>
								</label> 
								<label for="reach_goal_2">
									<p><input type="radio" name="reach_goal" value="2"{if $event_field.reach_goal eq '2'} checked="checked"{/if} id="reach_goal_2" /> Don't allow anymore RSVPs</p>
								</label> 
								<label for="reach_goal_3">
									<p><input type="radio" name="reach_goal" value="3"{if $event_field.reach_goal eq '3'} checked="checked"{/if} id="reach_goal_3" /> Start a waitlist</p>
								</label>{if isset($error.pub)}

								<p class="message-error" id="pubErr">{$error.pub}</p>{/if}

							</dd>
							<dt>
								<label>Event Permissions</label>
							</dt>
							<dd>
								<label for="is_public_yes">
									<p><input type="radio" name="is_public" value="1"{if $event_field.is_public eq '1' or ! isset($event_field.is_public)} checked="checked"{/if} id="is_public_yes" /> Anyone can sign up and invite others</p>
								</label> 
								<label for="is_public_no">
									<p><input type="radio" name="is_public" value="0"{if $event_field.is_public eq '0'} checked="checked"{/if} id="is_public_no" /> Only people you invite can attend</p>
								</label>{if isset($error.pub)}

								<p class="message-error" id="pubErr">{$error.pub}</p>{/if}

							</dd>
							<dt>
								<label>Twitter Hash Tag</label>
							</dt>
							<dd>
								<input type="text" name="twitter" value="{if isset($event_field.twitter)}{$event_field.twitter}{else}Ex: #TurtlesRock{/if}" class="inputbox autowidth{if ! isset($event_field.twitter)} default{/if}" id="twitter" />{if isset($error.twitter)}

								<p class="message-error">{$error.twitter}</p>{/if}
							</dd>
						</dl>
						<footer class="buttons buttons-submit">
							<input type="hidden" name="title" value="{$smarty.post.title}" />
							<input type="hidden" name="description" value="{$smarty.post.description}" />
							<input type="hidden" name="type" value="{$smarty.post.type}" />{if isset($smarty.post.location)}

							<input type="hidden" name="location" value="{$smarty.post.location}" />{/if}

							<input type="hidden" name="address" value="{$smarty.post.address}" />
							<input type="hidden" name="date" value="{$smarty.post.date}" />
							<input type="hidden" name="time" value="{$smarty.post.time}" />{if isset($smarty.post.end_date)}

							<input type="hidden" name="end_date" value="{$smarty.post.end_date}" />{/if}{if isset($smarty.post.end_time)}

							<input type="hidden" name="end_time" value="{$smarty.post.end_time}" />{/if}

							<p><span class="btn btn-med"><input type="submit" name="step2" value="Next" /></span></p> 
						</footer> 
					</fieldset>
				</form>
			</div>
		</section>{elseif $step == 3}

		{include file="create_guest.tpl"}{/if}

	</div>
</div>
{include file="footer.tpl"}

{include file="js_global.tpl"}
{include file="js_create.tpl"}

</body>
</html>