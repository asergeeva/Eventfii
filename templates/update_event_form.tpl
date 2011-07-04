<div id="private">
	<div id="main">
		<div id="container">
			<nav class="event-options">
				<ol>
					<li class="optn"><a href="manage?eventId={$eventInfo['id']}">Manage</a></li>
					<li class="optn optn-current"><a href="edit?eventId={$eventInfo['id']}">Edit</a></li>
					<li class="optn"><a href="event/{$eventInfo['id']}" target="_blank">Preview</a></li>
				</ol>
			</nav>
			<fieldset class="section-form">
				<legend>Edit Event</legend>
				<dl class="column">
					<dt><label for="event_title_update">What are you planning?</label> <span>Name of Event</span></dt>
					<dd><input type="text" class="inputbox" name="event_title_update" value="{$eventInfo['title']}" id="event_title_update" /></dd>
					<dt><label for="event_description_update">Brief Description</label> <span>What is your event about?</span></dt>
					<dd><input type="text" class="inputbox" name="event_description_update" value="{$eventInfo['description']}" id="event_description_update" /></dd>
					<dt><label for="event_address_update">Where</label> <span>Ex: 1234 Maple St, Los Angeles, CA 90007</span></dt>
					<dd><input type="text" class="inputbox" name="event_address_update" value="{$eventInfo['location_address']}" id="event_address_update" /></dd>
					<dt><label for="event_date_update">When</label> <span>Ex: May 14, 2011</span></dt>
					<dd><input type="text" class="inputbox" name="event_date_update" value="{$eventDate}" id="event_date_update" /></dd>
					<dt><label for="event_time_update">What Time</label> <span>Ex: 5:00 pm</span></dt>
					<dd><input type="text" class="inputbox" name="event_time_update" value="{$eventTime}" id="event_time_update" /></dd>
				</dl>
				<dl class="column">
					<dt><label for="event_goal_update">Attandance Goal</label> <span>In # of Attendees</span></dt>
					<dd><input type="text" class="inputbox" name="event_goal_update" value="{$eventInfo['goal']}" id="event_goal_update" /></dd>
					<dt><label for="event_deadline_update">Deadline to sign up</label> <span>Last day for anyone to reserve a spot</span></dt>
					<dd>	<input type="text" class="inputbox" name="event_deadline_update" value="{$eventInfo['event_deadline']}" id="event_deadline_update" /></dd>
          <dt>
            <label for="event_type_update">Event Type</label> 
            <span></span>
          </dt>
          <dd>
            <select id="event_type_update">
              <optgroup label="Personal">
                <option value="1" {$eventType['t1']}>Birthday</option>
                <option value="2" {$eventType['t2']}>Other party</option>
                <option value="3" {$eventType['t3']}>Dinner</option>
                <option value="4" {$eventType['t4']}>Social gathering</option>
                <option value="5" {$eventType['t5']}>Shared travel/trip</option>
                <option value="6" {$eventType['t6']}>Wedding related</option>
              </optgroup>
              <optgroup label="Educational">
                <option value="7" {$eventType['t7']}>Club meetup</option>
                <option value="8" {$eventType['t8']}>Educational event</option>
                <option value="9" {$eventType['t9']}>Recruiting/career</option>
                <option value="10" {$eventType['t10']}>School-sponsored event</option>
                <option value="11" {$eventType['t11']}>Greek</option>
              </optgroup>
              <optgroup label="Professional">
                <option value="12" {$eventType['t12']}>Fund raiser</option>
                <option value="13" {$eventType['t13']}>Professional event/networking</option>
                <option value="14" {$eventType['t14']}>Meeting</option>
                <option value="15" {$eventType['t15']}>Club</option>
                <option value="16" {$eventType['t16']}>Conference</option>
              </optgroup>
            </select>
					</dd>
					<dt><label for="event_ispublic_update">Event Permissions</label></dt>
					<dd><label for="event_ispublic_yes_update"><input type="radio" name="event_ispublic_update" id="event_ispublic_yes_update" {$isEventPublic} value="1" /> <span>Anyone can sign up and invite others</span></label> <label for="event_ispublic_no_update"><input type="radio" name="event_ispublic_update" id="event_ispublic_no_update" {$isEventPrivate} value="0" /> <span>Only people you invite can attend</span></label></dd>
					<dt><label for="event-media">Upload Picture</label> <span>Link or Browse</span></dt>
					<dd class="media">
            <div id="update-file-uploader">       
                <noscript>          
                    <p>Please enable JavaScript to use file uploader.</p>
                    <!-- or put a simple form for upload here -->
                </noscript>         
            </div>
          </dd>
          <dt><label for="event_url_update">URL</label></dt>
					<dd><input type="text" name="event_url_update" id="event_url_update" disabled="disabled" value="{$CURHOST}/event/{$eventInfo['id']}" /></dd>
          <dt>
            <div class="event_guest">
              <a href="#update"><img src="{$IMG_PATH}/addguests.png" id="update_event_guest_invite" rel="#event_guest_invite_overlay" /></a>
            </div>
          </dt>
				</dl>
				<div class="submit-buttons">
					<input type="submit" class="btn-update" id="event_update" value="Update" />
				</div>
			</fieldset>
		</div>
	</div>
</div>