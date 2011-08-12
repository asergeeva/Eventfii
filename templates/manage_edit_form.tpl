<div class="form" id="create_event_form">
			<section class="block">
				<form method="post" action="{$CURHOST}/event/manage/edit?eventId={$smarty.get.eventId}">
				<fieldset>
					<legend>Edit Event</legend>
						<dl class="column"> 		
							<dt>
								<label for="event_title_update">What are you planning?</label> 
								<em>Name of Event</em>
							</dt>
							<dd>
                                <input type="text" class="inputbox autowidth" name="title" value="{$event_field.title}" id="event_title_update" />{if isset($error.title)}
								<p class="message-error" id="titleErr">{$error.title}</p>{/if}
                            </dd>
							<dt>
								<label for="event_description_update">Brief Description</label> 
								<em>What is your event about?</em>
							</dt>
							<dd>
                                    <input type="text" class="inputbox autowidth" name="description" value="{$event_field.description}" id="event_description_update" />{if isset($error.desc)}
									<p class="message-error" id="descErr">{$error.desc}</p>{/if}
                            </dd>
							<dt>
								<label for="event_address_update">Where</label> 
								<em>Ex: 1234 Maple St, Los Angeles, CA 90007</em>
							</dt>
							<dd>
                                <input type="text" class="inputbox autowidth" name="address" value="{$event_field.address}" id="event_address_update" />{if isset($error.address)}
								<p class="message-error" id="addrErr">{$error.address}</p>{/if}
                            </dd>
							<dt>
								<label for="event_date_update">When</label> 
								<em>Ex: May 14, 2011</em>
							</dt>
							<dd>
                            	<input type="text" class="inputbox autowidth" name="date" value="{$event_field.date}" id="event_date_update" />{if isset($error.date)}
								<p class="message-error" id="dtErr">{$error.date}</p>{/if}
                            </dd>
							<dt>
								<label for="event_time_update">What Time</label> 
								<em>Ex: 12:30 PM</em>
							</dt>
							<dd>
                                <input type="text" class="inputbox autowidth" name="time" value="{date("g:i A", strtotime($event_field.time))}" id="event_time_update" />{if isset($error.time)}
								<p class="message-error" id="timeErr">{$error.time}</p>{/if}
                        	</dd>
						</dl> 
						<dl class="column"> 
							<dt>
								<label for="event_goal_update">Attendance Goal</label> 
								<em>In # of Attendees</em>
							</dt>
							<dd>
                                <input type="text" class="inputbox autowidth" name="goal" value="{$event_field.goal}" id="event_goal_update" />{if isset($error.goal)}
								<p class="message-error" id="goalErr">{$error.goal}</p>{/if}
                            </dd>
							<dt>
								<label for="event_deadline_update">Deadline to sign up</label> 
								<em>Last day for anyone to reserve a spot</em>
							</dt>
							<dd>
                                <input type="text" class="inputbox autowidth" name="deadline" value="{$event_field.deadline}" id="event_deadline_update" />{if isset($error.deadline)}
								<p class="message-error" id="deadlineErr">{$error.deadline}</p>{/if}
                            </dd>
							<dt><label for="event_type_update">Event Type</label></dt>
							<dd>
								<select name="type" id="event_type_update"> 
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
								<p class="message-error" id="eventErr">{$error.type}</p>{/if}
							</dd> 
							<dt><label for="event_ispublic_update">Event Permissions</label></dt>
							<dd>
								<label for="event_ispublic_yes_update">
									<p><input type="radio" name="is_public" value="1"{if $event_field.is_public eq '1'} checked="checked"{/if} id="event_ispublic_yes_update" /> Anyone can sign up and invite others</p>
								</label> 
								<label for="event_ispublic_no_update">
									<p><input type="radio" name="is_public" value="0"{if $event_field.is_public eq '0'} checked="checked"{/if} id="event_ispublic_no_update" /> Only people you invite can attend</p>
								</label>{if isset($error.pub)}
								<p class="message-error" id="pubErr">{$error.pub}</p>{/if}
							</dd>
						</dl> 
						<footer class="buttons-submit">
							<input type="hidden" name="location_lat" value="$event_field.location_lat" />
							<input type="hidden" name="location_long" value="$event_field.location_long" />
							<p><input type="submit" name="submit" id="event_update" value="Update" /></p>
						</footer>
				</fieldset>
				</form>
			</section>
		</div>