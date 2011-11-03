<p style="float: right"><a href="#" id="select_contact_all">Select all</a> | <a href="#" id="select_contact_none">Select none</a></p>
<div id="search-container">Search: <div id="contacts-header"></div></div>
								<ul class="user-list" id="contacts-list">
									{foreach from=$contactList key=email item=name}
									<li>
										<label for="contact-{$email}">
											<input type="checkbox" id="contact-{$email}" value="{$email}" class="selected_contact contact-email" />
											<img src="{$CURHOST}/images/default_thumb.jpg" width="36px" height="36px" alt="User" />
											<h3>{$name}</h3>
											<p>{*<a href="#/{$email}/">*}{$email}{*</a>*}</p>
										</label>
									</li>
									{/foreach}
								</ul>