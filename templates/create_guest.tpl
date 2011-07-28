				<header class="block-title">
					<h1>Add Guests</h1>
				</header>
				<form method="post" action="{$CURHOST}/create/guests">
				<fieldset>
					<ol>
						<li>
            	<span>Facebook</span>
              <div id="fb-root"></div>
  <script src="http://connect.facebook.net/en_US/all.js#xfbml=1"></script>
              <fb:send href="{$EVENT_URL}/{$eventInfo->eid}"></fb:send>
            </li>
						<li>
            	<span>Add from address book</span>
              <p><a href="#gmail" class="event_invite_oi">Gmail</a> 
              <a href="#yahoo" class="event_invite_oi">Yahoo!</a></p>
            </li>
						<li>
							<label for="emails">
								<span>Enter e-mails separated by a comma</span>
								<textarea class="inputbox autowidth" id="emails" name="emails" /></textarea>
							</label>
						</li>
						<li>
            	<span>Upload a CSV file</span>
              <span id="eventid">{$eventInfo->eid}</span>
            	<p><a href="#" id="csv_upload"><span>Upload</span></a></p>
            </li>
					</ol>
				</fieldset>
				<footer class="buttons buttons-submit">
					<p><input type="submit" name="submit" value="Done" /></p>
				</footer>
				</form>
