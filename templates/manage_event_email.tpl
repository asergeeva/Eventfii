<div id="main"> 
		<div id="container"> 
			<div class="section section-email"> 
				<section class="block"> 
					<h1 class="block-title">E-Mail Notifications</h1> 
					<form method="post" action=""> 
						<fieldset id="email"> 
							<label for="mail_to"><span>To:</span> <input type="text" class="inputbox" name="mail_to" value="All Attendees" id="mail_to" /></label> 
							<label for="subject"><span>Subject:</span> <input type="text" class="inputbox" name="subject" value="Event Name --- Time --- trueRSVP" id="subject" /></label> 
							<label for="message"><span>Message:</span> <textarea class="inputbox" name="message" id="message">Hi (First Name)!
 
I would like to remind you that {Event name} that’s happening on {Time}. Please go to trueRSVP at the link below to update your RSVP!
 
- {Host Name}</textarea></label> 
						<ul class="submit-buttons"> 
							<li><a href="#" class="btn"><span>Update</span></a></li> 
							<li class="btn btn-submit"><input type="Submit" value="Send Now" /></li> 
						</ul> 
						</fieldset> 
					</form> 
				</section> 
			</div> 
			<aside class="section-extra"> 
				<ul> 
					<li><a href="#" class="btn"><span>Reminder Email</span></a></li> 
					<li><a href="#" class="btn"><span>Thank You Email</span></a></li> 
					<li><a href="#" class="btn"><span>Blank Template</span></a></li> 
				</ul> 
			</aside> 
		</div> 
	</div> 


<div id="manage_event_email">
	<div id="manage_event_email_tabs">
    <ul>
      <li><a href="#reminder_form">Reminder</a></li>
      <li><a href="#followup_form">Follow Up</a></li>
    </ul>
    <div id="reminder_form">
      <div id="reminder_auto_send_container">
      <input type="checkbox" id="reminder_auto_send_cb" {$eventReminder['isAuto']} />
      Send automatically on: <input type="text" id="reminder_auto_send_date" /> at
       <select id="reminder_auto_send_time">
        <option>23:00</option>
        <option>22:00</option>
        <option>21:00</option>
        <option>20:00</option>
        <option>19:00</option>
        <option>18:00</option>
        <option>17:00</option>
        <option>16:00</option>
        <option>15:00</option>
        <option>14:00</option>
        <option>13:00</option>
        <option>12:00</option>
        <option>11:00</option>
        <option>10:00</option>
        <option>09:00</option>
        <option>08:00</option>
        <option>07:00</option>
        <option>06:00</option>
        <option>05:00</option>
        <option>04:00</option>
        <option>03:00</option>
        <option>02:00</option>
        <option>01:00</option>
      </select>      
      </div>
      <div id="reminder_message_container">
        <table>
        <tr>
          <th>To:</th>
          <td>
          <select id="reminder_recipient">
          	<option>All</option>
          </select>
          </td>
        </tr>
        <tr>
          <th>Subject:</th>
          <td><input type="text" id="reminder_message_subject" value="{$eventReminder['subject']}" /></td>
        </tr>
        <tr>
          <th>Content:</th>
          <td><textarea id="reminder_message_text">{$eventReminder['message']}</textarea></td>
        </tr>
        </table>
        <a href="#"><img src="{$EC_IMG_PATH}/save.png" id="save_reminder" /></a>
        <a href="#"><img src="{$IMG_PATH}/send_03.png" id="send_reminder" /></a>
        <span id="reminder_status"></span>
      </div>
    </div>
      <div id="followup_form">
        <div id="followup_auto_send_container">
        <input type="checkbox" id="followup_auto_send_cb" {$eventFollowup['isAuto']} />
        Send automatically on: <input type="text" id="followup_auto_send_date" /> at
        <select id="followup_auto_send_time">
          <option>23:00</option>
          <option>22:00</option>
          <option>21:00</option>
          <option>20:00</option>
          <option>19:00</option>
          <option>18:00</option>
          <option>17:00</option>
          <option>16:00</option>
          <option>15:00</option>
          <option>14:00</option>
          <option>13:00</option>
          <option>12:00</option>
          <option>11:00</option>
          <option>10:00</option>
          <option>09:00</option>
          <option>08:00</option>
          <option>07:00</option>
          <option>06:00</option>
          <option>05:00</option>
          <option>04:00</option>
          <option>03:00</option>
          <option>02:00</option>
          <option>01:00</option>
        </select>    
        </div>
        <div id="followup_message_container">
        <table>
          <tr>
            <th>To:</th>
            <td> 
            <select id="followup_recipient">
            <option>All</option>
            </select>
            </td>
          </tr>
          <tr>
            <th>Subject:</th>
            <td><input type="text" id="followup_message_subject" value="{$eventFollowup['subject']}" /></td>
          </tr>
          <tr>
            <th>Content:</th>
            <td><textarea id="followup_message_text">{$eventFollowup['message']}</textarea></td>
          </tr>
        </table>
        <a href="#"><img src="{$EC_IMG_PATH}/save.png" id="save_followup" /></a>
        <a href="#"><img src="{$IMG_PATH}/send_03.png" id="send_followup" /></a>
        <span id="followup_status"></span>
        </div>
      </div>
    </div>
	</div>
</div>
