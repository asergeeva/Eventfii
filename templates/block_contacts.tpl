<section class="block">{if isset($contacts)}

				<header class="block-title">
					<h1>Contacts</h1>
				</header>
				<div id="search-container">Search: <div id="contacts-header"></div></div>
				<ul class="user-list">{foreach $contacts as $contact}

					<li>
						<label{if ! isset($addButton)}>{else} for="contact-{$contact->id}">						
							<input type="checkbox" id="contact-{$contact->id}" value="{$contact->email}" class="selected_contact" />{/if}

							<img src="{$contact->pic}?type=square" width="36" height="36" alt="{$contact->email}" />{if isset($contact->fname)}

							<h3>{$contact->fname} {$contact->lname}</h3>{/if}

							<span><a href="#/{$contact->email}/">{$contact->email}</a></span>
						</label>
					</li>{/foreach}

				</ul>{else}

				<header class="block error">
					<p class="message">No contacts</p>
				</header>{/if}

			</section>
