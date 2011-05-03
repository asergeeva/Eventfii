<?php /* Smarty version Smarty-3.0.7, created on 2011-05-03 09:03:16
         compiled from "templates/home_container.tpl" */ ?>
<?php /*%%SmartyHeaderCode:9420425944dbfc4d4ea7024-34502271%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '8eeb17e7dc3d6f36b08755b0f0051c23586c548b' => 
    array (
      0 => 'templates/home_container.tpl',
      1 => 1303332514,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '9420425944dbfc4d4ea7024-34502271',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
)); /*/%%SmartyHeaderCode%%*/?>
<div id="home_container">
  <div id="header">
  <?php $_template = new Smarty_Internal_Template("home_header_no_logo.tpl", $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
 echo $_template->getRenderedTemplate();?><?php unset($_template);?>
  </div>
  <div id="middle">
    <div id="middle_left">
      <div id="home_logo_container"><a href="<?php echo $_smarty_tpl->getVariable('CURHOST')->value;?>
"><img src="<?php echo $_smarty_tpl->getVariable('IMG_PATH')->value;?>
/profilepage4_15_03.png" id="ef_home_logo" /></a></div>
      <div id="create_event_container">
        <form id="create_event_home" name="create_event_home" method="post" action="home">
        <input id="create_event_textarea" name="eventTitle" value="What are you planning?"></input>
        <input type="image" name="event_submit_btn" id="event_submit_btn" src="images/biggobutton_03.png" />
        </form>
      </div>
    </div>
    <div id="steps_container">
      <a href="#"><img src="images/icons_03.png" alt="Start with an idea and a goal" /></a>
      <a href="#"><img src="images/icons_05.png" alt="Get people to join in" /></a>
      <a href="#"><img src="images/icons_07.png" alt="Goal reached, event is on!" /></a>
    </div>
    <div id="new_events">
      <h2>What's happening now?</h2>
    </div>
  </div>
  <div id="footer"></div>
</div>