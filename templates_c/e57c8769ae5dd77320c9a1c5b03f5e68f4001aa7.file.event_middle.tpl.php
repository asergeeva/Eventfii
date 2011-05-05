<?php /* Smarty version Smarty-3.0.7, created on 2011-05-05 08:46:57
         compiled from "templates/event_middle.tpl" */ ?>
<?php /*%%SmartyHeaderCode:17026527784dc264011c6dc0-13675251%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'e57c8769ae5dd77320c9a1c5b03f5e68f4001aa7' => 
    array (
      0 => 'templates/event_middle.tpl',
      1 => 1304584557,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '17026527784dc264011c6dc0-13675251',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
)); /*/%%SmartyHeaderCode%%*/?>
<div id="event_info">
  <h2><?php echo $_smarty_tpl->getVariable('eventInfo')->value['title'];?>
</h2>
  <h3>Days left: <?php echo $_smarty_tpl->getVariable('eventInfo')->value['days_left'];?>
</h3>
  
  <img src="<?php echo $_smarty_tpl->getVariable('CURHOST')->value;?>
/upload/<?php echo $_smarty_tpl->getVariable('eventInfo')->value['id'];?>
.jpg"
  
  <div id="event_details">
  <h3>Description</h3>
  <?php echo $_smarty_tpl->getVariable('eventInfo')->value['description'];?>

  </div>
  
  <div id="event_location">
  <h3>Location</h3>
  <?php echo $_smarty_tpl->getVariable('eventInfo')->value['location_address'];?>

  </div>
  
  <div id="event_datetime">
  <h3>Date &amp; Time</h3>
  <?php echo $_smarty_tpl->getVariable('eventInfo')->value['event_datetime'];?>

  </div>
  
  <div id="event_attendance">
  <h3>Spots</h3>
  <?php echo $_smarty_tpl->getVariable('curSignUp')->value;?>
 / <?php echo $_smarty_tpl->getVariable('eventInfo')->value['min_spot'];?>

  </div>
  
  <div id="event_organizer">
  <h3>Event Organizer</h3>
  <?php echo $_smarty_tpl->getVariable('organizer')->value['fname'];?>
 <?php echo $_smarty_tpl->getVariable('organizer')->value['lname'];?>

  </div>
  
  <div id="event_cost">
  <h3>Cost</h3>
  $<?php echo $_smarty_tpl->getVariable('eventInfo')->value['cost'];?>

  </div>
  
  <div id="event_attending">
  <h2>Attending?</h2>
  <a href="#" id="event-<?php echo $_smarty_tpl->getVariable('eventId')->value;?>
"><img src="<?php echo $_smarty_tpl->getVariable('EP_IMG_PATH')->value;?>
/yes.png" class="ep_yes" id="attend_event_confirm" /></a>
  </div>
</div>