{include file="head.tpl"}
<body>

{include file="admin/header.tpl"}
<div id="container">
	<header id="header">
        <nav style="float:left;">
            <ul>
                <li class="{if !isset($smarty.get.option)}current{/if}"><a href="{$CURHOST}/admin"><span>Administration</span></a></li>
                <li class="{if isset($smarty.get.option) && $smarty.get.option eq 'manageStock'}current{/if}"><a href="{$CURHOST}/admin?option=manageStock" id="update_event_edit"><span>Manage Stock</span></a></li>
                <li class="{if isset($smarty.get.option) && $smarty.get.option eq 'stock' || $smarty.get.option eq 'viewstock' || $smarty.get.option eq 'addstock'}current{/if}"><a href="{$CURHOST}/admin?option=stock" id="update_event_edit"><span>Manage Stock Photos</span></a></li>
            </ul>
        </nav>
    </header>
    <div style="clear:both;"></div>
	<section id="main">
    	{if !isset($smarty.get.option)}
            <div class="content" style="overflow-y:scroll;width:100%">
                <section class="block">
                    <table id="aggregation_stats">
                        <tr><th># of events:</th><td>{$num_events}</td></tr>
                        <tr><th># of users:</th><td>{$num_users}</td></tr>
                        <tr><th># of invites:</th><td>{$num_invites}</td></tr>
                    </table>
                    <table id="events_stats_container" style="width:100%; text-align:center">
                    <tr>
                        <th>{counter start=0 skip=1}</th>
                        <th>Event name</th>
                        <th>Created on</th>
                        <th>Date of event</th>
                        <th>Host name</th>
                        <th>Host email</th>
                        <th># of invites</th>
                        <th># of FB invites</th>
                        <th># of checked off</th>
                        <th>trueRSVP</th>
                    </tr>
                    {foreach $events as $event}
                    <tr>
                        <td>{counter}</td>
                        <td><a href="{$EVENT_URL}/a/{$event.url_alias}" target="_blank">{$event.title}</a></td>
                        <td>{$event.created}</td>
                        <td>{$event.event_datetime}</td>
                        <td>{$event.fname} {$event.lname}</td>
                        <td>{$event.email}</td>
                        <td>{$event.num_invites}</td>
                        <td>{$event.fb_invite}</td>
                        <td>{$event.num_checked}</td>
                        <td>{$event.truersvp}</td>
                    </tr>
                    {/foreach}
                    </table>
                </section>
            </div>
        {elseif isset($smarty.get.option) && !isset($smarty.get.type) && $smarty.get.option eq 'manageStock'}
        	<div class="content" style="overflow-y:scroll;width:100%">
                <section class="block">
                	<h1 style="font-size:24px;"><a href="{$CURHOST}/admin?option=manageStock&type=add">Add Stock</a></h1>
                    <span>{if isset($smarty.get.error)}{$smarty.get.error}{/if}</span>
                    <table id="events_stats_container" style="width:100%; text-align:center">
                    <tr>
                        <th>{counter start=0 skip=1}</th>
                        <th>Stock name</th>
                        <th>Edit</th>
                        <th>Delete</th>
                    </tr>
                    {foreach $stock as $single_stock}
                    <tr>
                    	<td>{counter}</td>
                        <td>{$single_stock['name']}</td>
                        <td><a href="{$CURHOST}/admin/?option=manageStock&type=edit&stockId={$single_stock['id']}">Edit</a></td>
                        <td><a href="{$CURHOST}/admin/?option=manageStock&type=del&stockId={$single_stock['id']}">Delete</a></td>
                    </tr>
                    {/foreach}
                    </table>
                </section>
            </div>
        {elseif isset($smarty.get.option) && isset($smarty.get.type) && $smarty.get.option eq 'manageStock' && $smarty.get.type eq 'add'}
        	<div class="content" style="overflow-y:scroll;width:100%">
                <section class="block">
                	<h1 style="font-size:24px;">Add Stock</h1>
                    <span>{if isset($smarty.get.error)}{$smarty.get.error}{/if}</span>
                    <form enctype="multipart/form-data" method="post">
                        <table id="events_stats_container" style="width:100%; text-align:center">
                        <tr>
                            <td>Stock Name</td>
                            <td align="left"><input type="text" name="stock_name" id="stock_name" /></td>
                        </tr>
                        <tr>
                        	<td colspan="2" align="left"><input type="submit" name="submit" id="" value="Save" /></td>
                        </tr>
                        </table>
                    </form>
                </section>
            </div>
        {elseif isset($smarty.get.option) && isset($smarty.get.type) && $smarty.get.option eq 'manageStock' && $smarty.get.type eq 'edit' && isset($smarty.get.stockId)}
        	<div class="content" style="overflow-y:scroll;width:100%">
                <section class="block">
                	<h1 style="font-size:24px;">Edit Stock</h1>
                    <span>{if isset($smarty.get.error)}{$smarty.get.error}{/if}</span>
                    <form enctype="multipart/form-data" method="post">
                        <table id="events_stats_container" style="width:100%; text-align:center">
                        <tr>
                            <td>Stock Name</td>
                            <td align="left"><input type="text" name="stock_name" id="stock_name" value="{$stockName}" /></td>
                        </tr>
                        <tr>
                        	<td colspan="2" align="left"><input type="submit" name="submit" id="" value="Save" /></td>
                        </tr>
                        </table>
                    </form>
                </section>
            </div>
		{elseif isset($smarty.get.option) && $smarty.get.option eq 'stock'}
        	<div class="content" style="overflow-y:scroll;width:100%">
                <section class="block">
                    <span>{if isset($smarty.get.error)}{$smarty.get.error}{/if}</span>
                    <table id="events_stats_container" style="width:100%; text-align:center">
                    <tr>
                        <th>{counter start=0 skip=1}</th>
                        <th>Stock name</th>
                        <th>Photos Count</th>
                        <th>View</th>
                        <th>Add</th>
                    </tr>
                    {foreach $stock as $single_stock}
                    <tr>
                    	<td>{counter}</td>
                        <td>{$single_stock['name']}</td>
                        <td>{$stockCount[$single_stock['id']]}</td>
                        <td><a href="{$CURHOST}/admin?option=viewstock&stockId={$single_stock['id']}">View Photos</a></td>
                        <td><a href="{$CURHOST}/admin?option=addstock&stockId={$single_stock['id']}">Add Photo</a></td>
                    </tr>
                    {/foreach}
                    </table>
                </section>
            </div>
        {elseif isset($smarty.get.option) && $smarty.get.option eq 'viewstock'}
        	<div class="content" style="overflow-y:scroll;width:100%">
            	<section class="block">
                    <span>{if isset($smarty.get.error)}{$smarty.get.error}{/if}</span>
                    <div id="content_zoom">
                    <table id="events_stats_container" style="width:100%; text-align:center">
                    {counter start=0 skip=1 assign="count"}
                    {if count($stockPhotos) eq 0}
                    	<td>No photo found.</td>
                    {else}
                    <tr>
                    	{foreach $stockPhotos as $stockPhoto}
                            <td>
                            	<div><a href="{$CURHOST}/upload/stock/{$stockPhoto['photo']}" id="example"><img src="{$CURHOST}/upload/stock/thumb/{$stockPhoto['thumb']}" width="185" id="{$count++}" /></a></div>
                                <div align="center">
                                	<p class="buttons buttons-create"><a href="{$CURHOST}/admin/?option=viewstock&stockId=1&delId={$stockPhoto['id']}" class="btn btn-small"><span>Delete Photo</span></a></p>
                                </div>
                            </td>
                            {if $count%3 eq 0}
                            	</tr>
                                <tr>
                            {/if}
                         {/foreach}
                    </tr>
                    {/if}
                    </table>
                    </div>
                </section>
            </div>
        {elseif isset($smarty.get.option) && $smarty.get.option eq 'addstock'}
        	<div class="content" style="overflow-y:scroll;width:100%">
            	<section class="block">
                    <span>{if isset($smarty.get.error)}{$smarty.get.error}{/if}</span>
                    <form enctype="multipart/form-data" method="post">
                        <table id="events_stats_container" style="width:100%; text-align:center">
                        <tr>
                            <td>Stock Image</td>
                            <td align="left"><input type="file" name="up_file" id="up_file" /></td>
                        </tr>
                        <tr>
                            <td>Stock Name</td>
                            <td align="left"><input type="text" name="" id="" value="{$stockName}" readonly /></td>
                        </tr>
                        <tr>
                        	<td colspan="2" align="left"><input type="submit" name="submit" id="" value="Save" /></td>
                        </tr>
                        </table>
                    </form>
                </section>
            </div>
        {/if}
	</section>
	<footer class="buttons" style="text-align:center">
		<p>This page should only be seen by trueRSVP employees.</p>
	</footer>
</div>
{include file="footer.tpl"}

{include file="js_global.tpl"}
{include file="js_cp.tpl"}
<script src="{$JS_PATH}/openinviter.js" type="text/javascript" charset="utf-8"></script>
<script src="{$JS_PATH}/uploader.js" type="text/javascript" charset="utf-8"></script>
<script src="{$JS_PATH}/jquery.fancybox-1.3.1.js" type="text/javascript" charset="utf-8"></script>
<link type="text/css" rel="stylesheet" href="{$CURHOST}/css/jquery.fancybox-1.3.1.css" />
{if isset($smarty.get.option) && $smarty.get.option eq 'viewstock'}
 <script>
$(document).ready(function(){
	$("table a#example").fancybox({
		'transitionIn'		: 'none',
		'transitionOut'		: 'none',
		'titlePosition' 	: 'over',
		'titleFormat'		: function(title, currentArray, currentIndex, currentOpts) {
		}
	});
});
</script>
{/if}
</body>
</html>