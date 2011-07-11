<section class="block">
  <h1 class="block-title">Personal Profile</h1>
	{if $userInfo['pic'] eq ''}
		<a href="#" class="info-pic"><img class="info-pic" src="{$CURHOST}/upload/user/default_thumb.jpg" alt="{$userInfo['fname']} {$userInfo['lname']}" /></a>
	{else}
		<a href="#" class="info-pic"><img class="info-pic" src="{$CURHOST}/upload/user/{$userInfo['pic']}" alt="{$userInfo['fname']} {$userInfo['lname']}" /></a>
	{/if}
  <!--p class="info-about">Lover of music and world expert on camping at hippie music festivals</p>
  <p class="info-website">www.annasergeeva.com</p-->
</section>