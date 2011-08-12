<div id="event_invite_guest_fb">
  <h2 class="event_form_section_header">Facebook:</h2>
  <div id="fb-root"></div>
  <script src="http://connect.facebook.net/en_US/all.js#xfbml=1"></script>
  <fb:send href="{$event->url}"></fb:send>
</div>
<div id="event_invite_guest_email">
  <h2 class="event_form_section_header">Email:</h2>
  <ul id="event_invite_guest_oi">
    <li><a href="#gmail" class="event_invite_oi">Gmail</a></li>
    <li><a href="#yahoo" class="event_invite_oi">Yahoo!</a></li>
  </ul>
  <h3 class="event_form_section_subheader">Separated by comma</h3>
  <textarea name="guest_email" id="guest_email"></textarea>
</div>
<div class="big_vertical_divisor">OR</div>
