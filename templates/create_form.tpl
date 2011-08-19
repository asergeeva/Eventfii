<div class="form" id="event_create">
					<form method="post" action="{$CURHOST}/event/create">
					<fieldset>
						<legend>Create Event</legend> 
						<dl class="column"> 
							<dt>
								<label for="title">What are you planning?</label> 
								<em>Name of Event</em>
							</dt> 
							<dd>
								<input type="text" name="title" value="{$event_field.title|escape:'htmlall'}" class="inputbox autowidth" id="title" />{if isset($error.title)}

								<p class="message-error">{$error.title}</p>{/if}

							</dd> 
							<dt>
								<label for="description">Event Details</label> 
								<em>What should your guests know?</em>
							</dt>
							<dd>
								<textarea name="description" class="inputbox autowidth" id="description">{$event_field.description|escape:'htmlall'}</textarea>{if isset($error.desc)}

								<p class="message-error">{$error.desc}</p>{/if}

							</dd>
							<dt>
								<label for="location">Where</label>
								<em>Ex: Jim's House</em>
							</dt>
							<dd>
								<input type="text" name="address" value="{$event_field.location|escape:'htmlall'}" class="inputbox autowidth" id="location" />{if isset($error.location)}

								<p class="message-error">{$error.location}</p>{/if}

							</dd>
							<dt>
								<label for="address">Address</label> 
								<em>Ex: 1234 Maple St, Los Angeles, CA 90007</em>
							</dt> 
							<dd>
								<input type="text" name="address" value="{$event_field.address|escape:'htmlall'}" class="inputbox autowidth" id="address" />{if isset($error.address)}

								<p class="message-error">{$error.address}</p>{/if}

							</dd>
							<dt>
								<label for="date">When</label> 
								<em>Date &amp; Time</em>
							</dt> 
							<dd>
								<p><input type="text" name="date" value="{$event_field.date}" class="inputbox" id="date" /> <input type="text" name="time" value="{$event_field.time}" class="inputbox" id="time" /></p>{if isset($error.date)}

								<p class="message-error">{$error.date}</p>{/if}{if isset($error.time)}

								<p class="message-error">{$error.time}</p>{/if}

							</dd>
						</dl> 
						<dl class="column"> 
							<dt>
								<label for="goal">Attendance Goal</label> 
								<em>In # of Attendees</em>
							</dt>
							<dd>
								<input type="text" name="goal" value="{$event_field.goal|escape:'htmlall'}" class="inputbox autowidth" id="goal" />{if isset($error.goal)}

								<p class="message-error">{$error.goal}</p>{/if}

							</dd>
							<dt>
								<label for="deadline">Deadline to sign up</label> 
								<em>Last day for anyone to reserve a spot</em>
							</dt>
							<dd>
								<input type="text" name="deadline" value="{$event_field.deadline|escape:'htmlall'}" class="inputbox autowidth" id="deadline" />{if isset($error.deadline)}

								<p class="message-error">{$error.deadline}</p>{/if}

							</dd>
							<dt> 
								<label for="type">Event Type</label>
							</dt>
							<dd>
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

								<p class="message-error">{$error.type}</p>{/if}

							</dd>
							<dt>
								<label>Event Permissions</label>
							</dt>
							<dd>
								<label for="is_public_yes">
									<p><input type="radio" name="is_public" value="1"{if $event_field.permissions eq '1'} checked="checked"{/if} id="is_public_yes" /> Anyone can sign up and invite others</p>
								</label> 
								<label for="is_public_no">
									<p><input type="radio" name="is_public" value="0"{if $event_field.permissions eq '0'} checked="checked"{/if} id="is_public_no" /> Only people you invite can attend</p>
								</label>{if isset($error.pub)}

								<p class="message-error" id="pubErr">{$error.pub}</p>{/if}

							</dd>
						</dl>
						<footer class="buttons buttons-submit">
							<p><input type="submit" name="submit" value="Begin" id="event_create" /></p> 
						</footer> 
					</fieldset>
					</form>
				</div>
