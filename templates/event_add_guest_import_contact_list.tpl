								<div id="search-container">Search: <div id="contacts-header"></div></div>
								<ul class="contacts-list" id="contacts-list">{foreach from=$contactList key=email item=name}
									<li>
										<label for="contact-{$email}">
											<input type="checkbox" id="contact-{$email}" value="{$email}" class="selected_contact" />
											<img src="{$CURHOST}/images/default_thumb.jpg" width="36px" height="36px" alt="User" />
											<h3>{$name}</h3>
											<p>{*<a href="#/{$email}/">*}{$email}{*</a>*}</p>
										</label>
									</li>{/foreach}
								</ul>
								<form method="post" action="{$submitTo}" id="create_guests">
									<textarea name="emails" id="emails-hidden" style="display:none"></textarea>
									<footer class"buttons buttons-submit">
										<p><span class="btn btn-med"><input type="submit" name="submit" value="Invite" id="add_import_contact_list" /></span></p>
									</footer>
								</form>
