<div class="popup-container" id="rsvp-multiple">
	<div class="popup block">
		<header class="block-title">
			<h1>Please complete your RSVP!</h1>
		</header>
		<p class="popup-close"><a href="#">X</a></p>
		<fieldset>
			<p class="message">Your RSVP of <select name="rsvp-type"><option value="1">Absolutely</option><option value="2">Pretty Sure</option><option value="3">50/50</option><option value="4">Not Likely</option><option value="5">Raincheck</option></select> plus <select name="rsvp-number"><option value="0">0</option><option value="1">1</option><option value="2">2</option><option value="3">3</option><option value="4">4</option><option value="5">5</option><option value="6">6</option></select> will be sent to {$event->organizer->fname}</p>
			<dl>
				<dt class="inline">Guest names</dt>
				<dd>
					<p><input type="text" class="inputbox" /></p>
					<p><input type="text" class="inputbox" /></p>
				</dd>
				<footer class="buttons buttons-send">
					<p><a href="#" class="btn btn-small"><span>RSVP</span></a></p>
				</footer>
			</dl>
		</fieldset>
	</div>
</div>
