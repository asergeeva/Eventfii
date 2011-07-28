				<header class="block-title">
					<h1>Add Guests</h1>
				</header>
				<form method="post" action="{$CURHOST}/create/guests">
				<fieldset>
					<ol>
						<li><span>Facebook</span></li>
						<li><span>Add from address book</span></li>
						<li>
							<label for="emails">
								<span>Enter e-mails separated by a comma</span>
								<textarea class="inputbox autowidth" id="emails" /></textarea>
							</label>
						</li>
						<li><span>Upload a CSV file</span></li>
					</ol>
				</fieldset>
				<footer class="buttons buttons-submit">
					<p><input type="submit" name="submit" value="Done" /></p>
				</footer>
				</form>
