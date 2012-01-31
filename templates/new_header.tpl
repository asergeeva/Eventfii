<div id="fb-root"></div>
<header id="page-header">
    <h1 id="logo"><a href="{$CURHOST}">trueRSVP</a></h1>
    <aside>                
        <p class="buttons buttons-create"><a class="btn btn-small" href="{$CURHOST}/event/create"><span>New Event</span></a></p>
        {if isset($smarty.session.user)}
            <span class="login_info">
            <span class="login_info_inr">
                <span class="img_box"><img src="{$event->organizer->pic}" width="30" height="30" alt="" /></span>
                <span class="user_name">{$event->organizer->fname} {$event->organizer->lname}</span>
                <div class="img_arow">
                    <dl style="" class="dropdown">
                       <dt>
                       <a class="" id="linkglobal" style="cursor: pointer;"></a></dt>
                        <dd>
                            <ul style="display: none;" id="ulglobal">
                                <li><a href="{$CURHOST}">Home</a></li>
                                <li><a href="{$CURHOST}/method">How Does It Work?</a></li>
                                <li><a href="{$CURHOST}/logout">Sign out</a></li>
                          </ul>
                       </dd>
                    </dl>
                </div>
            </span>
        </span>
        {/if}
    </aside>
</header>