<div id="create_event_form">
<span id="create_event_eventid">{$maxEventId}</span>
	<!-- BEGIN Add Guest Section
	<div class="event_guest">
	  <a href="#"><img src="{$EC_IMG_PATH}/addguests.png" id="event_guest_invite" rel="#event_guest_invite_overlay" /></a>
	</div>
	<div id="create_event_form">
		<span id="create_event_eventid">{$maxEventId}</span>
	</div>
	END Add Guest Section-->
	<fieldset class="section-form">
		<legend>Create Event</legend>
		<dl class="column">
			<dt>
				<label for="event_title_create">What are you planning?</label> 
				<span>Name of Event</span>
			</dt>
			<dd><input type="text" class="inputbox" name="event_title_create" value="{$smarty.post.title|escape:'htmlall'}" id="event_title_create" />
      		<p><div class="error_message" id="titleErr"></div></p></dd>
			<dt>
				<label for="event_description_create">Brief Description</label> 
				<span>What is your event about?</span>
			</dt>
			<dd><input type="text" class="inputbox" name="event_description_create" id="event_description_create" value="{$smarty.post.description|escape:'htmlall'}" />
      		<p><div class="error_message" id="descErr"></div></p></dd>
			<dt>
				<label for="event_address_create">Where</label> 
				<span>Ex: 1234 Maple St, Los Angeles, CA 90007</span></dt>
			<dd><input type="text" class="inputbox" name="event_address_create" id="event_address_create" value="{$smarty.post.address|escape:'htmlall'}" />
      		<p><div class="error_message" id="addrErr"></div></p></dd>
			<dt>
				<label for="event_date_create">When</label> 
				<span>Ex: 05/14/2011 (MM/DD/YYYY)</span>
			</dt>
			<dd><input type="text" class="inputbox" name="event_date_create" id="event_date_create" value="{$smarty.post.date}" />
      		<p><div class="error_message" id="dtErr"></div></p></dd>
			<dt>
				<label for="event_time_create">What Time</label> 
				<span>Ex: 21:00 (HH:MM)</span>
			</dt>
			<dd><input type="text" class="inputbox" name="event_time_create" id="event_time_create" value="{$smarty.post.time|escape:'htmlall'}" />
      		<p><div class="error_message" id="timeErr"></div></p></dd>
		</dl>
		<dl class="column">
			<dt>
				<label for="event_goal_create">Attandance Goal</label> 
				<span>In # of Attendees</span>
			</dt>
			<dd><input type="text" class="inputbox" name="event_goal_create" id="event_goal_create" value="{$smarty.post.goal|escape:'htmlall'}" />
      		<p><div class="error_message" id="goalErr"></div></p></dd>
			<dt>
				<label for="event_deadline_create">Deadline to sign up</label> 
				<span>Last day for anyone to reserve a spot</span>
			</dt>
			<dd><input type="text" class="inputbox" name="event_deadline_create" id="event_deadline_create" value="{$smarty.post.deadline}" />
      		<p><div class="error_message" id="deadlineErr"></div></p></dd>
			<dt>
				<label for="event_type_create">Event Type</label> 
				<span></span>
			</dt>
			<dd>
				<select id="event_type_create"> 
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
					  <option value="12" {if $smarty.post.type eq '12'}selected{/if}>Fund raiser</option> 
					  <option value="13" {if $smarty.post.type eq '13'}selected{/if}>Professional event/networking</option> 
					  <option value="14" {if $smarty.post.type eq '14'}selected{/if}>Meeting</option> 
					  <option value="15" {if $smarty.post.type eq '15'}selected{/if}>Club</option> 
					  <option value="16" {if $smarty.post.type eq '16'}selected{/if}>Conference</option> 
					</optgroup> 
				</select>
			</dd>
			<dt>
				<label for="event-perms-1">Event Permissions</label>
			</dt>
			<dd>
				<label for="event_is_public_yes_create">
					<input type="radio" name="event_ispublic_create" value="1" checked="checked" {if $smarty.post.is_public eq '1'}checked="checked"{/if} id="event_is_public_yes_create" /> 
					<span>Anyone can sign up and invite others</span>
				</label> 
				<label for="event_is_public_no_create">
					<input type="radio" name="event_ispublic_create" value="0" {if $smarty.post.is_public eq '0'}checked="checked"{/if} id="event_is_public_no_create" /> 
					<span>Only people you invite can attend</span>
				</label>
				<p><div class="error_message" id="pubErr"></div></p>
			</dd>
			<dt>
				<label for="event-media">Upload Picture</label> 
				<span>Link or Browse</span>
			</dt>
 			<!--
			<input type="text" name="event_url_create" id="event_url_create" disabled="disabled" value="{$CURHOST}/event/{$maxEventId}" />
			-->
			<dd class="media">
      	<div id="create-file-uploader">       
          <noscript>          
              <p>Please enable JavaScript to use file uploader.</p>
              <!-- or put a simple form for upload here -->
          </noscript>         
      	</div>
      </dd>
			<dt><label for="event_url_create">URL</label></dt>
			<dd><input type="text" name="event_url_create" id="event_url_create" disabled="disabled" value="{$CURHOST}/event/{$maxEventId}" /></dd>
      <dt>
        <div class="event_guest">
          <a href="#create"><img src="{$IMG_PATH}/addguests.png" id="create_event_guest_invite" rel="#event_guest_invite_overlay" /></a>
        </div>
      </dt>
		</dl>
		<div class="submit-buttons">
			<p class="btn-large"><input type="submit" value="Begin" id="event_create" /></p>
			<p><div class="error_message" id="success"></div></p></dd>
		</div>
	</fieldset>
</div>