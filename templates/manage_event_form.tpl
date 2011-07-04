<div id="main">
		<div id="container">
      <nav class="event-options">
        <ol>
          <li class="optn optn-current"><a href="manage?eventId={$eventInfo['id']}">Manage</a></li>
          <li class="optn"><a href="edit?eventId={$eventInfo['id']}">Edit</a></li>
          <li class="optn"><a href="event/{$eventInfo['id']}" target="_blank">Preview</a></li>
        </ol>
      </nav>
      <aside class="section-extra">
        <section class="block">
          <h1 class="block-title">Your trueRSVP</h1>
          <p class="large">{$trsvpVal}</p>
        </section>
        <section class="block">
          <h1 class="block-title">Our Guestimate</h1>
          <p class="large">{$guestimate}</p>
        </section>
        <section class="block">
          <h1 class="block-title">Your Goal</h1>
          <p class="large">{$eventInfo['goal']}</p>
        </section>
      </aside>
      <div class="section section-primary">
        <section class="block">
          <h1 class="block-title">Tips</h1>
          <ol class="tips">
            <li>Invite enough guests so that "Our Guestimate" matches "Your Goal".</li>
            <li>As your guests begin to RSVP, "Your trueRSVP" number will increase towards "Your Goal"!</li>
          </ol>
        </section>
        <section class="block">
          <h1 class="block-title">Total RSVP</h1>
          <dl class="table">
            <dt>Response</dt>
            <dd>#</dd>
            <dt>I will absolutely be there</dt>
            <dd>{$guestConf1}</dd>
            <dt>I'm pretty sure i can make it</dt>
            <dd>{$guestConf2}</dd>
            <dt>I will make it if my schedule doesn't change</dt>
            <dd>{$guestConf3}</dd>
            <dt>I probably won't be able to make it</dt>
            <dd>{$guestConf4}</dd>
            <dt>Not attending, but show me as a supporter</dt>
            <dd>{$guestConf5}</dd>
            <dt>Not attending and not a supporter</dt>
            <dd>{$guestConf6}</dd>
            <dt>No Response</dt>
            <dd>{$guestNoResp}</dd>
          </dl>
        </section>
      </div>
      <aside class="section-extra">
        <ul>
          <li><a href="#manage" class="btn" id="update_event_guest_invite" rel="#event_guest_invite_overlay"><span>Invite More Guests</span></a></li>
          <li><a href="#" class="btn"><span>See RSVP List</span></a></li>
        </ul>
      </aside>
	</div>
</div>