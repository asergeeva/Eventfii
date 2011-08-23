			<section class="block">
				{if isset($contacts)}
				<ul class="contacts-list">
					{foreach $contacts as $contact}
					<li>
						<label for="contact-{$contact->id}">
							<input type="checkbox" id="contact-{$contact->id}" value="{$contact->email}" class="selected_contact" />
							<img src="{$contact->pic}" width="36px" height="36px" alt="{$contact->email}" />{if isset($contact->fname)}
							<h3>{$contact->fname} {$contact->lname}</h3>{/if}
							<p>{$contact->email}</p>
						</label>
					</li>
					{/foreach}
				</ul>{else}
				<header class="block">
					<p class="message">No contacts</p>
				</header>
				{/if}
			</section>
			{if isset($addButton)}
			<p class="message"><a href="#" id="add_import_contact_list">Add</a></p>
			{/if}
