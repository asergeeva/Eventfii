<section class="block"> 
					<header class="block-title"> 
						<h1>Invited Guests</h1> 
					</header>{if isset($guests)}

					<fieldset>					
						<ul class="contacts-list">{foreach $guests as $guest name=guests}

							<li>
								<label for="contact-{$smarty.foreach.guests.index}">{*
									<input type="checkbox" id="contact-{$smarty.foreach.contacts.index}" checked="checked" />*}
									<img src="{$guest->pic}?type=square" width="36px" height="36px" alt="{$guest->name}" />
									<h3>{$guest->name}</h3>
									<p>{$guest->friendly_cid}</p>
								</label>
							</li>{/foreach}
							
							{*if isset($invitedFBUsers)}
								{foreach $invitedFBUsers as $guest name=contacts}
	
								<li>
									<label for="contact-{$smarty.foreach.contacts.index}">{*
										<input type="checkbox" id="contact-{$smarty.foreach.contacts.index}" checked="checked" />}
										<img src="http://graph.facebook.com/{$guest['fb_id']}/picture" width="36px" height="36px" alt="{$guest['fb_id']}" />
										<h3>{$guest['fb_name']}</h3>
										<p>Facebook contact</p>
									</label>
								</li>{/foreach}
							
							{/if*}

						</ul>{*
					<footer class="buttons buttons-submit">
						<p><input type="submit" name="submit" value="Update Guest List" id="guests_update" /></p> 
					</footer>*}
					</fieldset>{else}

					<p>No one has been invited for this event.</p>{/if}

				</section>
