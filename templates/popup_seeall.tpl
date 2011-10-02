<div class="popup-container" id="see-all">
	<div class="popup block">
		<header class="block-title">
			<h1>Who's coming?</h1>
		</header>
		<ul class="thumbs">{foreach $attending as $guest}

			<li>
				<figure>
					<a href="{$CURHOST}/user/{$guest->id}">
						<img src="{$guest->pic}" width="64px" height="64px" alt="{$guest->fname} {$guest->lname}" />
						<figcaption>{$guest->fname} {$guest->lname}</figcaption>
					</a>
				</figure>
			</li>{/foreach}

		</ul>
		<p class="popup-close"><a href="#">X</a></p>
	</div>
</div>