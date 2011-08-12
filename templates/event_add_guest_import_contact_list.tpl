								<ul class="contacts-list">{foreach from=$contactList key=email item=name}
									<li>
										<label for="contact-{$email}">
											<input type="checkbox" id="contact-{$email}" value="{$email} />
											<img src="{$CURHOST}/images/default_thumb.jpg" width="36px" height="36px" alt="User" />
											<h3>{$name}</h3>
											<p>{$email}</p>
										</label>
									</li>{/foreach}
								</ul>
								<p class="message"><a href="#" id="add_import_contact_list">Add</a></p>