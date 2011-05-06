<?php /* Smarty version Smarty-3.0.7, created on 2011-05-06 01:18:59
         compiled from "templates/event_middle.tpl" */ ?>
<?php /*%%SmartyHeaderCode:12712676824dc34c8362b539-26432180%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'e57c8769ae5dd77320c9a1c5b03f5e68f4001aa7' => 
    array (
      0 => 'templates/event_middle.tpl',
      1 => 1304644736,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '12712676824dc34c8362b539-26432180',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
)); /*/%%SmartyHeaderCode%%*/?>
<div id="event_info">
  <h2 id="event_title"><?php echo $_smarty_tpl->getVariable('eventInfo')->value['title'];?>
</h2>
  
  <div id="time_info">
    <h3 id="event_dayleft"><?php echo $_smarty_tpl->getVariable('eventInfo')->value['days_left'];?>
 days left</h3>
    <div id="event_datetime">
      <strong>When:</strong> <?php echo $_smarty_tpl->getVariable('eventInfo')->value['event_datetime'];?>

    </div>
  </div>
  
  <div id="event_picture_container">
  	<img src="<?php echo $_smarty_tpl->getVariable('CURHOST')->value;?>
/upload/<?php echo $_smarty_tpl->getVariable('eventInfo')->value['id'];?>
.jpg" id="event_picture" />
  </div>
  
  <div id="event_spots">
  	<?php echo $_smarty_tpl->getVariable('curSignUp')->value;?>
 / <?php echo $_smarty_tpl->getVariable('eventInfo')->value['min_spot'];?>
 spots left
  </div>
  
  <div id="event_attending">
    <a href="#" id="event-<?php echo $_smarty_tpl->getVariable('eventId')->value;?>
"><img src="<?php echo $_smarty_tpl->getVariable('EP_IMG_PATH')->value;?>
/yes.png" class="ep_yes" id="attend_event_confirm" /></a>
    <div id="event_cost_attend">$<?php echo $_smarty_tpl->getVariable('eventInfo')->value['cost'];?>
/spot</div>
  </div>
  
  <div id="event_metadata">
    <div id="event_description">
    <?php echo $_smarty_tpl->getVariable('eventInfo')->value['description'];?>

    </div>
    
    <div id="event_location">
    at <?php echo $_smarty_tpl->getVariable('eventInfo')->value['location_address'];?>

    </div>
        
    <div id="event_cost">
    $<?php echo $_smarty_tpl->getVariable('eventInfo')->value['cost'];?>
 <span id="event_gets_price">gets you</span>
    	<div id="event_gets">
      <?php echo $_smarty_tpl->getVariable('eventInfo')->value['gets'];?>

      </div>
    </div>
    
    <div id="event_organizer">
    This event is organized by
    <?php echo $_smarty_tpl->getVariable('organizer')->value['fname'];?>
 <?php echo $_smarty_tpl->getVariable('organizer')->value['lname'];?>

    </div>
  </div>
</div>