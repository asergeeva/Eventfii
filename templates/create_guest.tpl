{include file="head.tpl"}
<body>

{include file="header.tpl"}
<div id="container">
	<nav class="steps step-{$step}" id="create-nav">
		<ol>
			<li>Basic info</li>
			<li>Preferences</li>
			<li>Add guests</li>
			<li>Success!</li>
		</ol>
	</nav>
	<div class="create">{if ! isset($smarty.get.option) && ! isset($page.addcontacts) && ! isset($page.addguests)}

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
				<ul>
					<li><a href="?{if isset($event)}eventId={$event->eid}&amp;{/if}option=trueRSVP" class="btn btn-manage{if ! isset($smarty.get.option) || $smarty.get.option == 'trueRSVP'} current{/if}"><span>trueRSVP Contacts</span></a></li>
					<li><a href="?{if isset($event)}eventId={$event->eid}&amp;{/if}option=manual" class="btn btn-manage{if $smarty.get.option == 'manual'} current{/if}"><span>Manually Add</span></a></li>
					<li><a href="?{if isset($event)}eventId={$event->eid}&amp;{/if}option=fb" class="btn btn-manage{if $smarty.get.option == 'fb'} current{/if}"><span>Add from Facebook</span></a></li>
					<li><a href="?{if isset($event)}eventId={$event->eid}&amp;{/if}option=csv" class="btn btn-manage{if $smarty.get.option == 'csv'} current{/if}"><span>Import CSV</span></a></li>
					<li><a href="?{if isset($event)}eventId={$event->eid}&amp;{/if}option=import" class="btn btn-manage{if $smarty.get.option == 'import'} current{/if}"><span>Gmail/Yahoo Import</span></a></li>
				</ul>
			</nav>{if ! isset($smarty.get.option) || $smarty.get.option == 'trueRSVP'}

			{include file="contacts_invite.tpl"}{elseif $smarty.get.option == 'manual'}

			<section class="block">
				<form method="post" action="{$CURHOST}/event/create/guests?eventId={$event->eid}&amp;option=manual" id="create_guests">
					<fieldset>
						<legend>Manually add guests</legend>
						<label>Enter e-mails separated by a comma</label>
						<textarea name="emails" class="inputbox autowidth"></textarea>
						<footer class="buttons buttons-submit"> 
							<p><span class="btn btn-med"><input type="submit" name="submit" value="Add" /></span></p>
						</footer>
					</fieldset>
				</form>
			</section>{elseif $smarty.get.option == 'fb'}

			<!--div id="fb-root"></div>
			<script src="http://connect.facebook.net/en_US/all.js#xfbml=1"></script>
			<fb:send href="{$EVENT_URL}/a/{$event->alias}?gref={$smarty.get.gref}"></fb:send-->
			<fb:serverFbml width="600">
			<script type="text/fbml">
			  <fb:fbml>
				  <fb:request-form action="{$CURHOST}/fb/invite" target="_top" method="POST" invite="true" type="event" content="{$smarty.session.user->fname} invites you to {$event->title}.<fb:req-choice url='{if isset($event->alias)}{$EVENT_URL}/a/{$event->alias}{if $smarty.get.gref neq ''}?gref={$smarty.get.gref}{/if}{else}{$CURHOST}{/if}' label='Accept' />">
				  <fb:multi-friend-selector showborder="false" actiontext="{if isset($event->title)}Invite to {$event->title}{else}{$smarty.session.user->fname} added you as a contact at trueRSVP{/if}" cols="3" max="35">
				</fb:request-form>
			  </fb:fbml>
			</script>
			</fb:serverFbml>{elseif $smarty.get.option == 'csv'}

			{else}

			{/if}

		</section>
		{include file="manage_invites.tpl"}			<!--div id="oi_logo"></div>
			<div id="oi_container">
			{if $smarty.get.option eq 'fb'}
				<fb:serverFbml>
			    <script type="text/fbml">
			      <fb:fbml>
			          <fb:request-form action="{$CURHOST}/fb/invite" target="_top" method="POST" invite="true" type="event" content="{$smarty.session.user->fname} invites you to {$event->title}.<fb:req-choice url='{if isset($event->alias)}{$EVENT_URL}/a/{$event->alias}{if $smarty.get.gref neq ''}?gref={$smarty.get.gref}{/if}{else}{$CURHOST}{/if}' label='Accept' />">
					  <fb:multi-friend-selector showborder="false" actiontext="{if isset($event->title)}Invite to {$event->title}{else}{$smarty.session.user->fname} added you as a contact at trueRSVP{/if}" cols="3" max="35">

			        </fb:request-form>
			      </fb:fbml>
			    </script>
				</fb:serverFbml>
			{/if}
			</div>
			
			<form method="post" action="{$CURHOST}{$submitTo}">
			<label for="emails">
				<span>Enter e-mails separated by a comma</span>
				<textarea class="inputbox autowidth" id="emails" name="emails" /></textarea>
			</label>
			<footer class="buttons buttons-submit">
				<p><span class="btn btn-med"><input type="submit" name="submit" value="Done" /></span></p>
			</footer>
			</form>
		</section-->
		{*include file="manage_invites.tpl"*}
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		

<!--section class="block">
				<header class="block-title">
					<h1>Add Guests</h1>
				</header>
				<form method="post" action="{$CURHOST}{$submitTo}">
				<fieldset>
					<ol>
						<li>
							<span>Facebook</span>
							<fb:serverFbml>
						    <script type="text/fbml">
						      <fb:fbml>
						          <fb:request-form action="{$CURHOST}/fb/invite" target="_top" method="POST" invite="true" type="event" content="{$smarty.session.user->fname} invites you to {$event->title}.<fb:req-choice url='{$CURHOST}/event/a/{$event->alias}' label='Accept' />">
								  <fb:multi-friend-selector showborder="false" actiontext="Invite to {$event->title}" cols="3" max="35">
						        </fb:request-form>
						      </fb:fbml>	
						    </script>
							</fb:serverFbml>
							<div id="fb-root"></div>
							<script src="http://connect.facebook.net/en_US/all.js#xfbml=1"></script>
							<fb:send href="{$EVENT_URL}/{$smarty.session.manage_event->eid}"></fb:send>
						</li>
						<li>
							<span>Add from address book</span>
							<p class="icons icons-full">
								<a href="#gmail" class="icon-gmail event_invite_oi">Gmail</a>
								<a href="#yahoo" class="icon-yahoo event_invite_oi">Yahoo!</a>
								<a href="#truersvp" class="icon-trueRSVP event_invite_oi">trueRSVP</a>
							</p>
							<div class="dropdown" id="oi_container"></div>
						</li>
						<li>
							<label for="emails">
								<span>Enter e-mails separated by a comma</span>
								<textarea class="inputbox autowidth" id="emails" name="emails" /></textarea>
							</label>
						</li>
						<li>
							<span>Upload a CSV file</span>
							<p><a href="#" id="csv_upload"><span>Upload</span></a></p>
						</li>
					</ol>
				</fieldset>
				<footer class="buttons buttons-submit">
					<p><span class="btn btn-med"><input type="submit" name="submit" value="Done" /></span></p>
				</footer>
				</form>
			</section-->