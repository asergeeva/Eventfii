<div class="form" id="create_event_form">
			<section class="block">
				<fieldset>
					<legend>Edit Event</legend>
						<dl class="column"> 		
							<dt>
								<label for="event_title_update">What are you planning?</label> 
								<em>Name of Event</em>
							</dt>
							<dd><input type="text" class="inputbox autowidth" name="event_title_update" value="{$eventInfo['title']}" id="event_title_update" /></dd>
							<dt>
								<label for="event_description_update">Brief Description</label> 
								<em>What is your event about?</em>
							</dt>
							<dd><input type="text" class="inputbox autowidth" name="event_description_update" value="{$eventInfo['description']}" id="event_description_update" /></dd>
							<dt>
								<label for="event_address_update">Where</label> 
								<em>Ex: 1234 Maple St, Los Angeles, CA 90007</em>
							</dt>
							<dd><input type="text" class="inputbox autowidth" name="event_address_update" value="{$eventInfo['location_address']}" id="event_address_update" /></dd>
							<dt>
								<label for="event_date_update">When</label> 
								<em>Ex: May 14, 2011</em>
							</dt>
							<dd><input type="text" class="inputbox autowidth" name="event_date_update" value="{$eventDate}" id="event_date_update" /></dd>
							<dt>
								<label for="event_time_update">What Time</label> 
								<em>Ex: 12:30 PM</em>
							</dt>
							<dd><input type="text" class="inputbox autowidth" name="event_time_update" value="{date("g:i A", strtotime(eventTime))}" id="event_time_update" /></dd>
						</dl> 
						<dl class="column"> 
							<dt>
								<label for="event_goal_update">Attendance Goal</label> 
								<em>In # of Attendees</em>
							</dt>
							<dd><input type="text" class="inputbox autowidth" name="event_goal_update" value="{$eventInfo['goal']}" id="event_goal_update" /></dd>
							<dt>
								<label for="event_deadline_update">Deadline to sign up</label> 
								<em>Last day for anyone to reserve a spot</em>
							</dt>
							<dd>	<input type="text" class="inputbox autowidth" name="event_deadline_update" value="{$eventInfo['event_deadline']}" id="event_deadline_update" /></dd>
							<dt><label for="event_type_update">Event Type</label></dt>
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
										<option value="12" {$eventType['t12']}>Fundraiser</option>
										<option value="13" {$eventType['t13']}>Professional event/networking</option>
										<option value="14" {$eventType['t14']}>Meeting</option>
										<option value="15" {$eventType['t15']}>Club</option>
										<option value="16" {$eventType['t16']}>Conference</option>
									</optgroup>
								</select>
							</dd> 
							<dt><label for="event_ispublic_update">Event Permissions</label></dt>
							<dd>
								<label for="event_ispublic_yes_update">
									<p><input type="radio" name="event_ispublic_update" id="event_ispublic_yes_update" {$isEventPublic} value="1" /> Anyone can sign up and invite others</p>
								</label> 
								<label for="event_ispublic_no_update">
									<p><input type="radio" name="event_ispublic_update" id="event_ispublic_no_update" {$isEventPrivate} value="0" /> Only people you invite can attend</p>
								</label>
							</dd>
							<dt>
								<label for="event-media">Upload Picture</label> 
								<em>Link or Browse</em>
							</dt> 
							<dd class="media">
								<div id="update-file-uploader" style="display:none;">       
									<noscript>          
										<p>Please enable JavaScript to use file uploader.</p>
										<!-- or put a simple form for upload here -->
									</noscript>         
								</div>
							</dd>
							<!-- dt><label for="event_url_update">URL</label></dt>
							<dd><input type="text" class="inputbox autowidth" name="event_url_update" id="event_url_update" disabled="disabled" value="http://localhost/eventfii/event/1" /></dd-->
							<dt>
								<div class="event_guest">
									<a href="#edit"><img src="http://localhost/eventfii/images/addguests.png" id="update_event_guest_invite" rel="#event_guest_invite_overlay" /></a>
								</div>
							</dt>
						</dl> 
						<footer class="buttons-submit">
							<input type="hidden" name="event_url_update" id="event_url_update" disabled="disabled" value="{$CURHOST}/event/{$eventInfo['id']}" />
							<input type="hidden" name="invite_guest_click_counter" id="invite_guest_click_counter" disabled="disabled" value="0" />
							<input type="submit" id="event_update" value="Update" /> 
						</footer>
				</fieldset>
			</section>
		</div>
