<?php /* Smarty version Smarty-3.0.7, created on 2011-05-03 09:03:19
         compiled from "templates/create_event_home.tpl" */ ?>
<?php /*%%SmartyHeaderCode:13994561734dbfc4d71e1319-51795149%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'b7ae3479f95a12653ee0188b0f59843c15052b44' => 
    array (
      0 => 'templates/create_event_home.tpl',
      1 => 1304413385,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '13994561734dbfc4d71e1319-51795149',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
)); /*/%%SmartyHeaderCode%%*/?>
<?php $_template = new Smarty_Internal_Template("header.tpl", $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
$_template->assign('title',"Jumpstart your social life"); echo $_template->getRenderedTemplate();?><?php unset($_template);?>
<?php $_template = new Smarty_Internal_Template("home_css.tpl", $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
 echo $_template->getRenderedTemplate();?><?php unset($_template);?>
<?php $_template = new Smarty_Internal_Template("cp_css.tpl", $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
 echo $_template->getRenderedTemplate();?><?php unset($_template);?>
</head>

<body>
<div id="container">
  <div id="header">
	<?php $_template = new Smarty_Internal_Template("home_header.tpl", $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
 echo $_template->getRenderedTemplate();?><?php unset($_template);?>
  </div>
  <div id="middle">
  <?php $_template = new Smarty_Internal_Template("create_event_form.tpl", $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
 echo $_template->getRenderedTemplate();?><?php unset($_template);?>
  </div>
  <div id="footer"></div>
</div>
<?php $_template = new Smarty_Internal_Template("global_js.tpl", $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
 echo $_template->getRenderedTemplate();?><?php unset($_template);?>
<script type="text/javascript" language="javascript" src="<?php echo $_smarty_tpl->getVariable('JS_PATH')->value;?>
/md5-min.js"></script>
<script type="text/javascript" language="javascript" src="<?php echo $_smarty_tpl->getVariable('JS_PATH')->value;?>
/login.js"></script>
<script type="text/javascript" language="javascript" src="<?php echo $_smarty_tpl->getVariable('JS_PATH')->value;?>
/create_event_form.js"></script>
</body>
<?php $_template = new Smarty_Internal_Template("footer.tpl", $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
 echo $_template->getRenderedTemplate();?><?php unset($_template);?>