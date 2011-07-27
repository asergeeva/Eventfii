<div class="form" id="event_create">
		<form method="post" action="{$CURHOST}/create">
		<fieldset>
			<legend>Create Event</legend> 
			<dl class="column"> 
				<dt>
					<label for="event_title_create">What are you planning?</label> 
					<em>Name of Event</em>
				</dt> 
				<dd>
					<input type="text" class="inputbox autowidth" name="title" value="{$event_field['title']|escape:'htmlall'}" id="event_title_create" /> 
		  			<p class="message-error" id="titleErr">{$error.title}</p>
		  		</dd> 
				<dt>
					<label for="event_description_create">Brief Description</label> 
					<em>What is your event about?</em>
				</dt> 
				<dd>
					<input type="text" class="inputbox autowidth" name="description" id="event_description_create" value="{$event_field['description']|escape:'htmlall'}" /> 
		  			<p class="message-error" id="descErr">{$error['desc']}</p>
		  		</dd>
				<dt>
					<label for="addresspicker">Where</label> 
					<em>Ex: 1234 Maple St, Los Angeles, CA 90007</em>
				</dt> 
				<dd>
					<input type="text" class="inputbox autowidth" name="address" id="event_address_create" value="{$event_field['address']|escape:'htmlall'}" /> 
		  			<p class="message-error" id="addrErr">{$error['address']}</p>
		  		</dd>
				<dt>
					<label for="event_date_create">When</label> 
					<em>Ex: 05/14/2011 (MM/DD/YYYY)</em>
				</dt> 
				<dd>
					<input type="text" class="inputbox autowidth" name="date" id="event_date_create" value="{$event_field['date']}" /> 
		  			<p class="message-error" id="dtErr">{$error['date']}</p>
		  		</dd>
				<dt>
					<label for="event_time_create">What Time</label> 
					<em>Ex: 12:30 PM (12 hour clock)</em>
				</dt> 
				<dd>
					<input type="text" class="inputbox autowidth" name="time" id="event_time_create" value="{$event_field['time']}" /> 
		  			<p class="message-error" id="timeErr">{$error['time']}</p>
		  		</dd>
			</dl> 
			<dl class="column"> 
				<dt>
					<label for="event_goal_create">Attendance Goal</label> 
					<em>In # of Attendees</em>
				</dt>
				<dd>
					<input type="text" class="inputbox autowidth" name="goal" id="event_goal_create" value="{$event_field['goal']|escape:'htmlall'}" /> 
		  			<p class="message-error" id="goalErr">{$error['goal']}</p>
		  		</dd>
				<dt>
					<label for="event_deadline_create">Deadline to sign up</label> 
					<em>Last day for anyone to reserve a spot</em>
				</dt>
				<dd>
					<input type="text" class="inputbox autowidth" name="deadline" id="event_deadline_create" value="{$event_field['deadline']|escape:'htmlall'}" /> 
		  			<p class="message-error" id="deadlineErr">{$error['deadline']}</p>
		  		</dd>
				<dt> 
					<label for="event_type_create">Event Type</label>
				</dt>
				<dd>
					<select name="type" id="event_type_create"> 
						<option value="0">Please Select</option> 
							<optgroup label="Personal"> 
							<option value="1"{if $event_field['type'] eq '1'} selected{/if}>Birthday</option> 
							<option value="2"{if $event_field['type'] eq '2'} selected{/if}>Other party</option> 
							<option value="3"{if $event_field['type'] eq '3'} selected{/if}>Dinner</option> 
							<option value="4"{if $event_field['type'] eq '4'} selected{/if}>Social gathering</option> 
							<option value="5"{if $event_field['type'] eq '5'} selected{/if}>Shared travel/trip</option> 
							<option value="6"{if $event_field['type'] eq '6'} selected{/if}>Wedding related</option> 
						</optgroup> 
						<optgroup label="Educational"> 
							<option value="7"{if $event_field['type'] eq '7'} selected{/if}>Club meetup</option> 
							<option value="8"{if $event_field['type'] eq '8'} selected{/if}>Educational event</option> 
							<option value="9"{if $event_field['type'] eq '9'} selected{/if}>Recruiting/career</option> 
							<option value="10"{if $event_field['type'] eq '10'} selected{/if}>School-sponsored event</option> 
							<option value="11"{if $event_field['type'] eq '11'} selected{/if}>Greek</option> 
						</optgroup> 
						<optgroup label="Professional"> 
							<option value="12"{if $event_field['type'] eq '12'} selected{/if}>Fundraiser</option> 
							<option value="13"{if $event_field['type'] eq '13'} selected{/if}>Professional event/networking</option> 
							<option value="14"{if $event_field['type'] eq '14'} selected{/if}>Meeting</option> 
							<option value="15"{if $event_field['type'] eq '15'} selected{/if}>Club</option> 
							<option value="16"{if $event_field['type'] eq '16'} selected{/if}>Conference</option> 
						</optgroup> 
					</select>
					<p class="message-error" id="eventErr">{$error['type']}</p>
				</dd>
				<dt>
					<label for="event-perms-1">Event Permissions</label>
				</dt>
				<dd>
					<label for="event_is_public_yes_create">
						<p><input type="radio" name="is_public" value="1"{if $event_field.permissions eq '1'} checked="checked"{/if} id="event_is_public_yes_create" /> Anyone can sign up and invite others</p>
					</label> 
					<label for="event_is_public_no_create">
						<p><input type="radio" name="is_public" value="0"{if $event_field.permissions eq '0'} checked="checked"{/if} id="event_is_public_no_create" /> Only people you invite can attend</p>
					</label>
					<p class="message-error" id="pubErr">{$error['pub']}</p>
				</dd>
			</dl>
			<footer class="buttons-submit">
				<p><input type="submit" name="submit" value="Begin" id="event_create" /></p> 
			</footer> 
		</fieldset>
		</form>
	</div>
