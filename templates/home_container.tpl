<div id="home_container">
  <div id="header">
  {include file="home_header_no_logo.tpl"}
  </div>
  <div id="middle_left">
    <div id="home_logo_container"><a href="{$CURHOST}"><img src="{$IMG_PATH}/profilepage4_15_03.png" id="ef_home_logo" /></a></div>
    <div id="create_event_container">
      <form id="create_event_home" name="create_event_home" method="post" action="home">
      <input id="create_event_textarea" name="eventTitle" value="What are you planning?"></input>
      <input type="image" name="event_submit_btn" id="event_submit_btn" src="images/fp/smallgo.png" />
      </form>
    </div>
  </div>
  <div id="middle">
  	<div id="middle_container">
      <div id="steps_container">
        <a href="#"><img src="{$IMG_PATH}/icons_03.png" alt="Start with an idea and a goal" /></a>
        <a href="#"><img src="{$IMG_PATH}/icons_05.png" alt="Get people to join in" /></a>
        <a href="#"><img src="{$IMG_PATH}/icons_07.png" alt="Goal reached, event is on!" /></a>
      </div>
      <div id="new_events">
        <h2>What's happening now?</h2>
        {foreach name=events item=event from=$newEvents}
        <div class="new_events_container">
          <h3><a href="{$CURHOST}/event/{$event['id']}">{$event['title']}</a></h3>
          <a href="{$CURHOST}/event/{$event['id']}">
          	<img src="{$IMG_UPLOAD}/{$event['id']}.jpg" style="width:300px;height:200px;" />
          </a>
        </div>
        {/foreach}
      </div>
    </div>
  </div>
  <div id="footer"></div>
</div>