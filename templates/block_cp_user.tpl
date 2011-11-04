<section class="block" id="user-pic">
				<p class="user-img">
					<a href="{$CURHOST}/user/a/{$smarty.session.user->alias}" class="info-pic"><img id="user_pic" src="{$smarty.session.user->pic}?type=large" alt="{$smarty.session.user->fname} {$smarty.session.user->lname}" /></a>
				</p>{if isset($page.settings)}

				<footer class="buttons buttons-extra">
					<a href="#" class="btn btn-manage" id="user_image"><span>Upload</span></a>
				</footer>
			</section>
			<section class="block" id="user-desc">
				<p class="user-info edit" id="user-bio">{if $smarty.session.user->about}{$smarty.session.user->about}{else}Click here to edit{/if}</p>{/if}

			</section>