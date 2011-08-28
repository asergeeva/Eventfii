<section class="block"> 
					<header class="block-title"> 
						<h1>Invited Guests</h1> 
					</header>{if isset($signedUp)}

					<fieldset>					
					<ul class="contacts-list">{foreach $signedUp as $guest name=contacts}

						<li>
							<label for="contact-{$smarty.foreach.contacts.index}">
								<!--input type="checkbox" id="contact-{$smarty.foreach.contacts.index}" checked="checked" /-->
								<img src="{$guest->pic}" width="36px" height="36px" alt="{$guest->fname} {$guest->lname}" />
								<h3>{$guest->fname} {$guest->lname}</h3>
								<p>{$guest->email}</p>
							</label>
						</li>{/foreach}

					</ul>
					<!--footer class="buttons buttons-submit">
						<p><input type="submit" name="submit" value="Update Guest List" id="guests_update" /></p> 
					</footer-->
					</fieldset>{/if}

				</section>