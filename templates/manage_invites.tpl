<section class="block"> 
					<header class="block-title"> 
						<h1>Invited Guests</h1> 
					</header>{if isset($curSignUp)}<ul class="thumbs">{foreach $curSignUp as $guest}
					<li>
						<figure>
							<a href="{$CURHOST}/user/{$guest->id}">
								<img src="{$guest->pic}" width="64px" height="64px" alt="{$v['fname']} {$guest->lname}" />
								<figcaption>{$guest->fname} {$guest->lname}</figcaption>
							</a>
						</figure>
					</li>{/foreach}
					
				</ul>{/if}
				
				</section>