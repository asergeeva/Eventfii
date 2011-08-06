<fieldset>
  <label for="send-automatically">
    <strong></strong>
    <div>
      <input type="checkbox" name="automatically" /> Send automatically on <input type="text" class="inputbox datebox" id="send-automatically" /> at <select class="timebox"></select>
    </div>
  </label>
  <label for="mail_to">
    <strong>To:</strong>
    <div>
      <input type="text" class="inputbox autowidth" name="mail_to" value="All Attendees" id="mail_to" />
    </div>
  </label>
  <label for="subject">
    <strong>Subject:</strong>
    <div>
      <input type="text" class="inputbox autowidth" name="subject" value="Event Name --- Time --- trueRSVP" id="subject" />
    </div>
  </label>
  <label for="message">
    <strong>Message:</strong>
    <div>
      <textarea class="autowidth" name="message" id="message">Hi [First Name]!

I would like to remind you that [Event name] that's happening on [Time]. Please go to trueRSVP at the link below to update your RSVP!

- [Host Name]</textarea>
    </div>
  </label>
</fieldset>