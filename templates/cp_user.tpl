<aside class="extra">
			<section class="block" id="user-pic">
				<p class="user-img">
					<a href="{$CURHOST}/user/a/{$smarty.session.user->alias}" class="info-pic"><img id="user_pic" src="{if isset($smarty.session.user->pic)}{$smarty.session.user->pic}{else}https://graph.facebook.com/{$smarty.session.user->facebook}/picture?type=large{/if}" width="96px" height="96px" alt="{$smarty.session.user->fname} {$smarty.session.user->lname}" /></a>
				</p>{if isset($page.settings)}

				<footer class="buttons buttons-extra">
					<p><a href="#" id="user_image"><span>Upload</span></a></p>
				</footer>
			</section>
			<section class="block" id="user-desc">
				<p class="user-info edit">{if $smarty.session.user->about}{$smarty.session.user->about}{else}Click here to edit{/if}</p>
			</section>{else}

			</section>{/if}

		</aside>
