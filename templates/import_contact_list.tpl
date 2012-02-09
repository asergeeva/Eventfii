            {if isset($oi_filter)}
            {else}
            <p style="float: right" id="rightPannel"><a href="#" id="select_contact_all">Select all</a> | <a href="#" id="select_contact_none">Select none</a></p>
            <div id="search-container">Search: <input type="text" id="filterSearch" name="filterSearch" value="{if isset($oi_filter)}{$oi_filter}{/if}" class="inputbox" style="width:200px;"  /><div id="contacts-header"></div></div><div id="contacts-list-filter">
            {/if}
            
								{if ($contactList)} 
                  <ul class="user-list" id="contacts-list">
                    {foreach from=$contactList key=email item=name}
                    <li>
                      <label for="contact-{$email}">
                        <input type="checkbox" id="contact-{$email}" value="{$email}" class="selected_contact contact-email" />
                        <img src="{$CURHOST}/images/default_thumb.jpg" width="36px" height="36px" alt="User" />
                        <h3>{$name}</h3>
                        <p>{*<a href="#/{$email}/">*}{$email}{*</a>*}</p>
                      </label>
                    </li>
                    {/foreach}
                  </ul>
                 <script> enableInviteButtton();</script>
                {else}<span style='color:#c00; font-size:16px; display:block; margin:20px 0 10px; text-align:center;'>No record match</span>
                <script>
                	disableInviteButtton();
							  </script>  
               {/if}
                <input type="hidden" id="oi_email_filter" name="oi_email_filter" value="{$oi_email}" />
                <input type="hidden" id="oi_pass_filter" name="oi_pass_filter" value="{$oi_pass}" />
                <input type="hidden" id="oi_provider_filter" name="oi_provider_filter" value="{$oi_provider}" />
             {if isset($oi_filter)}
            {else} </div>{/if}