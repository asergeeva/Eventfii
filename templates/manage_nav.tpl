<div class="navigation">
			<nav class="block" id="cp-nav">
				<ul>
					<li{$page["addguests"]}><a href="{$CURHOST}/event/manage/guests?eventId={$eventInfo['id']}"><span>Add more guests</span></a></li>
					<li{$page["email"]}><a href="{$CURHOST}/event/manage/email?eventId={$eventInfo['id']}"><span>E-mail current guests</span></a></li>
					<li{$page["text"]}><a href="{$CURHOST}/event/manage/text?eventId={$eventInfo['id']}"><span>Sent text to guests</span></a></li>
				</ul>
			</nav>
			<footer class="links-extra">
				<p><a href="{$CURHOST}">Back to home</a></p>
			</footer>
		</div>
