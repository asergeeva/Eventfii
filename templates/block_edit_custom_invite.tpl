<div style="width: 900px; margin: 0 auto; ">
        <div class="content_box">
                <div class="upload_brow" id="after_success" align="center">
                </div>
               <div class="photo_frame_outer">
                        <div class="fram_bot">
                            <div class="fram_cnt">
                                <div class="fram_top">
                                	<span id="image_view">
                                  {if $event_image && $event_image != ''}
			                			<a onclick="return launchEditor('image1', '{$CURHOST}/upload/events/{$event_image}');" href="javascript:void(0);"><img src="{$CURHOST}/upload/events/{$event_image}" alt="photo to edit" id="image1_after_save" /></a>
                                    {else}
                                    	<img src="{$CURHOST}/images/photo.png" alt="photo to edit" />
                                    {/if}
                								</span>
                                <div><span>{$smarty.session.manage_event->title}</span>{strtotime($smarty.session.manage_event->datetime)|date_format:"%B %e, %Y"} at {strtotime($smarty.session.manage_event->datetime)|date_format:"%l:%M%p"}</div>
                                </div>
                            </div>
                        </div>
                    </div>

            <div class="upload_brow">
			<div style="float: left; width: 180px; padding: 6px 0pt 0pt;">
            	<span style="font-weight:bold;">Upload your own photo: </span>
			</div>
            <div style="width: 80px; float: left; overflow:hidden;">
            	<form method="post" id="create_guests" enctype="multipart/form-data"><input type="file" name="file" id="file" /></form>
            </div>
<div class="clear"></div>
            </div>
            <div class="invit_sep"><strong>or</strong><span></span></div>
            <div class="chose_photo">
                <h3 class="title" style="font-weight:bold;">Choose a stock photo:</h3>
                <div class="chose_photo_inr">
                    <ul class="left">                    	
                    {foreach $stock as $n_stock name=foo}
                        <li onclick="showStockImages({$n_stock['id']});"><span id="stock_name_{$n_stock['id']}">{$n_stock['name']}</span></li>
                    {/foreach}
                    </ul>
                    <div id="scrollbar1" class="right">
                       <div class="scrollbar"><div class="track"><div class="thumb"><div class="end"></div></div></div></div>
                        <div class="viewport">
                            <div class="overview" id="show_images_div"></div>
                        </div>
                </div>
            </div>
        </div>
</div>