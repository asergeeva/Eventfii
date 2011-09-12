{if ! isset($smarty.get.option) && ! isset($page.addcontacts) && ! isset($page.addguests)}

		<header class="block notification">
			<p class="message">Congrats, your event has been created! Add guests now or come back to this step later.</p>
		</header>{elseif strlen($error) > 0}

		<header class="block error">
			<p class="message">{$error}</p>
		</header>{else if strlen($notification) > 0}

		<header class="block notification">
			<p class="message">{$notification}</p>
		</header>{/if}

		<section class="block">
			<nav class="horizontal-nav">
				<ul>{if !isset($page.addcontacts)}
					<li><a href="?{if isset($event)}eventId={$event->eid}&amp;{/if}option=trueRSVP" class="btn btn-manage{if ! isset($smarty.get.option) || $smarty.get.option == 'trueRSVP'} current{/if}"><span>trueRSVP Contacts</span></a></li>{/if}

					<li><a href="?{if isset($event)}eventId={$event->eid}&amp;{/if}option=manual" class="btn btn-manage{if $smarty.get.option == 'manual' || ($page.addcontacts && ! isset($smarty.get.option))} current{/if}"><span>Manually Add</span></a></li>
					<li><a href="?{if isset($event)}eventId={$event->eid}&amp;{/if}option=fb" class="btn btn-manage{if $smarty.get.option == 'fb'} current{/if}"><span>Add from Facebook</span></a></li>
					<li><a href="?{if isset($event)}eventId={$event->eid}&amp;{/if}option=csv" class="btn btn-manage{if $smarty.get.option == 'csv'} current{/if}"><span>Import CSV</span></a></li>
					<li><a href="?{if isset($event)}eventId={$event->eid}&amp;{/if}option=import" class="btn btn-manage{if $smarty.get.option == 'import'} current{/if}"><span>Gmail/Yahoo Import</span></a></li>
				</ul>
			</nav>{if ( ! isset($smarty.get.option) || $smarty.get.option == 'trueRSVP' ) && ! isset($page.addcontacts)}

			<section class="block">{if isset($contacts)}

				<header class="block-title">
					<h1>Contacts</h1>
				</header>
				<div id="search-container">Search: <div id="contacts-header"></div></div>
				<ul class="contacts-list" id="contacts-list">{foreach $contacts as $contact}

					<li>
						<label for="contact-{$contact->id}">{if isset($addButton)}
						
							<input type="checkbox" id="contact-{$contact->id}" value="{$contact->email}" class="selected_contact" />{/if}

							<img src="{$contact->pic}" width="36px" height="36px" alt="{$contact->email}" />{if isset($contact->fname)}
							<h3>{$contact->fname} {$contact->lname}</h3>{/if}
							<p>{$contact->email}</p>
						</label>
					</li>{/foreach}
					
				</ul>
				<form method="post" action="{$submitTo}" id="create_guests">
				<textarea name="emails" id="emails-hidden" class="inputbox autowidth" style="display:none"></textarea>
				<p><span class="btn btn-med"><input type="submit" name="submit" id="add_import_contact_list" value="Invite" /></span></p>
				</form>
				{else}

				<header class="block error">
					<p class="message">No contacts</p>
				</header>
				<footer class="message">
					<p>Use the options above to add guests to your event. Guests added to your event will automatically be added to your trueRSVP contact list. You can also <a href="{$CURHOST}/contacts/add">add guests</a> to your contact list through your control panel.</p>
				</footer>{/if}

			</section>{elseif $smarty.get.option == 'manual' || ($page.addcontacts && ! isset($smarty.get.option))}

			<section class="block">
				<form method="post" action="{$submitTo}" id="create_guests">
					<fieldset>
						<legend>Manually add guests</legend>
						<label>Enter e-mails separated by a comma</label>
						<textarea name="emails" class="inputbox autowidth"></textarea>
						<footer class="buttons buttons-submit"> 
							<p><span class="btn btn-med"><input type="submit" name="submit" value="Invite" /></span></p>
						</footer>
					</fieldset>
				</form>
			</section>{elseif $smarty.get.option == 'fb'}{*

			<div id="fb-root"></div>
			<script src="http://connect.facebook.net/en_US/all.js#xfbml=1"></script>
			<fb:send href="{$EVENT_URL}/a/{$event->alias}?gref={$smarty.get.gref}"></fb:send>*}

			<fb:serverFbml width="582">
			<script type="text/fbml">
			  <fb:fbml>
				  <fb:request-form action="{$CURHOST}/fb/invite" target="_top" method="POST" invite="true" type="event" content="{$smarty.session.user->fname} invites you to {$event->title}.<fb:req-choice url='{if isset($event->alias)}{$EVENT_URL}/a/{$event->alias}{if $smarty.get.gref neq ''}?gref={$smarty.get.gref}{/if}{else}{$CURHOST}{/if}' label='Accept' />">
				  <fb:multi-friend-selector showborder="false" actiontext="{if isset($event->title)}Invite to {$event->title}{else}{$smarty.session.user->fname} added you as a contact at trueRSVP{/if}" cols="3" max="35">
				</fb:request-form>
			  </fb:fbml>
			</script>
			</fb:serverFbml>{elseif $smarty.get.option == 'csv'}
				<p><a href="#" id="csv_upload"><span>Upload</span></a></p>
			{else}
			<div id="oi_container">
				<h2>Import Contacts</h2>
				<input type="hidden" name="oi_provider" id="oi_provider" value="{$provider}" />
				<table>
			  	<tr>
			    	<th>Email:</th>
			      <td><input type="text" name="oi_email" id="oi_email" /></td>
			   	</tr>
			    <tr>
			    	<th>Password:</th>
			      <td><input type="password" name="oi_pass" id="oi_pass" /></td>
			    </tr>
			    <tr>
			    	<th></th>
			      <td><a href="#search-container" id="oi_import">Import</a></td>
			    </tr>
			  </table>
			</div>
			{/if}

			<footer class="buttons buttons-submit">
				<p><a href="{$finishSubmit}&submit=true" class="btn btn-med"><span>Finish</span></a>{if sizeof($signedUp) == 1} <a href="{$CURHOST}/$event/a/{$event->alias}?created=true">Skip this step</a>{/if}</p>
			</footer>
		</section>