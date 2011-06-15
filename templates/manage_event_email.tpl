<div id="manage_event_email">
	<div id="manage_event_email_tabs">
    <ul>
      <li><a href="#reminder_form">Reminder</a></li>
      <li><a href="#followup_form">Follow Up</a></li>
      <li><a href="#attendance_form">Attendance</a></li>
    </ul>
    <div id="reminder_form">
      <div id="reminder_auto_send_container">
      <input type="checkbox" id="reminder_auto_send_cb" />
      Send automatically on: <input type="text" id="reminder_auto_send_date" />
      at <input type="text" id="reminder_auto_send_time" />
      </div>
      <div id="reminder_message_container">
      <table>
        <tr>
          <th>To:</th>
          <td>
          <select>
          <option>All</option>
          </select>
          </td>
        </tr>
        <tr>
          <th>Subject:</th>
          <td><input type="text" id="reminder_message_subject" /></td>
        </tr>
        <tr>
          <th>Content:</th>
          <td><textarea id="reminder_message_text"></textarea></td>
        </tr>
      </table>
      </div>
    </div>
    <div id="followup_form">
      <div id="followup_auto_send_container">
      <input type="checkbox" id="followup_auto_send_cb" />
      Send automatically on: <input type="text" id="followup_auto_send_date" />
      at <input type="text" id="followup_auto_send_time" />
      </div>
      <div id="followup_message_container">
      <table>
        <tr>
          <th>To:</th>
          <td> 
          <select>
          <option>All</option>
          </select>
          </td>
        </tr>
        <tr>
          <th>Subject:</th>
          <td><input type="text" id="followup_message_subject" /></td>
        </tr>
        <tr>
          <th>Content:</th>
          <td><textarea id="followup_message_text"></textarea></td>
        </tr>
      </table>
      </div>
    </div>
    <div id="attendance_form">
      <div id="attendance_auto_send_container">
      <input type="checkbox" id="attendance_auto_send_cb" />
      Send automatically on: <input type="text" id="attendance_auto_send_date" />
      at <input type="text" id="attendance_auto_send_time" />
      </div>
      <div id="attendance_message_container">
      <table>
        <tr>
          <th>To:</th>
          <td> 
          <select>
          <option>All</option>
          </select>
          </td>
        </tr>
        <tr>
          <th>Subject:</th>
          <td><input type="text" id="attendance_message_subject" /></td>
        </tr>
        <tr>
          <th>Content:</th>
          <td><textarea id="attendance_message_text"></textarea></td>
        </tr>
      </table>
      </div>
    </div>
	</div>
</div>