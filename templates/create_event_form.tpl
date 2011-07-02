<div id="create_event_form">
	<span id="create_event_eventid">{$maxEventId}</span>
  <h3>Create Event</h3>
  <div id="left_event_form">
    <div class="event_field">
      <h2 class="event_form_section_header">What are you planning?</h2>
      <h3 class="event_form_section_subheader">Name of event</h3>
      <input type="text" name="event_title_create" id="event_title_create" value="{$smarty.post.title|escape:'htmlall'}" />
	 	  <p><span style="color:red; top:0px; font-weight:bold;">{$error_title|escape:'htmlall'}</span></p>
    </div>
    <div class="event_field">
      <h2 class="event_form_section_header">Brief description</h2>
      <h3 class="event_form_section_subheader">What is this event about?</h3>
      <input type="text" name="event_description_create" id="event_description_create" value="{$smarty.post.description|escape:'htmlall'}" />
	   <p><span style="color:red; top:0px; font-weight:bold;">{$error_desc|escape:'htmlall'}</span></p>
    </div>
    <div class="event_field">
      <h2 class="event_form_section_header">Where</h2>
      <h3 class="event_form_section_subheader">Ex: 1234 Maple St, Los Angeles, CA 90007</h3>
      <input type="text" name="event_address_create" id="event_address_create" value="{$smarty.post.address|escape:'htmlall'}" />
	  <p><span style="color:red; top:0px; font-weight:bold;">{$error_address|escape:'htmlall'}</span></p>
    </div>
    <div class="event_field">
      <h2 class="event_form_section_header">When</h2>
      <h3 class="event_form_section_subheader">Ex: 05/14/2011 (MM/DD/YYYY)</h3>
      <input type="text" name="event_date_create" id="event_date_create" value="{$smarty.post.date}" />
	  <p><span style="color:red; top:0px; font-weight:bold;">{$error_dt}</span></p>
    </div>
    <div class="event_field">
      <h2 class="event_form_section_header">What time</h2>
      <h3 class="event_form_section_subheader">Ex: 21:10 (HH:MM)</h3>
      <input type="text" name="event_time_create" id="event_time_create" value="{$smarty.post.time|escape:'htmlall'}" />
	  <p><span style="color:red; top:0px; font-weight:bold;">{$error_tm}</span></p>
    </div>
    <div class="event_field">
      <h2 class="event_form_section_header">Deadline to sign up</h2>
      <h3 class="event_form_section_subheader">Last day for anyone to reserve a spot</h3>
      <input type="text" name="event_deadline_create" id="event_deadline_create" value="{$smarty.post.deadline}" />
	   <p><span style="color:red; top:0px; font-weight:bold;">{$error_ddt}</span></p>
    </div>
    </div>
    <div id="right_event_form">
    <div class="event_field">
      <h2 class="event_form_section_header">Attendance goal</h2>
      <h3 class="event_form_section_subheader">In # of attendees</h3>
      <input type="text" name="event_goal_create" id="event_goal_create" value="{$smarty.post.goal|escape:'htmlall'}" />
	  <p><span style="color:red; top:0px; font-weight:bold;">{$error_goal|escape:'htmlall'}</span></p>
    </div>
    <div class="event_field_ta">
      <h2 class="event_form_section_header">Each paid spot will get</h2>
      <h3 class="event_form_section_subheader">What each attendee will get once the event goes through</h3>
      <textarea name="event_gets_create" id="event_gets_create">{$smarty.post.gets|escape:'htmlall'}</textarea>
    </div>
    <div class="event_field">
      <h2 class="event_form_section_header">Event type</h2>
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
    </div>
    <div class="event_field">
	
      <input type="radio" name="event_ispublic_create" id="event_ispublic_yes_create" {if $smarty.post.is_public eq '1'}checked="checked"{/if} value="1" />
      Anyone can sign up &amp; invite others
    </div>
    <div class="event_field">
      <input type="radio" name="event_ispublic_create" id="event_ispublic_no_create" {if $smarty.post.is_public eq '0'}checked="checked"{/if} value="0" />
      Only people you invite can attend
	 <p><span style="color:red; top:0px; font-weight:bold;">{$error_is_pub}</span></p>
    </div>
    <div class="event_field_pic">
      <h2 class="event_form_section_header_radio">URL</h2>
      <input type="text" name="event_url_create" id="event_url_create" disabled="disabled" value="{$CURHOST}/event/{$maxEventId}" />
    </div>
    <div class="event_field_pic">
      <h2 class="event_form_section_header_radio">Upload picture</h2>
      <div id="create-file-uploader">       
          <noscript>          
              <p>Please enable JavaScript to use file uploader.</p>
              <!-- or put a simple form for upload here -->
          </noscript>         
      </div>
    </div>
    <div class="event_guest">
      <a href="#"><img src="{$EC_IMG_PATH}/addguests.png" id="event_guest_invite" rel="#event_guest_invite_overlay" /></a>
    </div>
    <a href="#" id="event_create" class="goButton">Go</a>
  </div>
</div>