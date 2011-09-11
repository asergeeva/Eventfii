<fieldset>
						<legend>Edit Event</legend> 
						<dl class="column"> 
							<dt>
								<label for="title">What are you planning?<span>*</span></label>
							</dt> 
							<dd{if isset($error.title)} class="error"{/if}>
								<input type="text" name="title" value="{if isset($event_field.title)}{$event_field.title}{else}Name of Event{/if}" class="inputbox{if ! isset($event_field.title)} default{/if}" id="title" />{if isset($error.title)}

								<em>{$error.title}</em>{/if}

							</dd>
							<dt>
								<label for="description">Event Details<span>*</span></label> 
							</dt>
							<dd{if isset($error.desc)} class="error"{/if}>
								<textarea name="description" class="inputbox{if ! isset($event_field.description)} default{/if}" id="description">{if isset($event_field.description)}{$event_field.description}{else}What should your guests know?{/if}</textarea>{if isset($error.desc)}

								<em>{$error.desc}</em>{/if}

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
								<label for="date">When<span>*</span></label> 
								<em>Date &amp; Time</em>
							</dt>
							<dd{if ( ! isset($event_field.date) or $event_field.date == "") || isset($error.time)} class="error"{/if}>
								<input type="text" name="date" value="{$event_field.date}" class="inputbox datebox" id="date" /> <select name="time" class="timebox id="time">{include file="timeselect.tpl" time=$event_field.time}</select>{if isset($error.date)}

								<em>{$error.date}</em>{/if}{if isset($error.time)}

								<em>{$error.time}</em>{/if}{if ! isset($event_field.end_date) or $event_field.end_date == ""}
								
								<p><a href="#" id="end-date">Add End Time</a></p>{/if}

							</dd>
							<dt id="add-end-time-title"{if ! isset($event_field.end_date) or $event_field.end_date == ""} style="display: none"{/if}>
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
								<label for="goal">Max attendance<span>*</span></label> 
							</dt>
							<dd{if isset($error.goal)} class="error"{/if}>
								<input type="text" name="goal" value="{if isset($event_field.goal)}{$event_field.goal}{else}In # of Attendees{/if}" class="inputbox{if ! isset($event_field.goal)} default{/if}" id="goal" />{if isset($error.goal)}

								<em>{$error.goal}</em>{/if}

							</dd>
							<dt>
								<label>What happens when you reach your goal?</label>
							</dt>
							<dd{if isset($error.pub)} class="error"{/if}>
								<label for="reach_goal_1">
									<p><input type="radio" name="reach_goal" value="1"{if $event_field.reach_goal eq '1' or ! isset($event_field.reach_goal)} checked="checked"{/if} id="reach_goal_1" /> Continue to allow RSVPs</p>
								</label> 
								<label for="reach_goal_2">
									<p><input type="radio" name="reach_goal" value="2"{if $event_field.reach_goal eq '2'} checked="checked"{/if} id="reach_goal_2" /> Don't allow anymore RSVPs</p>
								</label> 
								<!--label for="reach_goal_3">
									<p><input type="radio" name="reach_goal" value="3"{if $event_field.reach_goal eq '3'} checked="checked"{/if} id="reach_goal_3" /> Start a waitlist</p>
								</label-->{if isset($error.pub)}

								<em>{$error.pub}</em>{/if}

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

								<em>{$error.type}</em>{/if}

							</dd>
							<dt>
								<label>Event Permissions</label>
							</dt>
							<dd{if isset($error.pub)} class="error"{/if}>
								<label for="is_public_yes">
									<p><input type="radio" name="is_public" value="1"{if $event_field.is_public eq '1' or ! isset($event_field.is_public)} checked="checked"{/if} id="is_public_yes" /> Anyone can sign up and invite others</p>
								</label> 
								<label for="is_public_no">
									<p><input type="radio" name="is_public" value="0"{if $event_field.is_public eq '0'} checked="checked"{/if} id="is_public_no" /> Only people you invite can attend</p>
								</label>{if isset($error.pub)}

								<em>{$error.pub}</em>{/if}

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
						<footer class="buttons buttons-submit">{if isset($event_field.location_lat)}

							<input type="hidden" name="location_lat" value="{$event_field.location_lat}" />{/if}{if isset($event_field.location_long)}

							<input type="hidden" name="location_long" value="{$event_field.location_lat}" />{/if}

							<p><span class="btn btn-med"><input type="submit" name="submit" value="{if isset($page.edit)}Update{else}Next{/if}" /></span></p> 
						</footer> 
					</fieldset>
