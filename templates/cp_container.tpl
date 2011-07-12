<div class="section">
  <section class="block">
    <h1 class="block-title">Personal Profile</h1>
    {if $userInfo['pic'] eq ''}
      <a href="#" class="info-pic"><img class="info-pic" src="{$CURHOST}/images/default_thumb.jpg" alt="{$userInfo['fname']} {$userInfo['lname']}" width="200px" height="150px" /></a>
    {else}
      <a href="#" class="info-pic"><img class="info-pic" src="{$CURHOST}/upload/user/{$userInfo['pic']}" alt="{$userInfo['fname']} {$userInfo['lname']}" width="200px" height="150px" /></a>
    {/if}
   
    <!-- p><a href="#" class="btn-small"><span>Update</span></a></p -->
	<p><div id="user_image" style="position:relative; left:20px;"></p>       
    <noscript>          
        <p>Please enable JavaScript to use file uploader.</p>
        <!-- or put a simple form for upload here -->
    </noscript>         
	</div>
    <p class="info-about">We love to organize events!</p>
    <p class="info-website">www.truersvp.com</p>
    <p><a href="#" class="btn-small"><span>Edit</span></a></p>
  </section>
</div>
<div class="section">
  <section class="block">
    <h1 class="block-title">Events Attended</h1>
    {include file="event_attending.tpl"}
  </section>
</div>
<div class="section">
  <section class="block">
    <h1 class="block-title">Events Hosted</h1>
    {include file="event_created.tpl"}
  </section>
</div>
