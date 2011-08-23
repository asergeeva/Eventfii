<section class="block">
				<header class="block-title">
					<h1>Add Guests</h1>
				</header>
				<form method="post" action="{$CURHOST}{$submitTo}">
				<fieldset>
					<ol>
						<li>
							<span>Facebook</span>
							<div id="fb-root"></div>
							<script src="http://connect.facebook.net/en_US/all.js#xfbml=1"></script>
							<fb:send href="{$EVENT_URL}/{$smarty.session.manage_event->eid}"></fb:send>
						</li>
						<li>
							<span>Add from address book</span>
							<p><a href="#gmail" class="event_invite_oi">Gmail</a> 
							   <a href="#yahoo" class="event_invite_oi">Yahoo!</a>
							   <a href="#truersvp" class="event_invite_oi">trueRSVP</a></p>
              				<div class="dropdown" id="oi_container"></div>
						</li>
						<li>
							<label for="emails">
								<span>Enter e-mails separated by a comma</span>
								<textarea class="inputbox autowidth" id="emails" name="emails" /></textarea>
							</label>
						</li>
						<li>
							<span>Upload a CSV file</span>
							<p><a href="#" id="csv_upload"><span>Upload</span></a></p>
						</li>
					</ol>
				</fieldset>
				<footer class="buttons buttons-submit">
					<p><input type="submit" name="submit" value="Done" /></p>
				</footer>
				</form>
			</section>