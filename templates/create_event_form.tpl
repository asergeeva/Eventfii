<div class="form" id="create_event_form">
		<fieldset>
			<legend>Create Event</legend> 
			<dl class="column"> 
				<dt>
					<label for="event_title_create">What are you planning?</label> 
					<em>Name of Event</em>
				</dt> 
				<dd>
					<input type="text" class="inputbox autowidth" name="event_title_create" value="{$smarty.post.title|escape:'htmlall'}" id="event_title_create" /> 
		  			<p class="message-error" id="titleErr"></p>
		  		</dd> 
				<dt>
					<label for="event_description_create">Brief Description</label> 
					<em>What is your event about?</em>
				</dt> 
				<dd>
					<input type="text" class="inputbox autowidth" name="event_description_create" id="event_description_create" value="{$smarty.post.description|escape:'htmlall'}" /> 
		  			<p class="message-error" id="descErr"></p>
		  		</dd>
				<dt>
					<label for="addresspicker">Where</label> 
					<em>Ex: 1234 Maple St, Los Angeles, CA 90007</em>
				</dt> 
				<dd>
					<input type="text" class="inputbox autowidth" name="event_address_create" id="addresspicker" value="{$smarty.post.address|escape:'htmlall'}" /> 
		  			<p class="message-error" id="addrErr"></p>
		  		</dd>
				<dt>
					<label for="event_date_create">When</label> 
					<em>Ex: 05/14/2011 (MM/DD/YYYY)</em>
				</dt> 
				<dd>
					<input type="text" class="inputbox autowidth" name="event_date_create" id="event_date_create" value="{$smarty.post.date}" /> 
		  			<p class="message-error" id="dtErr"></p>
		  		</dd>
				<dt>
					<label for="event_time_create">What Time</label> 
					<em>Ex: 12:30 PM (12 hour clock)</em>
				</dt> 
				<dd>
					<input type="text" class="inputbox autowidth" name="event_time_create" id="event_time_create" value="{$smarty.post.time|escape:'htmlall'}" /> 
		  			<p class="message-error" id="timeErr"></p>
		  		</dd>
			</dl> 
			<dl class="column"> 
				<dt>
					<label for="event_goal_create">Attandance Goal</label> 
					<em>In # of Attendees</em>
				</dt>
				<dd>
					<input type="text" class="inputbox autowidth" name="event_goal_create" id="event_goal_create" value="{$smarty.post.goal|escape:'htmlall'}" /> 
		  			<p class="message-error" id="goalErr"></p>
		  		</dd>
				<dt>
					<label for="event_deadline_create">Deadline to sign up</label> 
					<em>Last day for anyone to reserve a spot</em>
				</dt>
				<dd>
					<input type="text" class="inputbox autowidth" name="event_deadline_create" id="event_deadline_create" value="{$smarty.post.deadline}" /> 
		  			<p class="message-error" id="deadlineErr"></p>
		  		</dd>
				<dt> 
					<label for="event_type_create">Event Type</label>
				</dt>
				<dd>
					<select id="event_type_create"> 
						<option value="0">Please Select</option> 
							<optgroup label="Personal"> 
							<option value="1" {if $smarty.post.type eq '1'}selected{/if}>Birthday</option> 
							<option value="2" {if $smarty.post.type eq '2'}selected{/if}>Other party</option> 
							<option value="3" {if $smarty.post.type eq '3'}selected{/if}>Dinner</option> 
							<option value="4" {if $smarty.post.type eq '4'}selected{/if}>Social gathering</option> 
							<option value="5" {if $smarty.post.type eq '5'}selected{/if}>Shared travel/trip</option> 
							<option value="6" {if $smarty.post.type eq '6'}selected{/if}>Wedding related</option> 
						</optgroup> 
						<optgroup label="Educational"> 
							<option value="7" {if $smarty.post.type eq '7'}selected{/if}>Club meetup</option> 
							<option value="8" {if $smarty.post.type eq '8'}selected{/if}>Educational event</option> 
							<option value="9" {if $smarty.post.type eq '9'}selected{/if}>Recruiting/career</option> 
							<option value="10" {if $smarty.post.type eq '10'}selected{/if}>School-sponsored event</option> 
							<option value="11" {if $smarty.post.type eq '11'}selected{/if}>Greek</option> 
						</optgroup> 
						<optgroup label="Professional"> 
							<option value="12" {if $smarty.post.type eq '12'}selected{/if}>Fundraiser</option> 
							<option value="13" {if $smarty.post.type eq '13'}selected{/if}>Professional event/networking</option> 
							<option value="14" {if $smarty.post.type eq '14'}selected{/if}>Meeting</option> 
							<option value="15" {if $smarty.post.type eq '15'}selected{/if}>Club</option> 
							<option value="16" {if $smarty.post.type eq '16'}selected{/if}>Conference</option> 
						</optgroup> 
					</select>
					<p class="message-error" id="eventErr"></p>
				</dd>
				<dt>
					<label for="event-perms-1">Event Permissions</label>
				</dt>
				<dd>
					<label for="event_is_public_yes_create">
						<p><input type="radio" name="event_ispublic_create" value="1" checked="checked" {if $smarty.post.is_public eq '1'}checked="checked"{/if} id="event_is_public_yes_create" /> Anyone can sign up and invite others</p>
					</label> 
					<label for="event_is_public_no_create">
						<p><input type="radio" name="event_ispublic_create" value="0" {if $smarty.post.is_public eq '0'}checked="checked"{/if} id="event_is_public_no_create" /> Only people you invite can attend</p>
					</label>
					<p><div class="error_message" id="pubErr"></div></p>
				</dd>
				<dt>
					<label for="event-media">Upload Picture</label>
					<em>Link or Browse</em>
				</dt>
				<dd class="media">
					<div id="create-file-uploader" style="display:none;">       
						<noscript>          
							<p>Please enable JavaScript to use file uploader.</p>
							<!-- or put a simple form for upload here -->
						</noscript>         
					</div>
				</dd>
				<!--dt>
					<label for="event_url_create">URL</label>
				</dt>
				<dd>
					<input type="hidden" class="inputbox autowidth" name="event_url_create" id="event_url_create" readonly="readonly" value="http://localhost/eventfii/event/2" />
				</dd-->
			</dl>
			<input type="hidden" class="inputbox autowidth" name="event_url_create" id="event_url_create" readonly="readonly" value="{$CURHOST}/event/{$maxEventId}" />
			<footer class="buttons-submit">
				<!--a href="#"><span>Add Guests</span></a-->
				<a href="#create"><img src="{$IMG_PATH}/addguests.png" id="create_event_guest_invite" rel="#event_guest_invite_overlay" /></a>
				<p class="btn-large"><input type="submit" value="Begin" id="event_create" /></p> 
				<p class="message-error" id="success"></p>
			</footer> 
		</fieldset>
	</div>
