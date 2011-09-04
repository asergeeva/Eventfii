<section class="block">
				<header class="block-title">
					<h1>Add Guests</h1>
				</header>
				<form method="post" action="{$CURHOST}{$submitTo}">
				<fieldset>
					<ol>
						<li>
							<span>Facebook</span>
							<fb:serverFbml>
						    <script type="text/fbml">
						      <fb:fbml>
						          <fb:request-form
						                    action="{$CURHOST}/fb/invite"
						                    target="_top"
						                    method="POST"
						                    invite="true"
						                    type="event"
						                    content="{$smarty.session.user->fname} invites you to {$event->title}.<fb:req-choice url='{$CURHOST}/event/a/{$event->alias}' label='Accept' />"
						                    >
						 					
						                    <fb:multi-friend-selector
						                    showborder="false"
						                    actiontext="Invite to {$event->title}" cols="3">
						        </fb:request-form>
						      </fb:fbml>
						    </script>
							</fb:serverFbml>
							<div id="fb-root"></div>
							<script src="http://connect.facebook.net/en_US/all.js#xfbml=1"></script>
							<fb:send href="{$EVENT_URL}/{$smarty.session.manage_event->eid}"></fb:send>
						</li>
						<li>
							<span>Add from address book</span>
							<p class="icons icons-full">
								<a href="#gmail" class="icon-gmail event_invite_oi">Gmail</a>
								<a href="#yahoo" class="icon-yahoo event_invite_oi">Yahoo!</a>
								<a href="#truersvp" class="icon-trueRSVP event_invite_oi">trueRSVP</a>
							</p>
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
					<p><span class="btn btn-med"><input type="submit" name="submit" value="Done" /></span></p>
				</footer>
				</form>
			</section>